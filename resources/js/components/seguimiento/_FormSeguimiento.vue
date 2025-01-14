<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('atencion')}">
                    <label>Atencion <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="seguimiento.atencion" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('atencion')" v-text="showError('atencion').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('toque')}">
                    <label>Toque <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="seguimiento.toque" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('toque')" v-text="showError('toque').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('respuesta')}">
                    <label>Respuesta <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="seguimiento.respuesta" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('respuesta')" v-text="showError('respuesta').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('prospecto_id')}">
                    <label>Prospecto<small class="text-danger">(*)</small></label>
                    <select2 :options="prospectos" v-model="seguimiento.prospecto_id" :selectValue="seguimiento.prospecto_id"
                        placeholder="Seleccione una Prospecto" keyProperty="prospecto_id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('prospecto_id')"
                        v-text="showError('prospecto_id').errorDetail"></small>
                </div>


            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.seguimiento'}" class="btn waves-effect waves-light btn-info mr-2">
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
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';
    import Select2 from './../../utils/Select2';

    export default {
        props: {
            cardTitle: {
                default: 'seguimiento'
            },
            seguimiento: {
                type: Object,
                default() {
                    return {
                        atencion: '',
                        toque: '',
                        respuesta: '',
                        prospecto_id: '',

                    }
                }
            }
        },
        data(){
            return {
                errors:[],
                prospectos: [],
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let seguimientoData = {
                    atencion: this.seguimiento.atencion,
                    toque: this.seguimiento.toque,
                    respuesta: this.seguimiento.respuesta,
                    prospecto_id: this.seguimiento.prospecto_id,

                }

                this.$emit('saveData', seguimientoData);
            },

            listarprospecto() {
            let vm = this;
            axios.get(`${appApiUrl}/prospecto/select`)
            .then(function (response) {
                vm.prospectos = response.data;
            })
            .catch(function (error) {
                console.log(error);
            })
        },


            validateFields() {
                this.errors = [];

                if (!this.seguimiento.atencion) {
                    this.setError('atencion', 'El campo atencion es obligatorio');
                }
                if (!this.seguimiento.toque) {
                    this.setError('toque', 'El campo toque es obligatorio');
                }
                if (!this.seguimiento.respuesta) {
                    this.setError('respuesta', 'El campo respuesta es obligatorio');
                }
                if (!this.seguimiento.prospecto_id) {
                    this.setError('prospecto_id', 'El campo prospecto es obligatorio');
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

        },
        //iniciamos lo metodos
        mounted(){
            this.listarprospecto();
        },
        components: {
            MainContent,
            VDatePicker,
            Select2
        }
    }

</script>
