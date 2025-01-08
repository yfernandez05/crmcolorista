<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombreevento')}">
                    <label>Nombre Stand <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="evento.nombreevento" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('nombreevento')" v-text="showError('nombreevento').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('idcampania')}">
                    <label>Campaña <small class="text-danger">(*)</small></label>
                    <select2 :options="campanias" v-model="evento.idcampania" :selectValue="evento.idcampania"
                        placeholder="Seleccione una campaña" keyProperty="idcampania" textProperty="nombrecampania">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('idcampania')"
                        v-text="showError('idcampania').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fechainicio')}">
                    <label>Fecha Inicio <small class="text-danger">(*)</small></label>
                    <v-date-picker v-model="evento.fechainicio"
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
                    <v-date-picker v-model="evento.fechafin"
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
            <router-link :to="{name: 'spa.evento'}" class="btn waves-effect waves-light btn-info mr-2">
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
//importamos componentes y librerias
import MainContent from './../../utils/MainContent';
import VueNumeric from 'vue-numeric';
import VDatePicker from 'vue2-datepicker';
import moment,{ now } from 'moment';
import 'vue2-datepicker/locale/es';
import Select2 from './../../utils/Select2';

export default {
    props: {
        cardTitle: {
            default: 'Stands'
        },
        evento: {
            type: Object,
            default() {
                return {
                    nombreevento: '',
                    idcampania: 0,
                    fechainicio: '',
                    fechafin: '',
                }
            }
        }
    },
    data(){
        return {
            campanias: [],
            errors:[],                
        }
    },

    //metodos
    methods: {
        doSaveData() {

            if (this.validateFields().length > 0) {
                return;
            }

            let eventosData = {
                nombreevento: this.evento.nombreevento,
                fechainicio: this.evento.fechainicio,
                fechafin: this.evento.fechafin,
                idcampania: this.evento.idcampania
            }

            this.$emit('saveData', eventosData);
        },

        listarCampanias() {
            let vm = this;
            axios.get(`${appApiUrl}/campania/select`)
            .then(function (response) {
                vm.campanias = response.data;
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        validateFields() {
            this.errors = [];

            if (!this.evento.nombreevento) {
                this.setError('nombreevento', 'El campo Nombre es obligatorio');
            }
            if (!this.evento.idcampania) {
                this.setError('idcampania', 'El campo Campaña es obligatorio');
            }

            if (!this.evento.fechainicio) {
                this.setError('fechainicio', 'El campo Fecha Inicio es obligatorio');
            }
            if (!this.evento.fechafin) {
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

    //iniciamos lo metodos
    mounted(){
        this.evento.fechainicio = this.formatDate(new Date(),'DD-MM-YYYY HH:mm:ss');
        this.listarCampanias();
    },

    //iniciamos los componentes
    components: {
        MainContent,
        VueNumeric,
        VDatePicker,
        Select2
    }
}

</script>