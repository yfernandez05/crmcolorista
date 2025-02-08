<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label>Alumno <small class="text-danger">(*)</small></label>
                    <v-select class="select-vue-customers"
                        v-model="selectedAlumno"
                        :filterable="false"
                        :options="alumnos"
                        :searchable="true"
                        label="nombrecompleto"
                        :loading="loading"
                        @search="onSearch"
                        @input="onSelectClient"
                        placeholder="Buscar por: dni,nombre ó apellidos.">
                        <template #option="data">
                            <div class="my-1">
                                <div class="d-flex no-block text-truncate">
                                    <h5 class="font-weight-bolder mb-0"><strong>{{ data.nombre }} {{ data.apellido }}</strong></h5    >
                                </div>
                                <div class="d-flex no-block text-truncate">
                                    <span>DNI: </span><span class="font-weight-bolder pl-1 pr-3" v-text="data.dni"></span>
                                </div>
                            </div>
                        </template>
                        <template #no-options>
                            <span>No se encontraron opciones</span>
                        </template>
                    </v-select>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('dni')}">
                    <label>DNI <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="contrato.dni" @keyup.enter ="doSaveData" disabled/>
                    <small class="form-control-feedback" v-if="errorExists('dni')" v-text="showError('dni').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('domicilio')}">
                    <label>Domicilio <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="contrato.domicilio" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('domicilio')" v-text="showError('domicilio').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fecha_inscripcion')}">
                    <label>Fecha Inscripción <small class="text-danger">(*)</small></label>
                    <v-date-picker v-model="contrato.fecha_inscripcion"
                        format="DD-MM-YYYY"
                        value-type="format"
                        placeholder="Seccione una fecha"
                    >
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fecha_inscripcion')" v-text="showError('fecha_inscripcion').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('idcuenta')}">
                    <label>Matricula <small class="text-danger">(*)</small></label>
                    <select2 
                    :options="selectedAlumno?.matriculas || []" 
                    v-model="contrato.matricula_id" 
                    placeholder="Seleccione una matrícula"
                    keyProperty="id" 
                    textProperty="detalle">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('idcuenta')"
                        v-text="showError('idcuenta').errorDetail"></small>
                </div>

                <div v-if="detalleMatricula" class="form-group col-12 col-sm-6 col-md-4">
                    <label>Carrera <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" :value="detalleMatricula.carrera?.nombre || 'N/A'" disabled />
                </div>
                <div v-if="detalleMatricula" class="form-group col-12 col-sm-6 col-md-4">
                    <label>Modulo <small class="text-danger">(*)</small></label>
                    <input 
                    type="text" class="form-control" :value=" detalleMatricula.ciclo?.nombre || 'N/A'" disabled />
                </div>
                <div v-if="detalleMatricula" class="form-group col-12 col-sm-6 col-md-4">
                    <label>Fecha de Matricula <small class="text-danger">(*)</small></label>
                    <input 
                    type="text" class="form-control" :value=" detalleMatricula.fecha || 'N/A'" disabled />
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('duracion_modulo')}">
                    <label>Duracion Modulos <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="contrato.duracion_modulo" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('duracion_modulo')" v-text="showError('duracion_modulo').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('cantidadveces')}">
                    <label>Cantidad Veces <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="contrato.cantidadveces" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('cantidadveces')" v-text="showError('cantidadveces').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('duracionhoras')}">
                    <label>Duración Horas <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="contrato.duracionhoras" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('duracionhoras')" v-text="showError('duracionhoras').errorDetail"></small>
                </div>
                <div v-if="detalleMatricula" class="form-group col-12">
                    <label>Detalles Asociados</label>
                    <table class="table table-sm table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Preciomes</th>
                                <th>Fecha de Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(detalle, index) in detalleMatricula.detalles" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ detalle.nombre || 'N/A' }}</td>
                                <td>{{ detalle.preciomes || 'N/A' }}</td>
                                <td>{{ detalle.fechapago || 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('monto_promocional')}">
                    <label>Monto Promocional <small class="text-danger">(*)</small></label>
                    <input type="number" class="form-control" v-model="contrato.monto_promocional" min="0" step="0.01" placeholder="0.00" required />                    
                     <small class="form-control-feedback" v-if="errorExists('monto_promocional')" v-text="showError('monto_promocional').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('monto_preabonado')}">
                    <label>Monto Pre-Abonado <small class="text-danger">(*)</small></label>
                    <input type="number" class="form-control" min="0" step="0.01" v-model="contrato.monto_preabonado" />
                    <small class="form-control-feedback" v-if="errorExists('monto_preabonado')" v-text="showError('monto_preabonado').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('importe_restante')}">
                    <label>Importe Restante <small class="text-danger">(*)</small></label>
                    <input type="number" class="form-control" min="0" step="0.01" v-model="contrato.importe_restante" />
                    <small class="form-control-feedback" v-if="errorExists('importe_restante')" v-text="showError('importe_restante').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fecha_limitepago')}">
                    <label>Fecha Limite Pago <small class="text-danger">(*)</small></label>
                    <v-date-picker v-model="contrato.fecha_limitepago"
                        format="DD-MM-YYYY"
                        value-type="format"
                        placeholder="Seccione una fecha"
                    >
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fecha_limitepago')" v-text="showError('fecha_limitepago').errorDetail"></small>
                </div>

                
            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.contrato'}" class="btn waves-effect waves-light btn-info mr-2">
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
    import moment,{ now } from 'moment';
    import vSelect from 'vue-select';
    import Select2 from './../../utils/Select2';
    import debounce from 'lodash/debounce';

    export default {
        props: {
            cardTitle: {
                default: 'Condicion'
            },
            contrato: {
                type: Object,
                default() {
                    return {
                        dni: '',
                        domicilio: '',
                        alumno_id:'',
                        fecha_inscripcion: '',
                        matricula_id: '',
                        monto_promocional: '',
                        monto_preabonado: '',
                        importe_restante: '',
                        fecha_limitepago: '',
                        duracion_modulo: '',
                        cantidadveces: '',
                        duracionhoras: '',
                    }
                }
            },
        },
        data(){
            return {
                errors:[],
                selectedAlumno: null,
                alumnos: [],
                loading: false,
                detalleMatricula: null 
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let contratoData = {
                    alumno_id: this.contrato.alumno_id,
                    matricula_id: this.contrato.matricula_id,
                    domicilio: this.contrato.domicilio,
                    fecha_inscripcion: this.contrato.fecha_inscripcion,
                    monto_promocional: this.contrato.monto_promocional,
                    monto_preabonado: this.contrato.monto_preabonado,
                    importe_restante: this.contrato.importe_restante,
                    fecha_limitepago: this.contrato.fecha_limitepago,
                    duracion_modulo: this.contrato.duracion_modulo,
                    cantidadveces: this.contrato.cantidadveces,
                    duracionhoras: this.contrato.duracionhoras,
                    detalles: this.detalleMatricula.detalles
                }

                this.$emit('saveData', contratoData);
            },

            validateFields() {
                this.errors = [];

                if (!this.contrato.alumno_id) {
                    this.setError('alumno_id', 'El campo Alumno es obligatorio');
                }
                if (!this.contrato.dni) {
                    this.setError('dni', 'El campo DNI es obligatorio');
                }
                if (!this.contrato.domicilio) {
                    this.setError('domicilio', 'El campo Domicilio es obligatorio');
                }
                if (!this.contrato.fecha_inscripcion) {
                    this.setError('fecha_inscripcion', 'El campo Fecha Inscripción es obligatorio');
                }
                if (!this.contrato.matricula_id) {
                    this.setError('matricula_id', 'El campo Matricula es obligatorio');
                }
                if (!this.contrato.monto_promocional) {
                    this.setError('monto_promocional', 'El campo monto promocional es obligatorio');
                }
                if (!this.contrato.monto_preabonado) {
                    this.setError('monto_preabonado', 'El campo monto pre abonado es obligatorio');
                }
                if (!this.contrato.importe_restante) {
                    this.setError('importe_restante', 'El campo importe restante es obligatorio');
                }
                if (!this.contrato.fecha_limitepago) {
                    this.setError('fecha_limitepago', 'El campo fecha limite es obligatorio');
                }
                if (!this.contrato.duracion_modulo) {
                    this.setError('duracion_modulo', 'El campo duracion limite es obligatorio');
                }
                if (!this.contrato.cantidadveces) {
                    this.setError('cantidadveces', 'El campo cantidad veces limite es obligatorio');
                }
                if (!this.contrato.duracionhoras) {
                    this.setError('duracionhoras', 'El campo duracion horas es obligatorio');
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

            onSearch: debounce(function (search) {
                if (search.length < 2) {
                    return;
                }
                this.loading = true;
                this.fetchClients(search);
            }, 500),

            fetchClients(search) {
                axios.get(`${appApiUrl}/contrato/selectsearch`, { params: { search } })
                    .then(response => {
                        this.alumnos = response.data;
                        this.loading = false;
                    })
                    .catch(error => {
                    console.error(error);
                    this.loading = false;
                });
            },

            onSelectClient(client) {
                this.selectedAlumno = client;
                this.contrato.alumno_id = client.id;
                this.contrato.dni = client.dni;
                this.contrato.domicilio = client.direccion;
                this.contrato.matricula_id = null
            },

            getDetailMatricula(){

            },

        },
        mounted(){
            this.contrato.fecha_inscripcion = this.formatDate(new Date(),'DD-MM-YYYY');
        },
        watch: {
            'contrato.matricula_id'(newMatriculaId) {
                if (newMatriculaId) {
                    console.log(newMatriculaId,this.selectedAlumno?.matriculas);
                    this.detalleMatricula = this.selectedAlumno?.matriculas.find(
                        (matricula) => matricula.id == newMatriculaId
                    );
                    console.log('Detalles de la matrícula seleccionada:', this.detalleMatricula);
                } else {
                    this.detalleMatricula = null;
                }
            }
        },
        components: {
            MainContent,
            vSelect,
            VDatePicker,
            Select2
        }
    }

</script>
