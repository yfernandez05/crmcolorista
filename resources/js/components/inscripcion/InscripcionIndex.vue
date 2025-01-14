<template>
    <main-content>
        <template v-slot:card-header-title>
            Inscripcion
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarInscripcion()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarInscripcion()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.inscripcion.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
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
                    <input type="text" class="form-control" v-model="inscripcion.costo" @keyup.enter="buscarInscripcion()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3 ">
                        <label>Fecha</label>
                        <v-date-picker v-model="inscripcion.fecha" range lang="es" format="DD-MM-YYYY" confirm
                            :editable="false" placeholder="Seleccione un rango de fecha" @input="buscarInscripcion()">
                            <template v-slot:footer="{ emit }">
                                <button class="mx-btn" @click="selectToday(emit)">
                                    Hoy
                                </button>
                            </template>
                        </v-date-picker>
                    </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Alumno</label>
                    <select2 :options="alumnos" @input="buscarInscripcion()" v-model="inscripcion.alumno_id"
                        :selectValue="inscripcion.alumno_id" placeholder="Seleccione un alumno" keyProperty="alumno_id"
                        textProperty="nombre">
                    </select2>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Carrera</label>
                    <select2 :options="carreras" @input="buscarInscripcion()" v-model="inscripcion.carrera_id"
                        :selectValue="inscripcion.carrera_id" placeholder="Seleccione una carrera" keyProperty="carrera_id"
                        textProperty="nombre">
                    </select2>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Usuario</label>
                    <select2 :options="users" @input="buscarInscripcion()" v-model="inscripcion.user_id"
                        :selectValue="inscripcion.user_id" placeholder="Seleccione un usuario" keyProperty="user_id"
                        textProperty="name">
                    </select2>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="inscripcion.estado" @change="buscarInscripcion()">
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
                            <th class="p-2">Detalle</th>
                            <th class="p-2">Fecha</th>
                            <th class="p-2">Alumno</th>
                            <th class="p-2">Carrera</th>
                            <th class="p-2">Usuario</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="isc in inscripciones" :key="isc.id">
                            <td>
                                <row-actions :rowData="isc" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="isc.id"></td>
                            <td v-text="isc.detalle"></td>                            
                            <td v-text="isc.fecha"></td>                            
                            <td v-text="isc.alumno?.nombre"></td>                            
                            <td v-text="isc.carrera?.nombre"></td>                            
                            <td v-text="isc.user?.name"></td>                            
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="isc.isactive ? 'badge-success':'badge-danger'"
                                     v-text="isc.statename">
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
                inscripciones: [],
                carreras: [],
                alumnos: [],
                users: [],
                pagination: {},
                filters: {},
                inscripcion: {
                    detalle: '',
                    fecha: '',
                    alumno_id: '',
                    carrera_id: '',
                    user_id: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarInscripcion() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/inscripcion`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.inscripciones = response.data.data;
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
            listarUsuarios() {
                let vm = this;
                axios.get(`${appApiUrl}/user/select`)
                    .then(function (response) {
                        vm.users = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            changePerPage(event) {
                this.filters.page = event.page;
                this.filters.perpage = event.perpage;
                this.listarInscripcion();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.inscripcion.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarInscripcion(event.data);
                    break;
                }
            },
            buscarInscripcion() {
                this.filters = {};
                this.filters.page = 1;

                if (this.inscripcion.detalle.length)
                    this.filters.detalle = this.inscripcion.detalle;

                if (this.inscripcion.fecha.length == 2) {
                    if (this.inscripcion.fecha[0] != null)
                        this.filters.fechadesde = this.formatDate(this.inscripcion.fecha[0], 'DD-MM-YYYY');

                    if (this.inscripcion.fecha[1] != null)
                        this.filters.fechahasta = this.formatDate(this.inscripcion.fecha[1], 'DD-MM-YYYY');
                }
                                
                if (this.inscripcion.alumno_id.length)
                    this.filters.alumno_id = this.inscripcion.alumno_id;
                
                if (this.inscripcion.carrera_id.length)
                    this.filters.carrera_id = this.inscripcion.carrera_id;
                
                if (this.inscripcion.user_id.length)
                    this.filters.user_id = this.inscripcion.user_id;
                
                if (this.inscripcion.estado)
                    this.filters.estado = this.inscripcion.estado;

                this.listarInscripcion();  
            },
            eliminarInscripcion(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar la inscripcion con codigo <b>${param.id}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/inscripcion/${param.id}`)
                                .then(function (response) {        0 
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarInscripcion();
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
            limpiarInscripcion(){
                this.inscripcion.detalle = '';
                this.inscripcion.fecha = '';
                this.inscripcion.alumno_id = '';
                this.inscripcion.carrera_id = '';
                this.inscripcion.user_id = '';
                this.buscarInscripcion();
            },
            
        },
        mounted() {
            this.listarInscripcion();
            this.listarCarreras();
            this.listarAlumnos();
            this.listarUsuarios();
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
