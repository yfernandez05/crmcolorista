<template>
    <main-content columnClass="col-12 col-md-10 col-lg-8 col-xl-7">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-12" :class="{'has-danger':errorExists('nombre')}">
                    <label>Rol <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="rol.nombre"/>
                    <small class="form-control-feedback" v-if="errorExists('nombre')" v-text="showError('nombre').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-12" :class="{'has-danger':errorExists('descripcion')}">
                    <label>Descripción</label>
                    <input type="text" class="form-control" v-model="rol.descripcion"/>
                    <small class="form-control-feedback" v-if="errorExists('descripcion')" v-text="showError('descripcion').errorDetail"></small>
                </div>
            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.rol'}" class="btn waves-effect waves-light btn-info mr-2">
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
                default: 'Rol'
            },
            rol: {
                type: Object,
                default: function () {
                    return {
                        nombre: '',
                        descripcion: ''
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
                    nombre: this.rol.nombre,
                    descripcion: this.rol.descripcion,
                }

                this.$emit('saveData', rolData);
            },

            validateFields() {
                this.errors = [];

                if (!this.rol.nombre) {
                    this.setError('nombre', 'El campo nombre es obligatorio');
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
            MainContent
        }
    }

</script>
