<template>
    <div>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombre')}">
                    <label>Nombre <small class="text-danger">(*)</small></label>
                    <div class="input-group">
                        <input type="text" class="form-control" v-model="alumno.nombre" @keyup.enter="doSaveData"/>
                        <div class="input-group-append">
                            <button type="button" title="Buscar Prospecto" @click="buscarprospecto"
                                class="btn btn-success waves-effect waves-light">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

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
                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label>Correo</label>
                    <input type="text" class="form-control" v-model="alumno.correo" @keyup.enter ="doSaveData"/>

                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('dni')}">
                    <label>DNI <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="alumno.dni" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('dni')" v-text="showError('dni').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('direccion')}">
                    <label>Dirección </label>
                    <input type="text" class="form-control" v-model="alumno.direccion" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('direccion')" v-text="showError('direccion').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('celular')}">
                    <label>Celular <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="alumno.celular" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('celular')" v-text="showError('celular').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('contactoemergencia')}">
                    <label>Contacto de emergencia (Teléfono)</label>
                    <input type="text" class="form-control" v-model="alumno.contactoemergencia" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('contactoemergencia')" v-text="showError('contactoemergencia').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombreemergencia')}">
                    <label>Contacto de emergencia (Nombre)</label>
                    <input type="text" class="form-control" v-model="alumno.nombreemergencia" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('nombreemergencia')" v-text="showError('nombreemergencia').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('empresa')}">
                    <label>Empresa </label>
                    <input type="text" class="form-control" v-model="alumno.empresa" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('empresa')" v-text="showError('empresa').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('cargodesempenia')}">
                    <label>Cargo que desempeña </label>
                    <input type="text" class="form-control" v-model="alumno.cargodesempenia" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('cargodesempenia')" v-text="showError('cargodesempenia').errorDetail"></small>
                </div>
                <!-- <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('colegiosegundario')}">
                    <label>Colegio segundario </label>
                    <input type="text" class="form-control" v-model="alumno.colegiosegundario" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('colegiosegundario')" v-text="showError('colegiosegundario').errorDetail"></small>
                </div> -->

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('trabajo')}"  style=" padding-left: 36px;">
                    <label>¿Actualmente se encuentra trabajando?</label>
                    <div>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" v-model="alumno.trabajo" value="Si">
                            Si
                        </label>
                    </div>
                    <div>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" v-model="alumno.trabajo" value="No">
                            NO
                        </label>
                    </div>
                    <small class="form-control-feedback" v-if="errorExists('trabajo')" v-text="showError('trabajo').errorDetail"></small>
                </div>               

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('edad')}">
                    <label>Edad</label>
                    <input type="text" class="form-control" v-model="alumno.edad" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('edad')" v-text="showError('edad').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('sexo')}" style=" padding-left: 36px;">
                    <label>Sexo</label>
                    <div>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" v-model="alumno.sexo" value="Femenino">
                            Femenino
                        </label>
                    </div>
                    <div>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" v-model="alumno.sexo" value="Masculino">
                            Masculino
                        </label>
                    </div>
                    <small class="form-control-feedback" v-if="errorExists('sexo')" v-text="showError('sexo').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" >
                    <label class="mb-1">Pais</label>
                        <select2 :options="paises" v-model="alumno.pais"
                                :selectValue="alumno.pais" placeholder="Seleccione un Pais"
                                keyProperty="pais" textProperty="pais">
                        </select2>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" >
                    <label class="mb-1">Departamento Perú</label>
                    <select2 :options="departamentos" @input="listarProvincias(selectedDepartamento)" v-model="selectedDepartamento"
                        :selectValue="selectedDepartamento" placeholder="Seleccione un Departamento"
                        keyProperty="pkubigeo" textProperty="nombreubigeo">
                    </select2>

                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" >
                    <label class="mb-1">Provincia Perú</label>
                    <select2 :options="provincias" @input="listarDistritos(selectedProvincia)" v-model="selectedProvincia"
                        :selectValue="selectedProvincia" placeholder="Seleccione una Provincia"
                        keyProperty="pkubigeo" textProperty="nombreubigeo">
                    </select2>

                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" >
                    <label class="mb-1">Distrito Perú</label>
                    <select2 :options="distritos" v-model="alumno.coddistrito"
                        :selectValue="alumno.coddistrito" placeholder="Seleccione un Distrito"
                        keyProperty="pkubigeo" textProperty="nombreubigeo">
                    </select2>

                </div>                

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('redfacebook')}">
                    <label>Facebook</label>
                    <input type="text" class="form-control" v-model="alumno.redfacebook" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('redfacebook')" v-text="showError('redfacebook').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('redinstagram')}">
                    <label> Instagram </label>
                    <input type="text" class="form-control" v-model="alumno.redinstagram" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('redinstagram')" v-text="showError('redinstagram').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('redtiktok')}">
                    <label>Tiktok</label>
                    <input type="text" class="form-control" v-model="alumno.redtiktok" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('redtiktok')" v-text="showError('redtiktok').errorDetail"></small>
                </div>

            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.alumno'}" class="btn waves-effect waves-light btn-info mr-2">
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

    <ModalProspecto ref="ModalProspecto" :showModal="showModal" @closeModal="showModal = false" @prospectoSeleccionado="setProspecto"></ModalProspecto>

    </div>
</template>

<script>
    import MainContent from './../../utils/MainContent';
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';
    import _ from 'lodash';
    import vSelect from 'vue-select';
    import ModalProspecto from './_ModalProspecto';
    import moment,{ now } from 'moment';
    import Select2 from './../../utils/Select2';

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
                        dni: '',
                        direccion: '',
                        distrito: '',
                        celular: '',
                        empresa: '',
                        cargodesempenia: '',
                       // colegiosegundario: '',
                        trabajo: '',
                        contactoemergencia: '',
                        edad: '',
                        sexo: '',
                        nombreemergencia: '',
                        redfacebook: '',
                        redinstagram: '',
                        redtiktok: '',
                        coddistrito: '',

                    }
                }
            },

        },
        data(){
            return {
                errors:[],
                prospectos: [],
                showModal: false,
                prospectoSeleccionado: {
                    id: 0,
                    nombre: '',
                },
                departamentos: [],
                provincias: [],
                distritos: [],
                selectedDepartamento: null,
                selectedProvincia: null,
                paises: [],
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
                    dni: this.alumno.dni,
                    direccion: this.alumno.direccion,
                    distrito: this.alumno.distrito,
                    celular: this.alumno.celular,
                    empresa: this.alumno.empresa,
                    cargodesempenia: this.alumno.cargodesempenia,
                    //colegiosegundario: this.alumno.colegiosegundario,
                    trabajo: this.alumno.trabajo,
                    contactoemergencia: this.alumno.contactoemergencia,
                    edad: this.alumno.edad,
                    sexo: this.alumno.sexo,
                    nombreemergencia: this.alumno.nombreemergencia,
                    redfacebook: this.alumno.redfacebook,
                    redinstagram: this.alumno.redinstagram,
                    redtiktok: this.alumno.redtiktok,
                    //coddistrito: this.alumno.coddistrito,
                    coddepartamento: this.selectedDepartamento,
                    codprovincia: this.selectedProvincia,
                    coddistrito: this.alumno.coddistrito,
                    pais: this.alumno.pais,

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
                if (!this.alumno.dni) {
                    this.setError('dni', 'El campo DNI es obligatorio');
                }
                if (!this.alumno.celular) {
                    this.setError('celular', 'El campo celular es obligatorio');
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

            buscarprospecto(event) {
                this.showModal = true;
                this.$nextTick(() => {
                    this.$refs.ModalProspecto.showDetail(event);
                });

            },
            setProspecto(prospecto) {
                this.alumno.nombre = prospecto.nombre;
                this.alumno.apellido = prospecto.apellido;
                this.alumno.correo = prospecto.correo;
                this.alumno.celular = prospecto.telefono;
                this.alumno.fecha_nac = this.formatDate(prospecto.fecha_nac, 'DD-MM-YYYY');

            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            listarDepartamentos() {
            let vm = this;
            axios.get(`${appApiUrl}/ubigeo/departamentos`)
                .then(function (response) {
                    vm.departamentos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarProvincias(departamentoId) {
                let vm = this;
                axios.get(`${appApiUrl}/ubigeo/provincias`, { params: { departamento_id: departamentoId } })
                    .then(function (response) {
                        vm.provincias = response.data;
                        vm.distritos = []; // Clear distritos when provincia changes
                        // Forzar la actualización del valor seleccionado
                        vm.$nextTick(() => {
                            if (vm.alumno.codprovincia) {
                                vm.selectedProvincia = vm.alumno.codprovincia;
                            }
                        });
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            listarDistritos(provinciaId) {
                let vm = this;
                axios.get(`${appApiUrl}/ubigeo/distritos`, { params: { provincia_id: provinciaId } })
                    .then(function (response) {
                        vm.distritos = response.data;
                        // Forzar la actualización del valor seleccionado
                        vm.$nextTick(() => {
                            if (vm.alumno.coddistrito) {
                                vm.selectedDistrito = vm.alumno.coddistrito;
                            }
                        });
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            listarPais(){
                let vm = this;
                axios
                .get(`${appApiUrl}/alumno/paisalumno`)
                .then(function (response) {
                vm.paises = response.data;
                })
                .catch(function (error) {
                console.log(error);
                });
            },
        },
        mounted() {
            this.listarDepartamentos();
            this.listarPais();
        },
        watch: {
            selectedDepartamento(newVal) {
                if (newVal) {
                    this.listarProvincias(newVal);
                }
            },
            selectedProvincia(newVal) {
                if (newVal) {
                    this.listarDistritos(newVal);
                }
            },
            alumno: {
                handler(newVal) {
                    if (newVal.coddepartamento) {
                        this.selectedDepartamento = newVal.coddepartamento;
                    }
                    if (newVal.codprovincia) {
                        this.selectedProvincia = newVal.codprovincia;
                    }
                    if (newVal.coddistrito) {
                        this.selectedDistrito = newVal.coddistrito;
                    }
                },
                immediate: true,
                deep: true
            }
        },
        components: {
            MainContent,
            VDatePicker,
            vSelect,
            ModalProspecto,
            Select2
        }
    }

</script>
