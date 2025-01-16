<template>
    <main-content columnClass="col-12 col-md-10 col-lg-8 col-xl-7">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-12" :class="{'has-danger':errorExists('idetiquetatele')}">
                    <label>Etiqueta Telefonica <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="etiquetatelefonica.etiquetatele"/>
                    <small class="form-control-feedback" v-if="errorExists('idetiquetatele')" v-text="showError('idetiquetatele').errorDetail"></small>
                </div>
                <hr class="mt-2">
                <div class="form-group col-12 col-sm-12" >
                    <label><small>Opciones adicionales:</small></label><br>

                    <label>Seleccione color de fondo: &nbsp;</label>
                    <v-input-colorpicker v-model="etiquetatelefonica.backgroundColor"/> &nbsp; &nbsp;

                    <label>Seleccione color de texto: &nbsp;</label>
                    <v-input-colorpicker v-model="etiquetatelefonica.textColor"/>
                </div>
                <div class="form-group col-12 col-sm-12" >
                    <label><small>Vista previa:</small></label><br>
                    <h4><span v-bind:style="{background: etiquetatelefonica.backgroundColor, color:etiquetatelefonica.textColor}" class="badge badge-pill badge-lg mb-3" v-text="etiquetatelefonica.etiquetatele"></span></h4>
                </div>
            </div>
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'sisfe.etiquetaTelefonica'}" class="btn waves-effect waves-light btn-info mr-2">
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
    import VInputColorpicker from 'vue-native-color-picker';

    export default {
        props: {
            cardTitle: {
                default: 'EtiquetaTelefonica'
            },
            etiquetatelefonica: {
                type: Object,
                default: function () {
                    return {
                        etiquetatele: '',
                        color:'',
                        backgroundColor: '#d3d3d3',
                        textColor: '#000',
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

                let EtiquetaTelefonicaData = {
                    etiquetatele: this.etiquetatelefonica.etiquetatele,
                    //color: this.etiquetatelefonica.color,
                    backgroundColor: this.etiquetatelefonica.backgroundColor,
                    textColor: this.etiquetatelefonica.textColor,
                }

                this.$emit('saveData', EtiquetaTelefonicaData);
            },

            validateFields() {
                this.errors = [];

                if (!this.etiquetatelefonica.etiquetatele) {
                    this.setError('etiquetatelefonica', 'El campo Etiqueta es obligatorio');
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
        components: {
            MainContent,
            VInputColorpicker,
        }
    }

</script>
