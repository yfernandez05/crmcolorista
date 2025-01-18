<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('carrera_id')}">
                    <label>Carreras <small class="text-danger">(*)</small></label>
                    <select2 :options="carreras" v-model="planestudio.carrera_id" :selectValue="planestudio.carrera_id"
                        placeholder="Seleccione una carrera" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('carrera_id')"
                        v-text="showError('carrera_id').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('ciclo_id')}">
                    <label>Modulo <small class="text-danger">(*)</small></label>
                    <select2 :options="ciclos" v-model="planestudio.ciclo_id" :selectValue="planestudio.ciclo_id"
                        placeholder="Seleccione un ciclo" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('ciclo_id')"
                        v-text="showError('ciclo_id').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('costo')}">
                    <label>Costo <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="planestudio.costo" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('costo')" v-text="showError('costo').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('duracion_meses')}">
                    <label>Duración de Meses <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="planestudio.duracion_meses" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('duracion_meses')" v-text="showError('duracion_meses').errorDetail"></small>
                </div>
            </div>
            <hr class="mt-2">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3 col-xl-2 d-none d-md-block">
                    <h3 class="card-title mb-1">Curso</h3>
                </div>
                <div class="form-group col-12 col-md-9 col-lg-9 col-xl-8" :class="{'has-danger':errorExists('curso_id')}">
                    <label>Curso <small class="text-danger">(*)</small></label>
                    <select2 :options="cursos" v-model="planestudio.curso_id" :selectValue="planestudio.curso_id"
                        placeholder="Seleccione un Curso" keyProperty="id" textProperty="nombre">
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
                            <tr v-for="(detalle, index) in planestudio.detalles" :key="index">
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
            <router-link :to="{name: 'spa.planestudio'}" class="btn waves-effect waves-light btn-info mr-2">
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
                default: 'Plan Estudio'
            },
            planestudio: {
                type: Object,
                default() {
                    return {
                        costo: '',
                        duracion_meses: '',
                        carrera_id: '',
                        ciclo_id: '',
                        detalles: [], // Array para los detalles
                        carrera: [],
                        ciclo: [],
                    }
                }
            }
        },
        data(){
            return {
                carreras: [],
                ciclos: [],
                errors: [],
                cursos: []
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let planestudioData = {
                    detalle: this.planestudio.detalle,
                    costo: this.planestudio.costo,
                    duracion_meses: this.planestudio.duracion_meses,
                    carrera_id: this.planestudio.carrera_id,
                    ciclo_id: this.planestudio.ciclo_id,
                    detalles: this.planestudio.detalles,
                }

                this.$emit('saveData', planestudioData);
            },
            agregarDetalle() {
                if (!this.planestudio.curso_id) {
                    this.setError('curso_id', 'Debe seleccionar un Curso.');
                    return;
                }
                console.log(this.planestudio.curso_id);

                let detalle = {
                    curso_id: this.planestudio.curso_id,
                    curso: this.cursos.find(c => c.id === Number(this.planestudio.curso_id)),
                };

                // Agregar el detalle a la lista
                this.planestudio.detalles.push(detalle);
                //console.log(this.planestudio.detalles);

                // Limpiar los campos de detalle después de añadirlos
                this.planestudio.curso_id = '';
            },
            eliminarDetalle(index) {
            this.planestudio.detalles.splice(index, 1);
            },

            validateFields() {
                this.errors = [];

                if (!this.planestudio.costo) {
                    this.setError('costo', 'El campo costo es obligatorio');
                }
                if (!this.planestudio.duracion_meses) {
                    this.setError('duracion_meses', 'El campo duracion es obligatorio');
                }
                if (!this.planestudio.carrera_id) {
                    this.setError('carrera_id', 'El campo carrera es obligatorio');
                }
                if (!this.planestudio.ciclo_id) {
                    this.setError('ciclo_id', 'El campo ciclo es obligatorio');
                }

                // if (!this.planestudio.detalle) {
                // this.setError('detalle', 'El campo detalle es obligatorio');
                // }
                // if (!Array.isArray(this.planestudio.detalles)) {
                // this.setError('curso_id', 'Debe seleccionar un Curso.');
                // }
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
            listarCiclos() {
                let vm = this;
                axios.get(`${appApiUrl}/ciclo/select`)
                    .then(function (response) {
                        vm.ciclos = response.data;
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
            this.listarCarreras();
            this.listarCiclos();
            this.listarcurso();
        },
        components: {
            MainContent,
            Select2
        }
    }

</script>
