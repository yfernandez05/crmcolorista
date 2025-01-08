<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombres')}">
                    <label>Nombres <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="cliente.nombres" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('nombres')" v-text="showError('nombres').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('apellidopaterno')}">
                    <label>Apellido Paterno <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="cliente.apellidopaterno" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('apellidopaterno')" v-text="showError('apellidopaterno').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('apellidomaterno')}">
                    <label>Apellido Materno<small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="cliente.apellidomaterno" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('apellidomaterno')" v-text="showError('apellidomaterno').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('email')}">
                    <label>Correo <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="cliente.email" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('email')" v-text="showError('email').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('dni')}">
                    <label>Dni</label>
                    <input type="text" class="form-control" v-model="cliente.dni" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('dni')" v-text="showError('dni').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('telefono')}">
                    <label>Telefono <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="cliente.telefono" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('telefono')" v-text="showError('telefono').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('edad')}">
                    <label>Edad </label>
                    <input type="text" class="form-control" v-model="cliente.edad" @:keyup.enter ="doSaveData" />
                    <small class="form-control-feedback" v-if="errorExists('edad')" v-text="showError('edad').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('idcampania')}">
                    <label class="mb-1">Campaña <small class="text-danger">(*)</small></label>
                    <select2 :options="campania" @input="buscarEvento()" v-model="cliente.idcampania"
                        :selectValue="eventoSeleccionado.idcampania" placeholder="Seleccione una campaña"
                        keyProperty="idcampania" textProperty="nombrecampania">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('idcampania')" v-text="showError('idcampania').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('idevento')}">
                    <label class="mb-1">Evento <small class="text-danger">(*)</small></label>
                    <select2 :options="dataevento" v-model="cliente.idevento"
                        :selectValue="cliente.idevento" placeholder="Seleccione un Evento"
                        keyProperty="idevento" textProperty="nombreevento">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('idevento')" v-text="showError('idevento').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('coddistrito')}">
                    <label class="mb-1">Distrito </label>
                    <select2 :options="distrito"  v-model="cliente.coddistrito"
                            :selectValue="cliente.coddistrito" placeholder="Seleccione una Distrito"
                            keyProperty="coddistrito" textProperty="distrito">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('coddistrito')" v-text="showError('coddistrito').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('procedencia')}">
                    <label class="mb-1">Procedencia <small class="text-danger">(*)</small></label>
                    <select2 :options="procedenciaAdsUtm" v-model="cliente.procedencia"
                    :selectValue="cliente.procedencia" placeholder="Seleccione una Procedencia ADS"
                    textProperty="procedencia">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('procedencia')" v-text="showError('procedencia').errorDetail"></small>
                </div>
            </div>
            <hr class="mt-2">

            <!-- Card-Conten Procedencia-->
            <card-group v-if="cliente.procedencia == 'Pauta'">
                <template v-slot:title-card-header>
                    <b>Procedencia ADS - {{cliente.procedencia}}</b>
                </template>
                <template v-slot:form-card-body>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>UTM</label>
                        <input type="text" class="form-control" v-model="cliente.utm">
                        <small class="form-control-feedback" ></small>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Contenido de Campaña</label>
                        <input type="text" class="form-control" v-model="cliente.campaign_content">
                        <small class="form-control-feedback" ></small>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Medio de Campaña</label>
                        <input type="text" class="form-control" v-model="cliente.campaign_medium">
                        <small class="form-control-feedback" ></small>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Nombre de Campaña</label>
                        <input type="text" class="form-control" v-model="cliente.campaign_name">
                        <small class="form-control-feedback" ></small>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Fuente de Campaña</label>
                        <input type="text" class="form-control" v-model="cliente.campaign_source">
                        <small class="form-control-feedback" ></small>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Teminos Campaña</label>
                        <input type="text" class="form-control" v-model="cliente.campaign_term">
                        <small class="form-control-feedback" ></small>
                    </div>
                </template>
            </card-group> 
            
            <!-- Card-Conten Academicos-->
            <card-group>
                <template v-slot:title-card-header>
                    <b>Datos Academicos</b>
                </template>
                <template v-slot:form-card-body>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Grado</label>
                        <input type="text" class="form-control" v-model="cliente.grado">
                        <small class="form-control-feedback" ></small>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Colegio</label>
                        <input type="text" class="form-control" v-model="cliente.colegio">
                        <small class="form-control-feedback" ></small>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Carrera</label>
                        <input type="text" class="form-control" v-model="cliente.carrera">
                        <small class="form-control-feedback" ></small>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Año de Egreso</label>
                        <input type="text" class="form-control" v-model="cliente.anioegreso">                        
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Tipo Participante</label>
                        <input type="text" class="form-control" v-model="cliente.tipoparticipante">
                        <small class="form-control-feedback" ></small>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label>Origen</label>
                        <input type="text" class="form-control" v-model="cliente.origen">
                        <small class="form-control-feedback" ></small>
                    </div>
                </template>
            </card-group>  

        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.administrarcliente'}" class="btn waves-effect waves-light btn-info mr-2">
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
    import VueNumeric from 'vue-numeric';
    import VDatePicker from 'vue2-datepicker';
    import Select2 from './../../utils/Select2';
    // import accounting from 'accounting';
    import moment,{ now } from 'moment';
    import 'vue2-datepicker/locale/es';
    import CardGroup from './../../utils/CardGroup';

    export default {
        props: {
            cardTitle: {
                default: 'cliente'
            },
            cliente: {
                type: Object,
                default() {
                    return {
                        nombres: '',
                        apellidopaterno: '',
                        apellidomaterno: '',
                        dni: '',
                        telefono: '',
                        email: '',
                        edad:'',
                        carrera: '',
                        colegio:'',
                        idcampania: 0,
                        idevento: 0,
                        coddistrito: '',
                        anioegreso: 0,
                        procedencia: '',
                        /* evento:{
                            idcampania: '',
                        }, */
                        utm: '',
                        campaign_content: '',
                        campaign_medium: '',
                        campaign_name: '',
                        campaign_source: '',
                        campaign_term: '',
                        grado: '',
                        tipoparticipante: '',
                        origen: '',
                    }
                }
            },
            procedenciaAdsUtm:[],
            eventos:[],
            distrito:[],
            eventoSeleccionado:[],
            campania:[],
        },
        data(){
            return {
                errors:[],
                dataevento: this.eventos,
                //eventos:[],
                //eventosData: this.eventos,
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                if(this.cliente.procedencia!= 'Pauta'){
                    this.resetSelectUtmProcedencia()
                }

                let anioegreso;
                if(!this.cliente.anioegreso){
                    anioegreso = 0;
                }else{
                    anioegreso = this.cliente.anioegreso;
                }
                let edad;
                if(!this.cliente.edad ){
                    edad = 0;
                }else{
                    edad = this.cliente.edad;
                }

                let clienteData = {
                    nombres: this.cliente.nombres,
                    apellidopaterno: this.cliente.apellidopaterno,
                    apellidomaterno: this.cliente.apellidomaterno,
                    email: this.cliente.email,
                    edad: edad,
                    //idcampania: this.cliente.evento.idcampania,
                    idevento: this.cliente.idevento,
                    dni: this.cliente.dni,
                    telefono: this.cliente.telefono,
                    carrera: this.cliente.carrera,
                    colegio: this.cliente.colegio,
                    anioegreso: anioegreso,
                    procedencia: this.cliente.procedencia,
                    coddistrito: this.cliente.coddistrito,
                    utm: this.cliente.utm,
                    campaign_content: this.cliente.campaign_content,
                    campaign_medium: this.cliente.campaign_medium,
                    campaign_name: this.cliente.campaign_name,
                    campaign_source: this.cliente.campaign_source,
                    campaign_term: this.cliente.campaign_term,
                    grado: this.cliente.grado,
                    tipoparticipante: this.cliente.tipoparticipante,
                    origen: this.cliente.origen,
                }

                //console.log(clienteData);
                this.$emit('saveData', clienteData);
            },

            //limpiar el select:
            resetSelectUtmProcedencia(){                
                this.cliente.utm ='',
                this.cliente.campaign_medium ='',
                this.cliente.campaign_content ='',
                this.cliente.campaign_name ='',
                this.cliente.campaign_source ='',
                this.cliente.campaign_term ='';
                //console.log(this.utm);
            },

            validateFields() {
                this.errors = [];

                if (!this.cliente.nombres) {
                    this.setError('nombres', 'El campo nombres es obligatorio');
                }
                if (!this.cliente.apellidopaterno) {
                    this.setError('apellidopaterno', 'El campo apellido paterno es obligatorio');
                }
                if (!this.cliente.apellidomaterno) {
                    this.setError('apellidomaterno', 'El campo apellido materno es obligatorio');
                }
                if (!this.cliente.telefono) {
                    this.setError('telefono', 'El campo telefono es obligatorio');
                }
                if (!this.cliente.email) {
                    this.setError('email', 'El campo Correo es obligatorio');
                }
                if (!this.cliente.evento.idcampania) {
                    this.setError('idcampania', 'El Campaña correo es obligatorio');
                }
                if (!this.cliente.procedencia) {
                    this.setError('procedencia', 'El Campaña evento es obligatorio');
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

            buscarEvento(){               
                this.cliente.idevento = 0; 
                this.dataevento = [];
                let vm = this;
                console.log(vm.cliente.nombres);
                axios.get(`${appApiUrl}/evento/getevento`, {params: {idcampania: this.cliente.idcampania}})
                .then(function (response) {
                    if (response.data == null || response.data == '') {
                        warningMessage(`Esta campaña no tiene ningun evento`, appName);
                    }
                    vm.dataevento = response.data;
                })
                .catch(function (error) {
                    errorMessage(appErrorMessage, appName);
                    console.log(error);
                })
            },
           
        },
        created(){
            console.log(this.clienteId);
            //this.buscarEvento();
            /* let vm = this;
            if(vm.cliente.coddistrito.length == null ){
                vm.cliente.coddistrito = '1';
            } */
        },
        components: {
            MainContent,
            VueNumeric,
            Select2,
            VDatePicker,
            CardGroup
        }
    }

</script>
