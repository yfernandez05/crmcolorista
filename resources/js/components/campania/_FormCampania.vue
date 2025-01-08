<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombrecampania')}">
                    <label>Nombre Campania <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="campania.nombrecampania" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('nombrecampania')" v-text="showError('nombrecampania').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('idcuenta')}">
                    <label>Empresa <small class="text-danger">(*)</small></label>
                    <select2 :options="cuentas" v-model="campania.idcuenta" :selectValue="campania.idcuenta"
                        placeholder="Seleccione Una cuenta" keyProperty="idcuenta" textProperty="empresa">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('idcuenta')"
                        v-text="showError('idcuenta').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fechainicio')}">
                    <label>Fecha Inicio <small class="text-danger">(*)</small></label>
                    <v-date-picker v-model="campania.fechainicio"
                        type="datetime"
                        format="DD-MM-YYYY HH:mm:ss"
                        value-type="format"
                        placeholder="Seccione"
                    >
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fechainicio')" v-text="showError('fechainicio').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fechafin')}">
                    <label>Fecha Fin <small class="text-danger">(*)</small></label>
                    <v-date-picker v-model="campania.fechafin"
                        type="datetime"
                        format="DD-MM-YYYY HH:mm:ss"
                        value-type="format"
                        placeholder="Seccione"
                    >
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fechafin')" v-text="showError('fechafin').errorDetail"></small>
                </div>
                
            
            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.campania'}" class="btn waves-effect waves-light btn-info mr-2">
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
    import VueNumeric from 'vue-numeric';
    import VDatePicker from 'vue2-datepicker';
    // import accounting from 'accounting';
    import moment,{ now } from 'moment';
    import 'vue2-datepicker/locale/es';
    import Select2 from './../../utils/Select2';

    export default {
        props: {
            cardTitle: {
                default: 'campanias'
            },
            campania: {
                type: Object,
                default() {
                    return {
                        nombrecampania: '',
                        fechainicio: '',
                        fechafin: '',
                    }
                }
            }
        },
        data(){
            return {
                cuentas: [],
                errors:[],
                
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let CampaniaData = {
                    nombrecampania: this.campania.nombrecampania,
                    fechainicio: this.campania.fechainicio,
                    fechafin: this.campania.fechafin,
                    idcuenta: this.campania.idcuenta
                }

                this.$emit('saveData', CampaniaData);
            },
            listarCuentas() {
                let vm = this;
                axios.get(`${appApiUrl}/cuenta/select`)
                    .then(function (response) {
                        vm.cuentas = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            validateFields() {
                this.errors = [];

                if (!this.campania.nombrecampania) {
                    this.setError('nombrecampania', 'El campo Nombre Campania es obligatorio');
                }
                if (!this.campania.idcuenta) {
                    this.setError('idcuenta', 'El campo cuenta es obligatorio');
                }

                if (!this.campania.fechainicio) {
                    this.setError('fechainicio', 'El campo Fecha Inicio es obligatorio');
                }
                if (!this.campania.fechafin) {
                    this.setError('fechafin', 'El campo Fecha Fin es obligatorio');
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
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
        },
        mounted(){
            this.campania.fechainicio = this.formatDate(new Date(),'DD-MM-YYYY HH:mm:ss');
            this.listarCuentas();
        },
        components: {
            MainContent,
            VueNumeric,
            VDatePicker,
            Select2
        }
    }

</script>

