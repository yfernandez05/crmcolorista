<template>
    <main-content columnClass="col-12 col-md-11 col-lg-12">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">                          
                <div class="form-group col-12 col-sm-4" :class="{'has-danger':errorExists('name')}">
                    <label>Nombres <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="user.name" />
                    <small class="form-control-feedback" v-if="errorExists('name')"
                        v-text="showError('name').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-4" :class="{'has-danger':errorExists('last_name')}">
                    <label>Apellidos <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="user.last_name" />
                    <small class="form-control-feedback" v-if="errorExists('last_name')"
                        v-text="showError('last_name').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-4" :class="{'has-danger':errorExists('specialities')}">
                    <label>Especialidad</label>
                    <input type="text" class="form-control" v-model="user.specialities" />
                    <small class="form-control-feedback" v-if="errorExists('specialities')"
                        v-text="showError('specialities').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-4" :class="{'has-danger':errorExists('email')}">
                    <label>Email <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="user.email" />
                    <small class="form-control-feedback" v-if="errorExists('email')"
                        v-text="showError('email').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-4" :class="{'has-danger':errorExists('idrol')}">
                    <label>Rol <small class="text-danger">(*)</small></label>
                    <select2 :options="roles" v-model="user.rol_id" :selectValue="user.rol_id"
                        placeholder="Seleccione Un rol" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('rol_id')"
                        v-text="showError('rol_id').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-4" :class="{'has-danger':errorExists('password')}"
                    v-if="showPasswordField">
                    <label>Password <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="user.password" />
                    <small class="form-control-feedback" v-if="errorExists('password')"
                        v-text="showError('password').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-4" :class="{'has-danger':errorExists('password_confirmation')}"
                    v-if="showPasswordField">
                    <label>Confirmar Password <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="user.password_confirmation" />
                    <small class="form-control-feedback" v-if="errorExists('password_confirmation')"
                        v-text="showError('password_confirmation').errorDetail"></small>
                </div>
            </div>

            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.user'}" class="btn waves-effect waves-light btn-info mr-2">
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


    export default {
        props: {
            cardTitle: {
                default: 'user'
            },
            user: {
                type: Object,
                // a factory function
                default: function () {
                    return {
                        name: '',
                        last_name: '',
                        specialities: '',
                        email: '',
                        password: '',
                        password_confirmation: '',
                    }
                }
            },
            showPasswordField: {
                type: Boolean,
                default: true
            }

        },
        data() {
            return {
                roles: [],
                errors: [],

            }
        },
        methods: {
            listarRoles() {
                let vm = this;
                axios.get(`${appApiUrl}/rol/select`)
                    .then(function (response) {
                        vm.roles = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },

            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let userData = {
                    name: this.user.name,
                    last_name: this.user.last_name,
                    specialities: this.user.specialities,
                    email: this.user.email,
                    //password:this.user.password,
                    rol_id: this.user.rol_id,
                };

                if (this.showPasswordField)
                    userData.password = this.user.password;

                this.$emit('saveData', userData);
            },
            validateFields() {
                this.errors = [];

                if (!this.user.rol_id) {
                    this.setError('rol_id', 'El campo Rol es obligatorio');
                }
                if (!this.user.name) {
                    this.setError('name', 'El campo Nombre es obligatorio');
                }
                if (!this.user.last_name) {
                    this.setError('last_name', 'El campo Apellido es obligatorio');
                }
                if (!this.user.email) {
                    this.setError('email', 'El campo Email es obligatorio');
                }


                if (this.showPasswordField) {

                    if (!this.user.password) {
                        this.setError('password', 'El campo password es obligatorio');
                    }

                    if (!this.user.password_confirmation) {
                        this.setError('password_confirmation', 'El campo confirmar password es obligatorio');
                    }

                    if (this.user.password != this.user.password_confirmation) {
                        this.setError('password_confirmation', 'Los passwords no coinciden');
                    }
                }


                return this.errors;
            },
            setError(keyModel, errorDetail) {
                this.errors.push({
                    keyModel: keyModel,
                    errorDetail: errorDetail
                });
            },
            errorExists(keyModel) {
                return this.errors.filter(err => err.keyModel === keyModel).length;
            },
            showError(keyModel) {
                return this.errors.find(err => err.keyModel === keyModel);
            },
        },           
        mounted() {
            this.listarRoles();
        },

        components: {
            MainContent,
            Select2,
        }
    }

</script>
