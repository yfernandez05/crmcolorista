<template>
    <div>

    <main-content columnClass="col-12 col-xl-11">
        <template v-slot:card-header-title>
            Registrar Atencion
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('idtipoatencion')}">
                    <label>Etiqueta General  <small class="text-danger">(*)</small></label>
                    <select2 :options="tipoatenciones" v-model="atencion.idtipoatencion" :selectValue="atencion.idtipoatencion"
                        placeholder="Seleccione Etiqueta General" keyProperty="idtipoatencion" textProperty="tipoatencion">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('idtipoatencion')"
                        v-text="showError('idtipoatencion').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('idetiquetatele')}">
                    <label>Etiqueta Telefonica <small class="text-danger">(*)</small></label>
                    <select2 :options="etiquetatelefonicas" v-model="atencion.idetiquetatele" :selectValue="atencion.idetiquetatele"
                        placeholder="Seleccione una Etiqueta Telefonica" keyProperty="idetiquetatele" textProperty="etiquetatele">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('idetiquetatele')"
                        v-text="showError('idetiquetatele').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fechaatencion')}">
                    <label>Fecha Atencion <small class="text-danger">(*)</small></label>
                    <v-date-picker v-model="atencion.fechaatencion"
                        format="DD-MM-YYYY"
                        value-type="format"
                        placeholder="Seccione"
                    >
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fechaatencion')" v-text="showError('fechaatencion').errorDetail"></small>
                </div>


                <div class="form-group col-12" :class="{'has-danger':errorExists('comentario')}">
                    <label>Comentario<small class="text-danger">(*)</small></label>
                    <textarea class="form-control"  v-model="atencion.comentario" @:keyup.enter ="doSaveData" rows="3"></textarea>
                    <small class="form-control-feedback" v-if="errorExists('comentario')" v-text="showError('comentario').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-md-4" :class="{'has-danger':errorExists('fechaagenda')}">
                    <label>Fecha programada de atención</label>
                    <v-date-picker v-model="atencion.fechaagenda"
                        type="datetime"
                        format="DD-MM-YYYY HH:mm:ss"
                        value-type="format"
                        placeholder="Seccione una fecha">
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fechaagenda')" v-text="showError('fechaagenda').errorDetail"></small>
                </div>


                <div class="form-group col-12 d-flex no-block align-items-init justify-content-init">
                    <button type="button" class="btn btn-success waves-effect waves-light" @click="saveData">
                        <i class="fa fa-save"></i>
                        Guardar
                    </button>
                </div>

            </div>
            <hr class="mt-1 mb-2">
            <div class="form-row">
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Nombre</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="prospecto.nombre"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Apellido</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="prospecto.apellido"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Fecha Nacimiento</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="prospecto.fecha_nac"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3" :class="{'has-danger':errorExists('email')}">
                <label class="mb-0">Email</label>
                <div class="input-group">
                    <input type="email" class="form-control text-truncate" v-model="prospecto.correo" placeholder="Ingrese su correo"/>
                    <ContactButtonyf :email="prospecto.correo" :showEmail="true"/>
                </div>
                <small class="form-control-feedback" v-if="errorExists('email')" v-text="showError('email').errorDetail"></small>
                </div>

                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3" :class="{'has-danger':errorExists('celular')}">
                <label class="mb-0">Celular</label>
                <div class="input-group">
                    <input type="text" class="form-control text-truncate" v-model="prospecto.telefono" placeholder="Ingrese su celular"/>
                    <ContactButtonyf :phone="prospecto.telefono" :showWhatsapp="true"/>
                </div>
                <small class="form-control-feedback" v-if="errorExists('celular')" v-text="showError('celular').errorDetail"></small>
                </div>
            </div>

            <div class="table-responsive">
                <table id="productos" class="table table-sm table-hover table-striped table-bordered mb-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cod</th>
                            <th>F. Atencion</th>
                            <th>Etiqueta General</th>
                            <th>Etiqueta Telefonica</th>
                            <th>Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="aten in atenciones" :key="aten.idatencion">
                            <td v-text="aten.idatencion"></td>
                            <td v-text="aten.fecha"></td>

                            <td><span class="badge badge-pill py-1 px-3"
                                v-bind:style="{background:aten.tipoatencion.backgroundColor, color:aten.tipoatencion.textColor}"
                                v-text="aten.tipoatencion.tipoatencion"></span>
                                <span v-text="aten.proximoinicio"></span>
                            </td>
                            <td><span class="badge badge-pill py-1 px-3"
                                v-bind:style="{background:aten.etiquetatelefonica.backgroundColor, color:aten.etiquetatelefonica.textColor}"
                                v-text="aten.etiquetatelefonica.etiquetatele"></span>
                            </td>
                            <td v-text="aten.comentario"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr class="mt-2">
        </template>
        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.atencion'}" class="btn waves-effect waves-light btn-info mr-2">
                <i class="fas fa-reply"></i> <span class="button-text">Atrás</span>
            </router-link>

            <div></div>
        </template>
    </main-content>

</div>
</template>

<script>
    import MainContent from './../../utils/MainContent';
    import VDatePicker from 'vue2-datepicker';
    import moment,{ now } from 'moment';
    import 'vue2-datepicker/locale/es';
    import Select2 from './../../utils/Select2';
    import VueNumeric from 'vue-numeric';
    import {bus} from '../../app';
    import ContactButtonyf from './../../utils/ContactButtonyf';

    export default {
        data(){
            return {
                errors:[],
                atencion:{
                    idtipoatencion:'',
                    idetiquetatele:'',
                    fechaatencion: this.formatDate(new Date(),'DD-MM-YYYY'),
                    comentario: '',
                    fechaagenda:'',

                },
                tipoatenciones:[],
                etiquetatelefonicas:[],
                prospecto:{
                  nombre:'',
                    apellido:'',
                    fecha_nac:'',
                    correo:'',
                    telefono:'',
                },
                atenciones:[],
            }
        },
        created() {
            this.prospecto.id = this.$route.params.id;

            if (isNaN(this.prospecto.id)) {
                this.backToList();
            }

           this.obtenerprospecto(this.prospecto.id);
            this.obtenerAtenciones(this.prospecto.id);
        },
        methods: {
            validateFields() {
                this.errors = [];

                if (!this.atencion.idtipoatencion) {
                    this.setError('idtipoatencion', 'El campo Etiqueta General es obligatorio');
                }

                if (!this.atencion.idetiquetatele) {
                    this.setError('idetiquetatele', 'El campo Etiqueta Telefonica es obligatorio');
                }

                if (!this.atencion.comentario) {
                    this.setError('comentario', 'El campo Comentario es obligatorio');
                }

                if (!this.atencion.fechaatencion) {
                    this.setError('fechaatencion', 'El campo Fecha Ingreso es obligatorio');
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


            listartipoAtenciones() {
                let vm = this;
                axios.get(`${appApiUrl}/tipoAtencion/select`,{
                        params: { istosave:true}
                    })
                    .then(function (response) {
                        vm.tipoatenciones = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listaretiquetaTelefonicas() {
                let vm = this;
                axios.get(`${appApiUrl}/etiquetaTelefonica/select`,{
                        params: { istosave:true}
                    })
                    .then(function (response) {
                        vm.etiquetatelefonicas = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            saveData(){
                if (this.validateFields().length > 0) {
                    return;
                }

                let atencionData = {
                    comentario: this.atencion.comentario,
                    fechaatencion: this.atencion.fechaatencion,
                    prospecto_id: this.prospecto.id,
                    idtipoatencion: this.atencion.idtipoatencion,
                    idetiquetatele: this.atencion.idetiquetatele,
                    fechaagenda:this.atencion.fechaagenda

                };

                let vm = this;
                axios.post(`${appApiUrl}/atencion`, atencionData)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;
                        if (result.status) {
                            successMessage(result.message, appName);
                            vm.obtenerAtenciones(vm.prospecto.id);
                            bus.actualizaragendas();

                        } else
                            errorMessage(result.message, appName);
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        console.log(error);
                    })
            },
            obtenerprospecto(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/prospecto/${id}/edit`)
                    .then(function (response) {
                        hidePreloader();
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún prospecto con el código ${id}`, appName);
                            vm.backToList();
                        }
                        vm.prospecto = response.data;

                        if(!vm.prospecto.isactive){
                            warningMessage('No se puede atender a un prospecto elimnado', appName);
                            vm.backToList();
                        }
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        vm.backToList();
                        console.log(error);
                    })
            },
            backToList(){
                this.$router.push({
                    name: 'spa.atencion'
                });
            },
            obtenerAtenciones(id){
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/atencion/detail/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        vm.atenciones = response.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        console.log(error);
                    })
            },



        },
        mounted(){
            this.listartipoAtenciones();
            this.listaretiquetaTelefonicas();
        },
        components: {
            MainContent,
            VDatePicker,
            Select2,
            VueNumeric,
            ContactButtonyf
        }
    }

</script>
