<template>
    <div>
        <main-content colunmClass="col-12 col-md-11 col-lg-9">
            <template v-slot:card-header-title>
                Atenciones
            </template>
            <template v-slot:card-header-actions>
                <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarprospecto()">
                    <i class="fas fa-search"></i>
                    <span class="d-none d-sm-inline-block">
                        Buscar
                    </span>
                </button>
                <button class="btn btn-sm btn-danger waves-effect waves-light" @click="limpiarFiltros()">
                    <i class="fas fa-times"></i>
                    <span class="d-none d-sm-inline-block">
                        Limpiar
                    </span>
                </button>
            </template>

            <template v-slot:card-body-main>
                <div class="form-row mb-2">
                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Nombre</label>
                        <input type="text" class="form-control" v-model="prospecto.nombre" @keyup.enter="buscarprospecto" />
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Apellido</label>
                        <input type="text" class="form-control" v-model="prospecto.apellido" @keyup.enter="buscarprospecto" />
                    </div>
                    <div class="form-group
                        col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Correo</label>
                        <input type="text" class="form-control" v-model="prospecto.correo" @keyup.enter="buscarprospecto" />
                    </div>
                    <div class="form-group
                        col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Telefono</label>
                        <input type="text" class="form-control" v-model="prospecto.telefono" @keyup.enter="buscarprospecto" />
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="p-2">Accion</th>
                                <th class="p-2 text-nowrap">Etiqueta General</th>
                                <th class="p-2 text-nowrap">Etiqueta Telefonica</th>
                                <th class="p-2 text-nowrap">F. Atencion</th>
                                <th class="p-2">Nombres</th>
                                <th class="p-2">Correo</th>
                                <th class="p-2">Telefono</th>
                                <th class="p-2">Comentario</th>
                                <th class="p-2">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="pros in prospectos" :key="pros.id">
                                <td>
                                    <row-actions :rowData="pros" @rowItemActions="rowItemActions" :activeEdit="false" :activeDelete="false" :activeShow="true">
                                        <button type="button" title="Atender" @click="agregarAtencion(pros)"
                                            class="btn btn-sm btn-outline-primary waves-effect waves-light border-0 mr-1">
                                            <i class="fas fa-user-edit fa-lg"></i>
                                        </button>
                                    </row-actions>
                                </td>

                                <td><span class="badge badge-pill py-1 px-3"
                                v-bind:style="{background: pros.ultimaatencion.tipoatencion.backgroundColor, color:pros.ultimaatencion.tipoatencion.textColor}"
                                v-text="pros.ultimaatencion.tipoatencion.tipoatencion"></span></td>
                                <td><span class="badge badge-pill py-1 px-3"
                                v-bind:style="{background: pros.ultimaatencion.etiquetatelefonica.backgroundColor, color:pros.ultimaatencion.etiquetatelefonica.textColor}"
                                v-text="pros.ultimaatencion.etiquetatelefonica.etiquetatele"></span>
                                </td>
                                <td v-text="pros.ultimaatencion.fecha" class="ui-max-w-100 text-truncate"></td>
                                <td v-text="pros.nombre" class="ui-max-w-300 text-truncate"></td>
                                <td v-text="pros.correo" class="ui-max-w-100 text-truncate"></td>
                                <td v-text="pros.telefono" class="ui-max-w-100 text-truncate"></td>
                                <td  class="ui-max-w-200 text-truncate">
                                     <span class="d-inline-block" tabindex="0" data-toggle="tooltip" :title="pros.ultimaatencion.comentario">
                                        <span  v-text="pros.ultimaatencion.comentario" ></span>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill py-1 px-3"
                                        :class="pros.isactive ? 'badge-success':'badge-danger'" v-text="pros.statename">
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

        <modal-atencion ref="modalAtencion"></modal-atencion>
    </div>
</template>


<script>
    import MainContent from './../../utils/MainContent';
    import PaginationLinks from './../../utils/PaginationLinks';
    import Select2 from './../../utils/Select2';
    import RowActions from './../../utils/RowActions';
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';
    import moment, { now, relativeTimeThreshold } from 'moment';
    import qs from 'qs';
    import ModalAtencion from './_ModalAtencion';
    import {bus} from '../../app'

    export default {
        data() {
            return {
                prospecto: {
                    nombre: '',
                    //fechaatencion: '',
                    //telefono: '',
                    //email: '',

                },
                prospectos: [],
                filters: {},
                pagination: {},

                tipoatenciones:[],
                etitelefonicas:[],

                users: [],
            }

        },
        methods: {
            listarAtencion() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/atencion`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.prospectos = response.data.data;
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

                this.listarAtencion();
            },
            buscarprospecto() {
                this.addFiltersRole();
                this.listarAtencion();
            },

            limpiarFiltros() {
                this.prospecto.nombre = '';
                this.prospecto.apellido = '';
                this.prospecto.correo = '';
                this.prospecto.telefono = '';
                this.prospecto.idtipoatencion= 0;
                this.prospecto.idetiquetatele= 0;
                this.buscarprospecto();
            },
            formatDate(value, fmt = 'D MMM YYYY') {
                return (value == null) ?
                    '' :
                    moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            selectToday(emit) {
                emit([new Date(), new Date()]);
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'show':
                        this.$nextTick(() => {
                            this.$refs.modalAtencion.showDetail(event.data);
                        });
                    break;
                }
            },
            addFiltersRole() {
                this.filters = {};
                this.filters.page = 1;

                if (this.prospecto.nombre.length)
                    this.filters.nombre = this.prospecto.nombre;
                if (this.prospecto.apellido.length)
                    this.filters.apellido = this.prospecto.apellido;
                if (this.prospecto.correo.length)
                    this.filters.correo = this.prospecto.correo;
                if (this.prospecto.telefono.length)
                    this.filters.telefono = this.prospecto.telefono;


            },
            agregarAtencion(param){

                this.$router.push({
                    name: 'spa.atencion.atender',params: { id: param.id }
                });
            },
            listartipoAtenciones() {
                let vm = this;
                axios.get(`${appApiUrl}/tipoAtencion/select`)
                    .then(function (response) {
                        vm.tipoatenciones = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listarEtiquetatele() {
                let vm = this;
                axios.get(`${appApiUrl}/etiquetaTelefonica/select`)
                    .then(function (response) {
                        vm.etitelefonicas = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },

        },
        // created(){
        //         this.listarAtencion();
        //         const vm = this;
        //             bus.$on('listaratenciones',()=>{
        //                 vm.listarAtencion();
        //             })
        // },
        mounted() {
            this.listarAtencion();

            this.listartipoAtenciones();
            this.listarEtiquetatele();
            //this.listarUser();
        },

        components: {
            MainContent,
            PaginationLinks,
            Select2,
            RowActions,
            VDatePicker,
            ModalAtencion,
        },

    }

</script>
