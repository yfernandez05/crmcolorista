<template>
    <main-content colunmClass="col-12 col-md-11 col-lg-9">
        <template v-slot:card-header-title>
            Exportar Participantes
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
                    <label class="mb-1">Cliente</label>
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
                    <select2 :options="campania" @input="buscarCliente()" v-model="cliente.idcampania"
                        :selectValue="cliente.idcampania" placeholder="Seleccione una campaña" keyProperty="idcampania"
                        textProperty="nombrecampania">
                    </select2>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Distrito</label>
                    <select2 :options="distrito" @input="buscarCliente()" v-model="cliente.coddistrito"
                        :selectValue="cliente.coddistrito" placeholder="Seleccione una Distrito"
                        keyProperty="coddistrito" textProperty="distrito">
                    </select2>
                </div>



                <div class="form-group col-12 col-sm-6 col-md-4 ">
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
                            <th class="p-2">id</th>
                            <th class="p-2">Nombres</th>
                            <th class="p-2">Apellido Paterno</th>
                            <th class="p-2">Apellido Materno</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Dni</th>
                            <th class="p-2">Telefono</th>
                            <th class="p-2">Campaña</th>
                            <th class="p-2">F. Registro</th>
                            <th class="p-2">Distrito</th>
                            <th class="p-2">utm</th>
                            <th class="p-2">carrera</th>
                            <th class="p-2">colegio</th>
                            <th class="p-2">anioegreso</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cli in clientes" :key="cli.idcliente">
                            <td v-text="cli.idcliente"></td>
                            <td v-text="cli.nombres" class="ui-max-w-300 text-truncate"></td>
                            <td v-text="cli.apellidopaterno" class="ui-max-w-300 text-truncate"></td>
                            <td v-text="cli.apellidomaterno" class="ui-max-w-300 text-truncate"></td>
                            <td v-text="cli.email"></td>
                            <td v-text="cli.dni"></td>
                            <td v-text="cli.telefono"></td>
                            <td v-text="cli.campania.nombrecampania"></td>
                            <td v-text="cli.fecha"></td>
                            <td v-text="cli.distrito.nombreubigeo"></td>
                            <td v-text="cli.utm"></td>
                            <td v-text="cli.carrera"></td>
                            <td v-text="cli.colegio"></td>
                            <td v-text="cli.anioegreso"></td>
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
</template>


<script>
    import MainContent from './../../utils/MainContent';
    import PaginationLinks from './../../utils/PaginationLinks';
    import Select2 from './../../utils/Select2';
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';
    import moment, { now, relativeTimeThreshold } from 'moment';
    import qs from 'qs';

    export default {
        data() {
            return {

                cliente: {
                    nombres: '',
                    apellidopaterno: '',
                    apellidomaterno: '',
                    dni: '',
                    fecharegistro: '',
                    estado: false,
                    coddistrito: 0,
                    idcampania: 0,
                },
                clientes: [],
                filters: {},
                pagination: {},
                campania: [],
                distrito: []
            }

        },
        methods: {
            listarCliente() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/cliente`, {
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
            limpiarFiltros() {

                this.cliente.nombres = '';
                this.cliente.apellidopaterno = '';
                this.cliente.apellidomaterno = '';
                this.cliente.dni = '',
                    this.cliente.idcampania = 0,
                    this.cliente.coddistrito = 0

            },
            formatDate(value, fmt = 'D MMM YYYY') {
                return (value == null) ?
                    '' :
                    moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            selectToday(emit) {
                emit([new Date(), new Date()]);
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

                if (this.cliente.idcampania.length)
                    this.filters.idcampania = this.cliente.idcampania;

                if (this.cliente.coddistrito.length)
                    this.filters.coddistrito = `%${this.cliente.coddistrito}`;

                if (this.cliente.estado)
                    this.filters.estado = this.cliente.estado;

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
        mounted() {
            this.listarCliente();
            this.listarCampania();
            this.listarUbigeo();
        },
        components: {
            MainContent,
            PaginationLinks,
            Select2,
            VDatePicker
        },
    }

</script>
