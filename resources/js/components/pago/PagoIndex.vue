<template>
    <main-content>
        <template v-slot:card-header-title>
            Pago
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarPago()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarPago()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.pago.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
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
                    <input type="text" class="form-control" v-model="pago.detalle" @keyup.enter="buscarPago()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label>Monto </label>
                    <vue-numeric class="form-control" ref="monto"
                        @keypress.native.enter.prevent="buscarPago"
                        thousand-separator="" v-model="pago.monto" v-bind:precision="0" >
                    </vue-numeric>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Matricula</label>
                    <select2 :options="matriculas" @input="buscarPago()" v-model="pago.matricula_id"
                        :selectValue="pago.matricula_id" placeholder="Seleccione una matricula" keyProperty="matricula_id"
                        textProperty="detalle">
                    </select2>
                </div>
               <!--  <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Concepto de Pago</label>
                    <select2 :options="conceptopagos" @input="buscarPago()" v-model="pago.concepto_id"
                        :selectValue="pago.concepto_id" placeholder="Seleccione una carrera" keyProperty="concepto_id"
                        textProperty="nombre">
                    </select2>
                </div> -->

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado"
                            v-model="pago.estado" @change="buscarPago()">
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
                            <th class="p-2">Detalle - Pago</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Apellido</th>
                            <th class="p-2">Dni</th>
                            <th class="p-2">Monto</th>
                            <th class="p-2">Detalle - Matricula</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ps in pagos" :key="ps.id" :activeShow="false">
                            <td>
                                <row-actions :rowData="ps" @rowItemActions="rowItemActions">
                                    <button type="button" title="Schneider" @click="generarComprobante(ps)"
                                                class="btn btn-sm btn-outline-sch waves-effect waves-light border-0 mr-1">
                                                <i class="fas fa-print fa-lg"></i>
                                    </button>
                                </row-actions>
                            </td>
                            <td v-text="ps.id"></td>
                            <td v-text="ps.detalle"></td>
                            <td v-text="ps.matricula.alumno.nombre"></td>
                            <td v-text="ps.matricula.alumno.apellido"></td>
                            <td v-text="ps.matricula.alumno.dni"></td>
                            <td v-text="ps.subtotal"></td>
                            <td v-text="ps.matricula?.detalle"></td>
                            <td >
                                <span class="badge badge-pill py-1 px-3"
                                    :class="ps.isactive ? 'badge-success':'badge-danger'"
                                     v-text="ps.statename">
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
    import VueNumeric from 'vue-numeric';
    import RowActions from './../../utils/RowActions';
    import Select2 from './../../utils/Select2';

    export default {
        data() {
            return {
                pagos: [],
                matriculas: [],
                conceptopagos: [],
                pagination: {},
                filters: {},
                pago: {
                    detalle: '',
                    monto: '',
                    matricula_id: '',
                    concepto_id: '',
                    estado: false
                }
            }
        },

        methods: {
            listarpago() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/pago`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.pagos = response.data.data;
                        vm.pagination = response.data;
                        delete vm.pagination.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        console.log(error);
                    });
            },
            listarMatriculas() {
                let vm = this;
                axios.get(`${appApiUrl}/matricula/select`)
                    .then(function (response) {
                        vm.matriculas = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listarConcepto() {
                let vm = this;
                axios.get(`${appApiUrl}/conceptopago/select`)
                    .then(function (response) {
                        vm.conceptopagos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            changePerPage(event) {
                this.filters.page = event.page;
                this.filters.perpage = event.perpage;
                this.listarpago();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.pago.editar', params: { id: event.data.id } })
                    break;
                    case 'delete':
                        this.eliminarpago(event.data);
                    break;
                }
            },
            buscarPago() {
                this.filters = {};
                this.filters.page = 1;

                if (this.pago.detalle.length)
                    this.filters.detalle = this.pago.detalle;

                if (this.pago.monto.length)
                    this.filters.monto = this.pago.monto;

                if (this.pago.matricula_id.length)
                    this.filters.matricula_id = this.pago.matricula_id;

                if (this.pago.concepto_id.length)
                    this.filters.concepto_id = this.pago.concepto_id;

                if (this.pago.estado)
                    this.filters.estado = this.pago.estado;

                this.listarpago();
            },
            eliminarpago(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el pago con codigo <b>${param.id}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){

                            showPreloader();
                            axios.delete(`${appApiUrl}/pago/${param.id}`)
                                .then(function (response) {        0
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarpago();
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
            limpiarPago(){
                this.pago.detalle = '';
                this.pago.monto = '';
                this.pago.matricula_id = '';
                this.pago.concepto_id = '';
                this.buscarPago();
            },
            generarComprobante(data){
                // console.log(data);
                let urlPdf = `${appApiUrl}/pago/comprobante/${data.id}`;
                window.open(urlPdf,'_blank')
            },

        },
        mounted() {
            this.listarpago();
            this.listarMatriculas();
            this.listarConcepto();
        },
        components: {
            MainContent,
            PaginationLinks,
            VueNumeric,
            RowActions,
            Select2,
        }
    }

</script>
