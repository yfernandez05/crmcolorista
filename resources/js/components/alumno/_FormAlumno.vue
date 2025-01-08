<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombre')}">
                    <label>Nombre <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="alumno.nombre" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('nombre')" v-text="showError('nombre').errorDetail"></small>
                </div>                
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('apellido')}">
                    <label>Apellido <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="alumno.apellido" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('apellido')" v-text="showError('apellido').errorDetail"></small>
                </div>                
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fecha_nac')}">
                    <label>Fecha Nacimiento </label>
                    <v-date-picker v-model="alumno.fecha_nac"
                        format="DD-MM-YYYY"
                        value-type="format"
                        placeholder="Seccione">
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fecha_nac')" v-text="showError('fecha_nac').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('correo')}">
                    <label>Correo <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="alumno.correo" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('correo')" v-text="showError('correo').errorDetail"></small>
                </div>                
            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.condicion'}" class="btn waves-effect waves-light btn-info mr-2">
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

    export default {
        props: {
            cardTitle: {
                default: 'Alumno'
            },
            alumno: {
                type: Object,
                default() {
                    return {
                        nombre: '',
                        apellido: '',
                        fecha_nac: '',
                        correo: '',
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
                    nombre: this.alumno.nombre,
                    apellido: this.alumno.apellido,
                    correo: this.alumno.correo,
                    fecha_nac: this.alumno.fecha_nac,
                }

                this.$emit('saveData', rolData);
            },

            validateFields() {
                this.errors = [];

                if (!this.alumno.nombre) {
                    this.setError('nombre', 'El campo nombre es obligatorio');
                }
                if (!this.alumno.apellido) {
                    this.setError('apellido', 'El campo apellido es obligatorio');
                }
                if (!this.alumno.correo) {
                    this.setError('correo', 'El campo correo es obligatorio');
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
            VDatePicker
        }
    }

</script>
