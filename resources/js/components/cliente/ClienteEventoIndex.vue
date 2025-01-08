<template>
    <div>
        <main-content colunmClass="col-12 col-md-11 col-lg-9">
            <template v-slot:card-header-title>
                Alumnos Eventos
            </template>
            <template v-slot:card-header-actions>
                <button class="btn btn-sm btn-green waves-effect waves-light" @click="descargarExcel()">
                    <i class="fas fa-file-excel"></i>
                    <span class="d-none d-sm-inline-block">
                        Descargar
                    </span>
                </button>
                <i class="fas fa-grip-lines-vertical text-white px-3"></i>
                <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarCliente()">
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
                        <label class="mb-1">Nombres</label>
                        <input type="text" class="form-control" v-model="cliente.nombres" @keyup.enter="buscarCliente" />
                    </div>

                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Apellido Paterno</label>
                        <input type="text" class="form-control" v-model="cliente.apellidopaterno" @keyup.enter="buscarCliente" />
                    </div>

                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Apellido Materno</label>
                        <input type="text" class="form-control" v-model="cliente.apellidomaterno" @keyup.enter="buscarCliente" />
                    </div>

                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Dni</label>
                        <input type="text" class="form-control" v-model="cliente.dni" @keyup.enter="buscarCliente" />
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Telefono</label>
                        <input type="text" class="form-control" v-model="cliente.telefono" @keyup.enter="buscarCliente" />
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Correo</label>
                        <input type="text" class="form-control" v-model="cliente.correo" @keyup.enter="buscarCliente" />
                    </div>

                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3 ">
                        <label>Fecha</label>
                        <v-date-picker v-model="cliente.fecharegistro" range lang="es" format="DD-MM-YYYY" confirm
                            :editable="false" placeholder="Seleccione un rango de fecha" @input="buscarCliente()">
                            <template v-slot:footer="{ emit }">
                                <button class="mx-btn" @click="selectToday(emit)">
                                    Today
                                </button>
                            </template>
                        </v-date-picker>
                    </div>

                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Campaña</label>
                        <select2 :options="campania" @input="buscarEvento()" v-model="cliente.idcampania"
                            :selectValue="cliente.idcampania" placeholder="Seleccione una campaña"
                            keyProperty="idcampania" textProperty="nombrecampania">
                        </select2>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Evento</label>
                        <select2 :options="eventos" @input="buscarCliente()" v-model="cliente.idevento"
                            :selectValue="cliente.idevento" placeholder="Seleccione un Evento"
                            keyProperty="idevento" textProperty="nombreevento">
                        </select2>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Procedencia</label>
                        <select2 :options="procedencias" @input="buscarCliente()" v-model="cliente.procedencia"
                            :selectValue="cliente.procedencia" placeholder="Seleccione una procedencia"
                            textProperty="procedencia">
                        </select2>
                    </div>

                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label class="mb-1">Distrito</label>
                        <select2 :options="distrito" @input="buscarCliente()" v-model="cliente.coddistrito"
                            :selectValue="cliente.coddistrito" placeholder="Seleccione una Distrito"
                            keyProperty="coddistrito" textProperty="distrito">
                        </select2>
                    </div>

                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label>&nbsp;</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="asistencia" v-model="cliente.asistencia"
                                @change="buscarCliente()">
                            <label class="custom-control-label" for="asistencia">Asistente</label>
                        </div>
                    </div>

                    <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                        <label>&nbsp;</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="estado" v-model="cliente.estado"
                                @change="buscarCliente()">
                            <label class="custom-control-label" for="estado">Incluir Eliminados</label>
                        </div>
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="p-2">Accion</th>
                                <th class="p-2">id</th>
                                <th class="p-2">idCliente</th>
                                <th class="p-2">Nombres</th>
                                <th class="p-2">Apellido Paterno</th>
                                <th class="p-2">Apellido Materno</th>
                                <th class="p-2">Email</th>
                                <th class="p-2">Telefono</th>
                                <th class="p-2">Edad</th>                            
                                <th class="p-2 text-nowrap">F. Registro</th>
                                <th class="p-2">Carrera</th>
                                <th class="p-2">Campaña</th>
                                <th class="p-2">Asistencia</th>
                                <th class="p-2">F.Asistencia</th>
                                <th class="p-2">Evento</th>
                                <th class="p-2">Asistencia Evento</th>
                                <th class="p-2">F.Asistencia Evento</th>
                                <th class="p-2">Tipo Participante</th>
                                <th class="p-2">Empresa</th>
                                <th class="p-2">Origen</th>
                                <th class="p-2">Distrito</th>
                                <th class="p-2">Dni</th>
                                <th class="p-2">Grado</th>
                                <th class="p-2">colegio</th>
                                <th class="p-2">Anioegreso</th>
                                <th class="p-2">Procedencia</th>
                                <th class="p-2">utm</th>
                                <th class="p-2">campaign_content</th>
                                <th class="p-2">campaign_medium</th>
                                <th class="p-2">campaign_name</th>
                                <th class="p-2">campaign_source</th>
                                <th class="p-2">campaign_term</th>
                                <th class="p-2">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cli in clientes" :key="cli.ideventocliente">
                                <td>
                                    <row-actions :rowData="cli" @rowItemActions="rowItemActions" :activeEdit="false" :activeDelete="false" :activeShow="true">
                                        <button type="button" :title="cli.isactive ? 'Desactivar':'Activar'" @click="cambiarEstado(cli)"
                                                class="btn btn-sm  waves-effect waves-light border-0 mr-1"
                                                :class="cli.isactive ? 'btn-outline-danger':'btn-outline-success'">
                                            <i class="fas fa-lg" :class="cli.isactive ? 'fa-trash':'fa-check-circle'"></i>
                                        </button>
                                    </row-actions>
                                </td>
                                <td v-text="cli.ideventocliente"></td>
                                <td v-text="cli.idcliente"></td>
                                <td v-text="cli.nombres" class="ui-max-w-300 text-truncate"></td>
                                <td v-text="cli.apellidopaterno" class="ui-max-w-300 text-truncate"></td>
                                <td v-text="cli.apellidomaterno" class="ui-max-w-300 text-truncate"></td>
                                <td v-text="cli.email"></td>
                                <td v-text="cli.telefono"></td>
                                <td v-text="cli.edad"></td>                            
                                <td v-text="cli.fecha" class="ui-max-w-100 text-truncate"></td>
                                <td v-text="cli.carrera" class="ui-max-w-200 text-truncate"></td>
                                <td v-text="cli.evento.campania.nombrecampania" class="ui-max-w-200 text-truncate"></td>
                                <td>
                                    <span class="badge badge-pill py-1 px-3"
                                        :class="cli.isassistance ? 'badge-info':'badge-secondary'" v-text="cli.asistencianame">
                                    </span>
                                </td>
                                <td v-text="cli.fechapresencia" class="ui-max-w-200 text-truncate"></td>
                                <td v-text="cli.evento.nombreevento" class="ui-max-w-300 text-truncate"></td>
                                <td>
                                    <span class="badge badge-pill py-1 px-3"
                                        :class="cli.isassistance ? 'badge-info':'badge-secondary'" v-text="cli.asistencianame">
                                    </span>
                                </td>
                                <td v-text="cli.fechapresencia" class="ui-max-w-200 text-truncate"></td>
                                <td v-text="cli.tipoparticipante" class="ui-max-w-300 text-truncate"></td>
                                <td v-text="cli.evento.campania.cuenta.empresa" class="ui-max-w-300 text-truncate"></td>
                                <td v-text="cli.origen" class="ui-max-w-200 text-truncate"></td>
                                <td v-text="cli.distrito.nombreubigeo" class="ui-max-w-100 text-truncate"></td>
                                <td v-text="cli.dni"></td>
                                <td v-text="cli.grado" class="ui-max-w-200 text-truncate"></td>
                                <td v-text="cli.colegio " class="ui-max-w-200 text-truncate"></td>
                                <td v-text="cli.anioegreso"></td>
                                <td v-text="cli.procedencia"></td>
                                <td v-text="cli.utm"></td>
                                <td v-text="cli.campaign_content"></td>
                                <td v-text="cli.campaign_medium"></td>
                                <td v-text="cli.campaign_name"></td>
                                <td v-text="cli.campaign_source"></td>
                                <td v-text="cli.campaign_term"></td>
                                <td>
                                    <span class="badge badge-pill py-1 px-3"
                                        :class="cli.isactive ? 'badge-success':'badge-danger'" v-text="cli.statename">
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
//importamos librerias o componentes
import MainContent from './../../utils/MainContent';
import PaginationLinks from './../../utils/PaginationLinks';
import Select2 from './../../utils/Select2';
import RowActions from './../../utils/RowActions';
import VDatePicker from 'vue2-datepicker';
import 'vue2-datepicker/locale/es';
import moment, { now, relativeTimeThreshold } from 'moment';
import qs from 'qs';
import ModalAtencion from '../atencion/_ModalAtencion';

export default {
    data() {
        return {
            cliente: {
                nombres: '',
                apellidopaterno: '',
                apellidomaterno: '',
                dni: '',
                telefono: '',
                correo: '',
                fecharegistro: '',
                estado: false,
                coddistrito: 0,
                idcampania: 0,
                idevento: 0,
                procedencia: '',
            },
            clientes: [],
            filters: {},
            pagination: {},
            campania: [],
            eventos: [],
            distrito: [],
            procedencias: [],
        }
    },

    //metodos
    methods:{
        listarCliente() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/clienteevento`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.clientes = response.data.data;
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

            this.listarCliente();
        },
        buscarCliente() {
            this.addFiltersRole();
            this.listarCliente();
        },
        buscarEvento(){
            this.cliente.idevento = 0; 
            this.eventos = [];

            let vm = this;
            axios.get(`${appApiUrl}/evento/getevento`, {params: {idcampania:this.cliente.idcampania}})
            .then(function (response) {

                if (response.data == null || response.data == '') {
                    warningMessage(`Esta campaña no tiene ningun evento`, appName);
                }
                
                vm.eventos = response.data;
                vm.buscarCliente();
            })
            .catch(function (error) {
                errorMessage(appErrorMessage, appName);
                console.log(error);
            })
        },
        listarCampania() {
            let vm = this;
            axios.get(`${appApiUrl}/campania/select`)
                .then(function (response) {
                    vm.campania = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
        },
        
        listarUbigeo() {
            let vm = this;
            axios.get(`${appApiUrl}/ubigeo/distritosleccionados`)
                .then(function (response) {
                    vm.distrito = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
        },

        listarProcedenciaUTM(){
            let vm = this;
            axios.get(`${appApiUrl}/cliente/procedenciaAdsUtm`)
            .then(function (response) {
                vm.procedencias = response.data;
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        limpiarFiltros() {

            this.cliente.nombres = '';
            this.cliente.apellidopaterno = '';
            this.cliente.apellidomaterno = '';
            this.cliente.dni = '',
            this.cliente.telefono = '';
            this.cliente.correo = '';
            this.cliente.idcampania = 0;
            this.cliente.coddistrito = 0;
            this.cliente.procedencia = '';
            this.cliente.idevento= 0;
            this.buscarCliente();

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
                // case 'delete':
                //     this.cambiarEstado(event.data);
                //     break;
            }
            
        },
        cambiarEstado(param) {
            let vm = this;
            let optionMessage ='Eliminar';

            if(!param.isactive) optionMessage = 'Activar';

            swalAlertConfirm(`¿Seguro que quiere ${optionMessage} El Cliente <b>${param.nombres}</b>?`, appName)
                .then(function (optionSelected) {

                    if (optionSelected.value) {

                        showPreloader();
                        axios.delete(`${appApiUrl}/cliente/${param.idcliente}`)
                        .then(function (response) {
                            hidePreloader();
                            let result = response.data;

                            if (result.status) {
                                successMessage(result.message, appName);
                                vm.listarCliente();
                            }  else if (result.warning){
                                warningMessage(result.message, appName);
                                vm.listarCliente();
                            } else{
                                errorMessage(result.message, appName);
                            }

                        })
                        .catch(function (error) {
                            hidePreloader();
                            errorMessage(appErrorMessage, appName);
                            console.log(error);
                        });
                    }
                });
        },
        addFiltersRole() {
            this.filters = {};
            this.filters.page = 1;

            if (this.cliente.nombres.length)
                this.filters.nombres = this.cliente.nombres;

            if (this.cliente.apellidopaterno.length)
                this.filters.apellidopaterno = this.cliente.apellidopaterno;

            if (this.cliente.apellidomaterno.length)
                this.filters.apellidomaterno = this.cliente.apellidomaterno;

            if (this.cliente.dni.length)
                this.filters.dni = this.cliente.dni;

            if (this.cliente.telefono.length)
                this.filters.telefono = this.cliente.telefono;
            
            if (this.cliente.correo.length)
                this.filters.email = this.cliente.correo;    

            if (this.cliente.idcampania.length)
                this.filters.idcampania = this.cliente.idcampania;

            /* if (this.cliente.idevento.length)
                this.filters.idevento = this.cliente.idevento; */

            if (this.cliente.procedencia.length)
                this.filters.procedencia = this.cliente.procedencia;

            if (this.cliente.coddistrito.length)
                this.filters.coddistrito = `%${this.cliente.coddistrito}`;

            if (this.cliente.estado)
                this.filters.estado = this.cliente.estado;

            if (this.cliente.asistencia)
                this.filters.asistencia = 1;

            if (this.cliente.fecharegistro.length == 2) {
                if (this.cliente.fecharegistro[0] != null)
                    this.filters.fechadesde = this.formatDate(this.cliente.fecharegistro[0], 'DD-MM-YYYY');

                if (this.cliente.fecharegistro[1] != null)
                    this.filters.fechahasta = this.formatDate(this.cliente.fecharegistro[1], 'DD-MM-YYYY');
            }
        },
        descargarExcel() {
            this.addFiltersRole();

            let urlexcel = `${appApiUrl}/cliente/exportarexcel?${qs.stringify(this.filters)}`;
            window.location = urlexcel;
        }

    },

    //iniciamos metodos
    created(){
        this.listarCliente();
        this.listarCampania();
        this.listarUbigeo();
        this.listarProcedenciaUTM();
    },

    //iniciamos los componentes
    components:{
        MainContent,
        PaginationLinks,
        Select2,
        RowActions,
        VDatePicker,
        ModalAtencion
    },
}
</script>