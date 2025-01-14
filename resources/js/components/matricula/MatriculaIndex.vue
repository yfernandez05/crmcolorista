<template>
    <main-content>
        <template v-slot:card-header-title>
            Matricula
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarMatricula()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarMatricula()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.matricula.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>    
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label>Detalle</label>
                    <input type="text" class="form-control" v-model="matricula.detalle" @keyup.enter="buscarMatricula()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3 ">
                        <label>Fecha</label>
                        <v-date-picker v-model="matricula.fecha" range lang="es" format="DD-MM-YYYY" confirm
                            :editable="false" placeholder="Seleccione un rango de fecha" @input="buscarMatricula()">
                            <template v-slot:footer="{ emit }">
                                <button class="mx-btn" @click="selectToday(emit)">
                                    Hoy
                                </button>
                            </template>
                        </v-date-picker>
                    </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Alumno</label>
                    <select2 :options="alumnos" @input="buscarMatricula()" v-model="matricula.alumno_id"
                        :selectValue="matricula.alumno_id" placeholder="Seleccione un alumno" keyProperty="alumno_id"
                        textProperty="nombre">
                    </select2>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Carrera</label>
                    <select2 :options="carreras" @input="buscarMatricula()" v-model="matricula.carrera_id"
                        :selectValue="matricula.carrera_id" placeholder="Seleccione una carrera" keyProperty="carrera_id"
                        textProperty="nombre">
                    </select2>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Ciclo</label>
                    <select2 :options="ciclos" @input="buscarMatricula()" v-model="matricula.ciclo_id"
                        :selectValue="matricula.ciclo_id" placeholder="Seleccione un ciclo" keyProperty="ciclo_id"
                        textProperty="nombre">
                    </select2>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Periodo</label>
                    <select2 :options="periodos" @input="buscarMatricula()" v-model="matricula.periodo_id"
                        :selectValue="matricula.periodo_id" placeholder="Seleccione un periodo" keyProperty="periodo_id"
                        textProperty="nombre">
                    </select2>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Condicion</label>
                    <select2 :options="condiciones" @input="buscarMatricula()" v-model="matricula.condicion_id"
                        :selectValue="matricula.condicion_id" placeholder="Seleccione una condicion" keyProperty="condicion_id"
                        textProperty="nombre">
                    </select2>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Turno</label>
                    <select2 :options="turnos" @input="buscarMatricula()" v-model="matricula.turno_id"
                        :selectValue="matricula.turno_id" placeholder="Seleccione un turno" keyProperty="turno_id"
                        textProperty="nombre">
                    </select2>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="matricula.estado" @change="buscarMatricula()">
                        <label class="custom-control-label" for="estado">Incluir Eliminados</label>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="p-2">Acciones</th>
                            <th class="p-2">Cod</th>
                            <th class="p-2">Alumno</th>
                            <th class="p-2">Carrera</th>
                            <th class="p-2">Detalle</th>
                            <th class="p-2">Fecha</th>
                            <th class="p-2">Ciclo</th>
                            <th class="p-2">Periodo</th>
                            <th class="p-2">Condición</th>
                            <th class="p-2">Turno</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="mts in matriculas" :key="mts.id">
                            <td>
                                <row-actions :rowData="mts" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="mts.id"></td>
                            <td v-text="mts.alumno?.nombre"></td>                            
                            <td v-text="mts.carrera?.nombre"></td>                            
                            <td v-text="mts.detalle"></td>                            
                            <td v-text="mts.fecha"></td>                            
                            <td v-text="mts.ciclo?.nombre"></td>                            
                            <td v-text="mts.periodo?.nombre"></td>                            
                            <td v-text="mts.condicion?.nombre"></td>                            
                            <td v-text="mts.turno?.nombre"></td>                            
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="mts.isactive ? 'badge-success':'badge-danger'"
                                     v-text="mts.statename">
                                </span>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
            <pagination-links :pagination="pagination" @changePerPage="changePerPage">
            </pagination-links>

        </template>
    </main-content>
</template>


<script>
    import MainContent from './../../utils/MainContent';
    import PaginationLinks from './../../utils/PaginationLinks';
    import RowActions from './../../utils/RowActions';
    import Select2 from './../../utils/Select2';
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';
    import moment, {
        now,
        relativeTimeThreshold
    } from 'moment';

    export default {
        data() {
            return {
                matriculas: [],
                carreras: [],
                alumnos: [],
                ciclos: [],
                periodos: [],
                condiciones: [],
                turnos: [],
                pagination: {},
                filters: {},
                matricula: {
                    detalle: '',
                    fecha: '',
                    alumno_id: '',
                    carrera_id: '',
                    ciclo_id: '',
                    periodo_id: '',
                    condicion_id: '',
                    turno_id: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarMatricula() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/matricula`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.matriculas = response.data.data;
                        vm.pagination = response.data;
                        delete vm.pagination.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        console.log(error);
                    });
            },
            listarCarreras() {
                let vm = this;
                axios.get(`${appApiUrl}/carrera/select`)
                    .then(function (response) {
                        vm.carreras = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listarAlumnos() {
                let vm = this;
                axios.get(`${appApiUrl}/alumno/select`)
                    .then(function (response) {
                        vm.alumnos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listarCiclo() {
                let vm = this;
                axios.get(`${appApiUrl}/ciclo/select`)
                    .then(function (response) {
                        vm.ciclos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listarPeriodo() {
                let vm = this;
                axios.get(`${appApiUrl}/periodo/select`)
                    .then(function (response) {
                        vm.periodos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listarCondicion() {
                let vm = this;
                axios.get(`${appApiUrl}/condicion/select`)
                    .then(function (response) {
                        vm.condiciones = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listarTurno() {
                let vm = this;
                axios.get(`${appApiUrl}/turno/select`)
                    .then(function (response) {
                        vm.turnos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            changePerPage(event) {
                this.filters.page = event.page;
                this.filters.perpage = event.perpage;
                this.listarMatricula();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.matricula.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarMatricula(event.data);
                    break;
                }
            },
            buscarMatricula() {
                this.filters = {};
                this.filters.page = 1;

                if (this.matricula.detalle.length)
                    this.filters.detalle = this.matricula.detalle;

                if (this.matricula.fecha.length == 2) {
                    if (this.matricula.fecha[0] != null)
                        this.filters.fechadesde = this.formatDate(this.matricula.fecha[0], 'DD-MM-YYYY');

                    if (this.matricula.fecha[1] != null)
                        this.filters.fechahasta = this.formatDate(this.matricula.fecha[1], 'DD-MM-YYYY');
                }
                                
                if (this.matricula.alumno_id.length)
                    this.filters.alumno_id = this.matricula.alumno_id;
                
                if (this.matricula.carrera_id.length)
                    this.filters.carrera_id = this.matricula.carrera_id;
                
                if (this.matricula.ciclo_id.length)
                    this.filters.ciclo_id = this.matricula.ciclo_id;
                
                if (this.matricula.periodo_id.length)
                    this.filters.periodo_id = this.matricula.periodo_id;
                
                if (this.matricula.condicion_id.length)
                    this.filters.condicion_id = this.matricula.condicion_id;
                
                if (this.matricula.turno_id.length)
                    this.filters.turno_id = this.matricula.turno_id;
                
                if (this.matricula.estado)
                    this.filters.estado = this.matricula.estado;

                this.listarMatricula();  
            },
            eliminarMatricula(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar la matricula con codigo <b>${param.id}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/matricula/${param.id}`)
                                .then(function (response) {        0 
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarMatricula();
                                    } else
                                        errorMessage(result.message, appName);
                                })
                                .catch(function (error) {
                                    hidePreloader();
                                    errorMessage(appErrorMessage, appName);
                                    console.log(error);
                                });
                        }
                    });
            },
            formatDate(value, fmt = 'D MMM YYYY') {
                return (value == null) ?
                    '' :
                    moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            selectToday(emit) {
                emit([new Date(), new Date()]);
            },
            limpiarMatricula(){
                this.matricula.detalle = '';
                this.matricula.fecha = '';
                this.matricula.alumno_id = '';
                this.matricula.carrera_id = '';
                this.matricula.ciclo_id = '';
                this.matricula.periodo_id = '';
                this.matricula.condicion_id = '';
                this.matricula.turno_id = '';
                
                this.buscarMatricula();
            },
            
        },
        mounted() {
            this.listarMatricula();
            this.listarCarreras();
            this.listarAlumnos();
            this.listarCiclo();
            this.listarPeriodo();
            this.listarCondicion();
            this.listarTurno();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            Select2,
            VDatePicker
        }
    }

</script>
