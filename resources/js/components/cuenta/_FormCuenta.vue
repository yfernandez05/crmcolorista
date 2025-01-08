<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('empresa')}">
                    <label>Empresa <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="cuenta.empresa" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('empresa')" v-text="showError('empresa').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('ruc')}">
                    <label>Ruc <small class="text-danger">(*)</small></label>
                    <vue-numeric class="form-control" ref="ruc" 
                        @keypress.native.enter.prevent="doSaveData"
                        thousand-separator="" v-model="cuenta.ruc" v-bind:precision="0" >
                    </vue-numeric>
                    <small class="form-control-feedback" v-if="errorExists('ruc')" v-text="showError('ruc').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('razonsocial')}">
                    <label>Razón Social <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="cuenta.razonsocial" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('razonsocial')" v-text="showError('razonsocial').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('contrato')}">
                    <label>Contrato </label>
                    <input type="text" class="form-control" v-model="cuenta.contrato" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('contrato')" v-text="showError('contrato').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fechainicio')}">
                    <label>Fecha Inicio <small class="text-danger">(*)</small></label>
                    <v-date-picker v-model="cuenta.fechainicio"
                        format="DD-MM-YYYY"
                        value-type="format"
                        placeholder="Seccione"
                    >
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fechainicio')" v-text="showError('fechainicio').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('mensual')}">
                    <label>Mensual </label>
                    <vue-numeric class="form-control" ref="mensual" 
                        @keypress.native.enter.prevent="doSaveData"
                        thousand-separator="" v-model="cuenta.mensual" v-bind:precision="0" >
                    </vue-numeric>
                    <small class="form-control-feedback" v-if="errorExists('mensual')" v-text="showError('mensual').errorDetail"></small>
                </div>
            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.cuenta'}" class="btn waves-effect waves-light btn-info mr-2">
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

    export default {
        props: {
            cardTitle: {
                default: 'Cuenta'
            },
            cuenta: {
                type: Object,
                default() {
                    return {
                        empresa: '',
                        ruc: '',
                        razonsocial: '',
                        contrato: '',
                        fechainicio: '',
                        mensual: '',
                    }
                }
            }
        },
        data(){
            return {
                errors:[]
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let rolData = {
                    empresa: this.cuenta.empresa,
                    ruc: this.cuenta.ruc,
                    razonsocial: this.cuenta.razonsocial,
                    contrato: this.cuenta.contrato,
                    fechainicio: this.cuenta.fechainicio,
                    mensual: this.cuenta.mensual,
                }

                this.$emit('saveData', rolData);
            },

            validateFields() {
                this.errors = [];

                if (!this.cuenta.empresa) {
                    this.setError('empresa', 'El campo Empresa es obligatorio');
                }
                if (!this.cuenta.ruc) {
                    this.setError('ruc', 'El campo Ruc es obligatorio');
                }
                if (!this.cuenta.razonsocial) {
                    this.setError('razonsocial', 'El campo Razón Social es obligatorio');
                }
                if (!this.cuenta.fechainicio) {
                    this.setError('fechainicio', 'El campo Fecha Inicio es obligatorio');
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
            this.cuenta.fechainicio = this.formatDate(new Date(),'DD-MM-YYYY');
        },
        components: {
            MainContent,
            VueNumeric,
            VDatePicker
        }
    }

</script>
