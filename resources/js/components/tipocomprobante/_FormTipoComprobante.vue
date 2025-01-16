<template>
    <main-content columnClass="col-12 col-md-10 col-lg-8 col-xl-7">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>
        <template v-slot:card-body-main>

            <div class="form-row">
                <div class="form-group col-12" :class="{'has-danger':errorExists('nombrecomprobante')}">
                    <label>Nombre <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="tipoComprobante.nombrecomprobante" />
                    <small class="form-control-feedback" v-if="errorExists('nombrecomprobante')" v-text="showError('nombrecomprobante').errorDetail"></small>
                </div>

                 <div class="form-group col-12 col-sm-6" :class="{'has-danger':errorExists('codigosunat')}">
                    <label>Codigo sunat <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="tipoComprobante.codigosunat" maxlength="2"  />
                    <small class="form-control-feedback" v-if="errorExists('codigosunat')" v-text="showError('codigosunat').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6" :class="{'has-danger':errorExists('serie')}">
                    <label>Serie <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="tipoComprobante.serie" maxlength="4"  />
                    <small class="form-control-feedback" v-if="errorExists('serie')" v-text="showError('serie').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input"
                         v-model="tipoComprobante.agregarigv" id="agregarigv">
                        <label class="custom-control-label" for="agregarigv">Agregar Igv</label>
                    </div>
                </div>
            </div>
            
            <hr class="mt-2">
        </template>
        <template v-slot:card-body-actions>
            <router-link :to="{name: 'sisfe.tipocomprobante'}" class="btn waves-effect waves-light btn-info mr-2">
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
    export default {
        props: {
            cardTitle: {
                default: 'Tipo comprobante'
            },
            tipoComprobante: {
                type: Object,
                default: function () {
                    return {
                        nombrecomprobante: '',
                        codigosunat: '',
                        serie: '',
                        agregarigv: false
                    }
                }
            }
        },
        data() {
            return {
                errors:[]
            }
        },
        methods: {
            doSaveData(){
                if(this.validateFields().length > 0 ){
                    return;
                }

                let tipoComprobanteData ={
                    nombrecomprobante:this.tipoComprobante.nombrecomprobante,
                    codigosunat:this.tipoComprobante.codigosunat,
                    serie:this.tipoComprobante.serie,
                    agregarigv:this.tipoComprobante.agregarigv
                }

                this.$emit('saveData', tipoComprobanteData);
            },
            validateFields(){
                this.errors=[];

                if(!this.tipoComprobante.nombrecomprobante){
                    this.setError('nombrecomprobante','El campo nombre es obligatorio');
                }
                if(!this.tipoComprobante.codigosunat){
                    this.setError('codigosunat','El campo codigo sunat es obligatorio');
                }
                if(!this.tipoComprobante.serie){
                    this.setError('serie','El campo serie es obligatorio');
                }

                return this.errors;
            },
            setError(keyModel, errorDetail){
                this.errors.push({keyModel:keyModel,errorDetail:errorDetail});
            },
            errorExists(keyModel){
                return this.errors.filter(err => err.keyModel === keyModel).length;
            },
            showError(keyModel){
                return this.errors.find(err => err.keyModel === keyModel);
            },
        },
        components: {
            MainContent
        }
    }

</script>
