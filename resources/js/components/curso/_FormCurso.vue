<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombre')}">
                    <label>Nombre <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="curso.nombre" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('nombre')" v-text="showError('nombre').errorDetail"></small>
                </div> 
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('duracion_meses')}">
                    <label>Duración Meses</label>
                    <input type="text" class="form-control" v-model="curso.duracion_meses" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('duracion_meses')" v-text="showError('duracion_meses').errorDetail"></small>
                </div>                  
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('descripcion')}">
                    <label>Descripción</label>
                    <input type="text" class="form-control" v-model="curso.descripcion" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('descripcion')" v-text="showError('descripcion').errorDetail"></small>
                </div>                
                            
            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.curso'}" class="btn waves-effect waves-light btn-info mr-2">
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
                default: 'curso'
            },
            curso: {
                type: Object,
                default() {
                    return {
                        nombre: '',
                        descripcion: '',
                        duracion_meses: '',
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
                    nombre: this.curso.nombre,
                    descripcion: this.curso.descripcion,
                    duracion_meses: this.curso.duracion_meses,
                }

                this.$emit('saveData', rolData);
            },

            validateFields() {
                this.errors = [];

                if (!this.curso.nombre) {
                    this.setError('nombre', 'El campo nombre es obligatorio');
                }
                if (!this.curso.duracion_meses) {
                    this.setError('duracion_meses', 'El campo duracion_meses es obligatorio');
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
