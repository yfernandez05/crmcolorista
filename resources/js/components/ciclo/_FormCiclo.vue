<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombre')}">
                    <label>Nombre <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="ciclo.nombre" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('nombre')" v-text="showError('nombre').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('duracion')}">
                    <label>Duracion meses <small class="text-danger">(*)</small></label>
                    <input type="number" class="form-control" v-model="ciclo.duracion" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('duracion')" v-text="showError('duracion').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('preciomes')}">
                    <label>Precio por Mes <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="ciclo.preciomes" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('preciomes')" v-text="showError('preciomes').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('descripcion')}">
                    <label>Descripción</label>
                    <input type="text" class="form-control" v-model="ciclo.descripcion" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('descripcion')" v-text="showError('descripcion').errorDetail"></small>
                </div>
            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.ciclo'}" class="btn waves-effect waves-light btn-info mr-2">
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
                default: 'Ciclo'
            },
            ciclo: {
                type: Object,
                default() {
                    return {
                        nombre: '',
                        descripcion: '',
                        duracion: 0,
                        preciomes: 0.00,
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
                    nombre: this.ciclo.nombre,
                    descripcion: this.ciclo.descripcion,
                    duracion: this.ciclo.duracion,
                    preciomes: this.ciclo.preciomes,
                }

                this.$emit('saveData', rolData);
            },

            validateFields() {
                this.errors = [];

                if (!this.ciclo.nombre) {
                    this.setError('nombre', 'El campo nombre es obligatorio');
                }

                if (!this.ciclo.duracion) {
                    this.setError('duracion', 'El campo duracion es obligatorio');
                }

                if (!this.ciclo.preciomes) {
                    this.setError('preciomes', 'El campo precio por mes es obligatorio');
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
        }
    }

</script>
