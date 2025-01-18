<template>
    <div>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombre')}">
                    <label>Nombre <small class="text-danger">(*)</small></label>
                    <div class="input-group">
                        <input type="text" class="form-control" v-model="alumno.nombre" @keyup.enter="doSaveData"/>
                        <div class="input-group-append">
                            <button type="button" title="Buscar Prospecto" @click="buscarprospecto"
                                class="btn btn-success waves-effect waves-light">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <small class="form-control-feedback" v-if="errorExists('nombre')" v-text="showError('nombre').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('apellido')}">
                    <label>Apellido <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="alumno.apellido" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('apellido')" v-text="showError('apellido').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fecha_nac')}">
                    <label>Fecha Nacimiento </label>
                    <v-date-picker v-model="alumno.fecha_nac"
                        format="DD-MM-YYYY"
                        value-type="format"
                        placeholder="Seccione">
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fecha_nac')" v-text="showError('fecha_nac').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('correo')}">
                    <label>Correo <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="alumno.correo" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('correo')" v-text="showError('correo').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('dni')}">
                    <label>DNI </label>
                    <input type="text" class="form-control" v-model="alumno.dni" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('dni')" v-text="showError('dni').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('direccion')}">
                    <label>Dirección </label>
                    <input type="text" class="form-control" v-model="alumno.direccion" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('direccion')" v-text="showError('direccion').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('distrito')}">
                    <label>Distrito </label>
                    <input type="text" class="form-control" v-model="alumno.distrito" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('distrito')" v-text="showError('distrito').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('celular')}">
                    <label>Celular </label>
                    <input type="text" class="form-control" v-model="alumno.celular" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('celular')" v-text="showError('celular').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('empresa')}">
                    <label>Empresa </label>
                    <input type="text" class="form-control" v-model="alumno.empresa" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('empresa')" v-text="showError('empresa').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('cargodesempenia')}">
                    <label>Cargo que desempeña </label>
                    <input type="text" class="form-control" v-model="alumno.cargodesempenia" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('cargodesempenia')" v-text="showError('cargodesempenia').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('colegiosegundario')}">
                    <label>Colegio segundario </label>
                    <input type="text" class="form-control" v-model="alumno.colegiosegundario" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('colegiosegundario')" v-text="showError('colegiosegundario').errorDetail"></small>
                </div>

            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.alumno'}" class="btn waves-effect waves-light btn-info mr-2">
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

    <ModalProspecto ref="ModalProspecto" :showModal="showModal" @closeModal="showModal = false" @prospectoSeleccionado="setProspecto"></ModalProspecto>

    </div>
</template>

<script>
    import MainContent from './../../utils/MainContent';
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';
    import _ from 'lodash';
    import vSelect from 'vue-select';
    import ModalProspecto from './_ModalProspecto';
    import moment,{ now } from 'moment';

    export default {
        props: {
            cardTitle: {
                default: 'Alumno'
            },
            alumno: {
                type: Object,
                default() {
                    return {
                        nombre: '',
                        apellido: '',
                        fecha_nac: '',
                        correo: '',
                        dni: '',
                        direccion: '',
                        distrito: '',
                        celular: '',
                        empresa: '',
                        cargodesempenia: '',
                        colegiosegundario: '',

                    }
                }
            }
        },
        data(){
            return {
                errors:[],
                prospectos: [],
                showModal: false,
                prospectoSeleccionado: {
                    id: 0,
                    nombre: '',
                },
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let rolData = {
                    nombre: this.alumno.nombre,
                    apellido: this.alumno.apellido,
                    correo: this.alumno.correo,
                    fecha_nac: this.alumno.fecha_nac,
                    dni: this.alumno.dni,
                    direccion: this.alumno.direccion,
                    distrito: this.alumno.distrito,
                    celular: this.alumno.celular,
                    empresa: this.alumno.empresa,
                    cargodesempenia: this.alumno.cargodesempenia,
                    colegiosegundario: this.alumno.colegiosegundario,
                }

                this.$emit('saveData', rolData);
            },

            validateFields() {
                this.errors = [];

                if (!this.alumno.nombre) {
                    this.setError('nombre', 'El campo nombre es obligatorio');
                }
                if (!this.alumno.apellido) {
                    this.setError('apellido', 'El campo apellido es obligatorio');
                }
                if (!this.alumno.correo) {
                    this.setError('correo', 'El campo correo es obligatorio');
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

            buscarprospecto(event) {
                this.showModal = true;
                this.$nextTick(() => {
                    this.$refs.ModalProspecto.showDetail(event);
                });

            },
            setProspecto(prospecto) {
                this.alumno.nombre = prospecto.nombre;
                this.alumno.apellido = prospecto.apellido;
                this.alumno.correo = prospecto.correo;
                this.alumno.celular = prospecto.telefono;
                this.alumno.fecha_nac = this.formatDate(prospecto.fecha_nac, 'DD-MM-YYYY');

            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },

        },
        components: {
            MainContent,
            VDatePicker,
            vSelect,
            ModalProspecto
        }
    }

</script>
