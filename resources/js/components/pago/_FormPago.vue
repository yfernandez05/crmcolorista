<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('matricula_id')}">
                    <label>Matricula <small class="text-danger">(*)</small></label>
                    <select2 :options="matriculas" v-model="pago.matricula_id" :selectValue="pago.matricula_id"
                        placeholder="Seleccione una matricula" keyProperty="id" textProperty="detalle">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('matricula_id')"
                        v-text="showError('matricula_id').errorDetail"></small>
                </div>              

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('concepto_id')}">
                    <label>Concepto <small class="text-danger">(*)</small></label>
                    <select2 :options="conceptopagos" v-model="pago.concepto_id" :selectValue="pago.concepto_id"
                        placeholder="Seleccione una concepto" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('concepto_id')"
                        v-text="showError('concepto_id').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('monto')}">
                    <label>Monto </label>
                    <vue-numeric class="form-control" ref="monto" 
                        @keypress.native.enter.prevent="doSaveData"
                        thousand-separator="," v-model="pago.monto" v-bind:precision="2"  currency="S/">
                    </vue-numeric>

                    <small class="form-control-feedback" v-if="errorExists('monto')" v-text="showError('monto').errorDetail"></small>
                </div>

                <div class="form-group col-12" :class="{'has-danger':errorExists('detalle')}">
                    <label>Detalle<small class="text-danger">(*)</small></label>
                    <textarea class="form-control"  v-model="pago.detalle" @:keyup.enter ="doSaveData" rows="2"></textarea>
                    <small class="form-control-feedback" v-if="errorExists('detalle')" v-text="showError('detalle').errorDetail"></small>
                </div>
              
            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.pago'}" class="btn waves-effect waves-light btn-info mr-2">
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
    import VueNumeric from 'vue-numeric';
    import 'vue2-datepicker/locale/es';

    export default {
        props: {
            cardTitle: {
                default: 'pago'
            },
            pago: {
                type: Object,
                default() {
                    return {
                        detalle: '',
                        monto:'',
                        concepto_id: '',
                        matricula_id: '',
                    }
                }
            }
        },
        data(){
            return {
                conceptopagos: [],
                matriculas: [],
                errors: []
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let pagoData = {
                    detalle: this.pago.detalle,
                    monto: this.pago.monto,
                    matricula_id: this.pago.matricula_id,
                    concepto_id: this.pago.concepto_id,
                }

                this.$emit('saveData', pagoData);
            },

            validateFields() {
                this.errors = [];

                if (!this.pago.detalle) {
                    this.setError('detalle', 'El campo detalle es obligatorio');
                }
                if (!this.pago.monto) {
                    this.setError('monto', 'El campo monto es obligatorio');
                }
                if (!this.pago.matricula_id) {
                    this.setError('matricula_id', 'El campo matricula es obligatorio');
                }
                if (!this.pago.concepto_id) {
                    this.setError('concepto_id', 'El campo concepto es obligatorio');
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
            listarMatriculas() {
                let vm = this;
                axios.get(`${appApiUrl}/matricula/select`)
                    .then(function (response) {
                        vm.matriculas = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listarConceptos() {
                let vm = this;
                axios.get(`${appApiUrl}/conceptopago/select`)
                    .then(function (response) {
                        vm.conceptopagos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },


        },
        mounted(){
            this.listarMatriculas();
            this.listarConceptos();
        },
        components: {
            MainContent,
            Select2,
            VueNumeric
        }
    }

</script>
