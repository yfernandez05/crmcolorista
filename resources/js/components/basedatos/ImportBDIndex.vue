<template>
    <div>
        <main-content columnClass="col-12">
            <template v-slot:card-header-title>
                Importacion
            </template>
            <template v-slot:card-header-actions>
                <button class="btn btn-sm btn-green waves-effect waves-light" @click="openModalImport()">
                    <i class="far fa-file-excel"></i>
                    <span class="d-none d-sm-inline-block ">
                        Importar Datos
                    </span>
                </button>
                <i class="fas fa-grip-lines-vertical text-white px-3"></i>
                <button class="btn btn-sm btn-info waves-effect waves-light" @click="descargarPlantillaCliente()">
                    <i class="fas fa-file-download"></i>
                    <span class="d-none d-sm-inline-block ">
                        Descargar Formato
                    </span>
                </button>
                <i class="fas fa-grip-lines-vertical text-white px-3"></i>
                <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarImportaciones()">
                    <i class="fas fa-search"></i>
                    <span class="d-none d-sm-inline-block">
                        Buscar
                    </span>
                </button>
                <button class="btn btn-sm btn-danger waves-effect waves-light">
                    <i class="fas fa-times"></i>
                    <span class="d-none d-sm-inline-block" @click="limpiarFiltros()">
                        Limpiar
                    </span>
                </button>   
            </template>
            <template v-slot:card-body-main>
                <div class="form-row">
                    <div class="form-group col-12 col-sm-6 col-md-4 ">
                        <label>Usuario</label>
                        <select2 :options="users" @input="buscarImportaciones()" v-model="importacion.iduser"
                            :selectValue="importacion.iduser" placeholder="Seleccione un usuario" keyProperty="id"
                            textProperty="name">
                        </select2>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4 ">
                        <label>fecha</label>
                        <v-date-picker v-model="importacion.fecha" range lang="es" format="DD-MM-YYYY" 
                            confirm :editable="false" placeholder="Seleccione un rango de fecha" @input="buscarImportaciones()">
                            <template v-slot:footer="{ emit }">
                                <button class="mx-btn" @click="selectToday(emit)">
                                    Hoy
                                </button>
                            </template>                        
                        </v-date-picker>
                    </div>
                    <!-- <div class="form-group col-12 col-sm-6 col-md-4 ">
                        <label>&nbsp;</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="estado" 
                                v-model="importacion.estado" @change="buscarImportaciones()">
                            <label class="custom-control-label" for="estado">Incluir Eliminados</label>
                        </div>
                    </div> -->
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="p-2">Codigo</th>
                                <th class="p-2">Archivo</th>
                                <th class="p-2 text-nowrap">Fech. Registro</th>
                                <th class="p-2 text-nowrap">Cant. Registros</th>
                                <th class="p-2">Usuario</th>
                                <th class="p-2">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="imp in importaciones" :key="imp.idimportacion">
                                <td v-text="imp.idimportacion"></td>
                                <td v-text="imp.nombrearchivo" class="ui-max-w-300 text-truncate"></td>
                                <td v-text="imp.fecha"></td>
                                <td v-text="imp.cantregistros"></td>
                                <td v-text="imp.user.name"></td>
                                <td>
                                    <span class="badge badge-pill py-1 px-3"
                                        :class="imp.isactive ? 'badge-success':'badge-danger'" v-text="imp.statename">
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
        <modal-import ref="modalIimport"></modal-import>
    </div>
</template>


<script>
    import MainContent from './../../utils/MainContent';
    import PaginationLinks from './../../utils/PaginationLinks';
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';
    import moment,{ now } from 'moment';
    import ModalImport from './_ModalImport';
    import Select2 from './../../utils/Select2';
    import qs from 'qs';

    export default {
        data() {
            return {
                importaciones: [],
                pagination: {},
                filters: {},
                importacion: {
                    iduser: 0,
                    fecha:'',
                    estado: false
                },
                users:[]
            }
        },
        
        methods: {
            listarImportaciones() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/import`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.importaciones = response.data.data;
                        vm.pagination = response.data;
                        delete vm.pagination.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        console.log(error);
                    });
            },
            changePerPage(event) {
                this.filters.page = event.page;
                this.filters.perpage = event.perpage;
                this.listarImportaciones();
            },
            buscarImportaciones() {
                this.filters = {};
                this.filters.page = 1;

                if (this.importacion.iduser.length)
                    this.filters.iduser = this.importacion.iduser;

                if (this.importacion.estado)
                    this.filters.estado = this.importacion.estado;    

                if(this.importacion.fecha.length == 2){                
                    if(this.importacion.fecha[0] != null)
                        this.filters.fechadesde = this.formatDate(this.importacion.fecha[0],'DD-MM-YYYY');

                    if(this.importacion.fecha[1] != null)
                        this.filters.fechahasta = this.formatDate(this.importacion.fecha[1],'DD-MM-YYYY');
                }

                this.listarImportaciones();  
            },
            limpiarFiltros(){
                this.importacion.iduser=0;
                this.importacion.fecha = '';
                this.importacion.estado=false;

                this.listarImportaciones();
            },
            openModalImport(){
                this.$nextTick(() => {
                    this.$refs.modalIimport.openModal();
                });
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            selectToday(emit){
                emit([ new Date(), new Date() ]);
            },
            listarUsers() {
                let vm = this;
                axios.get(`${appApiUrl}/user`)
                    .then(function (response) {
                        vm.users = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },

            descargarPlantillaCliente() {
                //this.addFiltersRole();
                let urlexcel = `${appApiUrl}/prospecto/descargarplantilla`;
                window.location = urlexcel;
            },

            descargarPlantillainstitucion() {
                //this.addFiltersRole();
                let urlexcel = `${appApiUrl}/institucion/descargarplantilla`;
                window.location = urlexcel;
            }

        },
        mounted() {
            this.listarImportaciones();
            this.listarUsers();
        },
        components: {
            MainContent,
            PaginationLinks,
            VDatePicker,
            ModalImport,
            Select2,
        }
    }

</script>
