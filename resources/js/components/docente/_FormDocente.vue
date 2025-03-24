<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('usuario_id')}">
                    <label>Docentes <small class="text-danger">(*)</small></label>
                    <select2 :options="userdocentes" v-model="docente.usuario_id" :selectValue="docente.usuario_id"
                        placeholder="Seleccione un docente" keyProperty="id" textProperty="name">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('usuario_id')"
                        v-text="showError('usuario_id').errorDetail"></small>
                </div>
            </div>
            <hr class="mt-2">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3 col-xl-2 d-none d-md-block">
                    <h3 class="card-title mb-1">Curso</h3>
                </div>
                <div class="form-group col-12 col-md-9 col-lg-9 col-xl-8" :class="{'has-danger':errorExists('curso_id')}">
                    <label>Curso <small class="text-danger">(*)</small></label>
                    <select2 :options="cursos" v-model="docente.curso_id" :selectValue="docente.curso_id"
                        placeholder="Seleccione un curso" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('curso_id')"
                        v-text="showError('curso_id').errorDetail"></small>
               </div>
               <div class="form-group mb-1 col-sm-3 col-md-2 align-items-end justify-content-end">
                    <label class="mb-0">&nbsp;&nbsp;</label><br>
                    <button type="submit" @click.prevent="agregarDetalle()"
                        class="btn waves-effect waves-light btn-primary text-truncate px-2">
                        <i class="far fa-hand-point-up"></i> <span>Añadir Curso</span></button>
                </div>
            </div>
            <div class="row mt-2">
                <!-- Tabla de Detalles -->
                <div class="form-group col-12">
                    <table class="table table-sm table-hover table-striped table-bordered mb-2">
                        <thead class="thead-dark">
                            <tr>
                                <th>Cursos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(detalle, index) in docente.detalles" :key="index">
                                <td v-text="detalle.curso.nombre"></td>
                                <td>
                                    <button class="btn btn-danger" @click="eliminarDetalle(index)">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.docente'}" class="btn waves-effect waves-light btn-info mr-2">
                <i class="fas fa-reply"></i> <span class="button-text">Atrás</span>
            </router-link>

            <div>
                <button type="button" class="btn btn-success waves-effect waves-light" @click="doSaveData">
                    <i class="fa fa-save"></i>
                    Guardar
                </button>
                <button type="reset" class="btn waves-effect waves-light btn-outline-secondary ml-2">
                    <i class="fa fa-window-close"></i> <span class="button-text">Cancelar</span>
                </button>
            </div>
        </template>

    </main-content>
</template>

<script>
    import MainContent from './../../utils/MainContent';
    import Select2 from './../../utils/Select2';

    export default {
        props: {
            cardTitle: {
                default: 'Registrar cursos para el docente'
            },
            docente: {
                type: Object,
                default() {
                    return {
                        detalles: [],
                        usuario_id: '',
                        curso_id: '',
                    }
                }
            }
        },
        data(){
            return {
                userdocentes: [],
                errors: [],
                cursos: []
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let docenteData = {
                    usuario_id: this.docente.usuario_id,
                    detalles: this.docente.detalles,
                }

                this.$emit('saveData', docenteData);
            },
            agregarDetalle() {
                if (!this.docente.curso_id) {
                    this.setError('curso_id', 'Debe seleccionar un Curso.');
                    return;
                }
                console.log(this.docente.curso_id);

                let existe = this.docente.detalles.some(detalle => detalle.curso_id === Number(this.docente.curso_id));

                if (existe) {
                    warningMessage('Este curso ya ha sido agregado.', 'Aviso');
                    return;
                }

                let detalle = {
                    curso_id: this.docente.curso_id,
                    curso: this.cursos.find(c => c.id === Number(this.docente.curso_id)),
                };

                // Agregar el detalle a la lista
                this.docente.detalles.push(detalle);
                //console.log(this.docente.detalles);

                // Limpiar los campos de detalle después de añadirlos
                this.docente.curso_id = '';
            },
            eliminarDetalle(index) {
            this.docente.detalles.splice(index, 1);
            },

            validateFields() {
                this.errors = [];

                if (!this.docente.usuario_id) {
                    this.setError('usuario_id', 'El campo docente es obligatorio');
                }
                if (!this.docente.detalles || this.docente.detalles.length === 0) {
                    this.setError('curso_id', 'Debe agregar al menos un curso');
                }

                return this.errors;
            },
            setError(keyModel, errorDetail) {
                this.errors.push({
                    keyModel: keyModel,
                    errorDetail: errorDetail
                });
            },
            errorExists(keyModel){
                return this.errors.filter(err => err.keyModel === keyModel).length;
            },
            showError(keyModel){
                return this.errors.find(err => err.keyModel === keyModel);
            },
            listarUsuarioDocente() {
                let vm = this;
                axios.get(`${appApiUrl}/user/selectdocente`)
                    .then(function (response) {
                        vm.userdocentes = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            
            listarcurso() {
            axios.get(`${appApiUrl}/curso/select`)
                .then(response => {
                    this.cursos = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
            },

        },
        mounted(){
            this.listarUsuarioDocente();
            this.listarcurso();
        },
        components: {
            MainContent,
            Select2
        }
    }

</script>
