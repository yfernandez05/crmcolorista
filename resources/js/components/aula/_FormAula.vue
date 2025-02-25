<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('carrera_id')}">
                    <label>Carrera<small class="text-danger">(*)</small></label>
                    <select2 :options="carreras" v-model="aula.carrera_id" :selectValue="aula.carrera_id"
                        placeholder="Seleccione una carrera" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('carrera_id')"
                        v-text="showError('carrera_id').errorDetail"></small>
                </div>
            </div>
            <hr class="mt-2">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3 col-xl-2 d-none d-md-block">
                    <h3 class="card-title mb-1">Aulas</h3>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombre_aula')}">
                    <label>Nombre de Aula <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="aula.nombre_aula" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('nombre_aula')" v-text="showError('nombre_aula').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('foro_maximo')}">
                    <label>Foro maximo</label>
                    <input type="text" class="form-control" v-model="aula.foro_maximo" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('foro_maximo')" v-text="showError('foro_maximo').errorDetail"></small>
                </div>
               <div class="form-group mb-1 col-sm-3 col-md-2 align-items-end justify-content-end">
                    <label class="mb-0">&nbsp;&nbsp;</label><br>
                    <button type="submit" @click.prevent="agregarDetalle()"
                        class="btn waves-effect waves-light btn-primary text-truncate px-2">
                        <i class="far fa-hand-point-up"></i> <span>Añadir Aula</span></button>
                </div>
            </div>
            <div class="row mt-2">
                <!-- Tabla de Detalles -->
                <div class="form-group col-12">
                    <table class="table table-sm table-hover table-striped table-bordered mb-2">
                        <thead class="thead-dark">
                            <tr>
                                <th>Cursos</th>
                                <th>Foro Max.</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(detalle, index) in aula.detalles" :key="index">
                                <td v-text="detalle.nombre_aula"></td>
                                <td v-text="detalle.foro_maximo"></td>
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
            <router-link :to="{name: 'spa.aula'}" class="btn waves-effect waves-light btn-info mr-2">
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
                default: 'Aula'
            },
            aula: {
                type: Object,
                default() {
                    return {
                        carrera_id: '',
                        nombre_aula: '',
                        foro_maximo: '',
                        detalles: [], // Array para los detalles
                        carrera: [],
                    }
                }
            }
        },
        data(){
            return {
                carreras: [],
                errors: [],
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let aulaData = {
                    carrera_id: this.aula.carrera_id,
                    detalles: this.aula.detalles,
                }

                this.$emit('saveData', aulaData);
            },
            agregarDetalle() {
                
                if (!this.aula.nombre_aula) {
                    this.setError('nombre_aula', 'Debe agregar el nombre de aula.');
                    return;
                }

                let detalle = {
                    nombre_aula: this.aula.nombre_aula,
                    foro_maximo: this.aula.foro_maximo ? this.aula.foro_maximo : null,
                };

                this.aula.detalles.push(detalle);

                // Limpiar los campos de detalle después de añadirlos
                this.aula.nombre_aula = '';
                this.aula.foro_maximo = '';
            },
            eliminarDetalle(index) {
                this.aula.detalles.splice(index, 1);
            },

            validateFields() {
                this.errors = [];

                if (!this.aula.carrera_id) {
                    this.setError('carrera_id', 'El campo carrera es obligatorio');
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
            listarCarreras() {
                let vm = this;
                axios.get(`${appApiUrl}/carrera/select`)
                    .then(function (response) {
                        vm.carreras = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },

        },
        mounted(){
            this.listarCarreras();
        },
        components: {
            MainContent,
            Select2
        }
    }

</script>
