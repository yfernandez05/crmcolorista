<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('detalle')}">
                    <label>Detalle <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="inscripcion.detalle" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('detalle')" v-text="showError('detalle').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fecha')}">
                    <label>Fecha</label>
                    <v-date-picker v-model="inscripcion.fecha"
                        format="DD-MM-YYYY"
                        value-type="format"
                        placeholder="Seccione una fecha">
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fecha')" v-text="showError('fecha').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('alumno_id')}">
                    <label>Alumno <small class="text-danger">(*)</small></label>
                    <select2 :options="alumnos" v-model="inscripcion.alumno_id" :selectValue="inscripcion.alumno_id"
                        placeholder="Seleccione un alumno" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('alumno_id')"
                        v-text="showError('alumno_id').errorDetail"></small>
                </div>              

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('carrera_id')}">
                    <label>Carrera <small class="text-danger">(*)</small></label>
                    <select2 :options="carreras" v-model="inscripcion.carrera_id" :selectValue="inscripcion.carrera_id"
                        placeholder="Seleccione una carrera" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('carrera_id')"
                        v-text="showError('carrera_id').errorDetail"></small>
                </div>              
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('user_id')}">
                    <label>Usuario <small class="text-danger">(*)</small></label>
                    <select2 :options="users" v-model="inscripcion.user_id" :selectValue="inscripcion.user_id"
                        placeholder="Seleccione un usuario" keyProperty="id" textProperty="name">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('user_id')"
                        v-text="showError('user_id').errorDetail"></small>
                </div>              
            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.inscripcion'}" class="btn waves-effect waves-light btn-info mr-2">
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
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';

    export default {
        props: {
            cardTitle: {
                default: 'Inscripcion'
            },
            inscripcion: {
                type: Object,
                default() {
                    return {
                        detalle: '',
                        fecha:'',
                        alumno_id: '',
                        carrera_id: '',
                        user_id: '',
                    }
                }
            }
        },
        data(){
            return {
                carreras: [],
                alumnos: [],
                users: [],
                errors: []
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let inscripcionData = {
                    detalle: this.inscripcion.detalle,
                    fecha: this.inscripcion.fecha,
                    alumno_id: this.inscripcion.alumno_id,
                    carrera_id: this.inscripcion.carrera_id,
                    user_id: this.inscripcion.user_id
                }

                this.$emit('saveData', inscripcionData);
            },

            validateFields() {
                this.errors = [];

                if (!this.inscripcion.detalle) {
                    this.setError('detalle', 'El campo detalle es obligatorio');
                }
                if (!this.inscripcion.fecha) {
                    this.setError('fecha', 'El campo fecha es obligatorio');
                }
                if (!this.inscripcion.alumno_id) {
                    this.setError('alumno_id', 'El campo alumno es obligatorio');
                }
                if (!this.inscripcion.carrera_id) {
                    this.setError('carrera_id', 'El campo carrera es obligatorio');
                }
                if (!this.inscripcion.user_id) {
                    this.setError('user_id', 'El campo usuario es obligatorio');
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
            listarAlumnos() {
                let vm = this;
                axios.get(`${appApiUrl}/alumno/select`)
                    .then(function (response) {
                        vm.alumnos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
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
            listarUsuarios() {
                let vm = this;
                axios.get(`${appApiUrl}/user/select`)
                    .then(function (response) {
                        vm.users = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },

        },
        mounted(){
            this.listarAlumnos();
            this.listarCarreras();
            this.listarUsuarios();
        },
        components: {
            MainContent,
            Select2,
            VDatePicker
        }
    }

</script>
