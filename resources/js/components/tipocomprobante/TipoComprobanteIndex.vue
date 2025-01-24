<template>
    <main-content columnClass="col-12 col-md-11 col-lg-9">
        <template v-slot:card-header-title>
            Tipo Comprobante
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarTipocompro()">
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
            <router-link :to="{name: 'spa.tipocomprobante.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block">
                    Nuevo
                </span>
            </router-link>
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Comprobante</label>
                    <input type="text" class="form-control" v-model="tipocompro.nombrecomprobante" @keyup.enter="buscarTipocompro()">

                </div>
                 <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Codig. Sunat</label>
                    <input type="text" class="form-control" v-model="tipocompro.codigosunat" @keyup.enter="buscarTipocompro()">

                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="p-2">Acciones</th>
                            <th class="p-2">Codigo</th>
                            <th class="p-2">Comprobante</th>
                            <th class="p-2">Cod sunat</th>
                            <th class="p-2">Serie</th>
                            <th class="p-2">Correlativo</th>
                            <th class="p-2 text-nowrap">Agrega igv</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="tipocom in tipocomprobante" :key="tipocom.codcomprobante">
                            <td>
                                <row-actions :rowData="tipocom" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="tipocom.codcomprobante"></td>
                            <td v-text="tipocom.nombrecomprobante"></td>
                            <td v-text="tipocom.codigosunat"></td>
                            <td v-text="tipocom.serie"></td>
                            <td v-text="tipocom.correlativo"></td>
                            <td >
                                <span v-if="tipocom.agregarigv" class="badge badge-pill badge-success">Si</span>
                                <span v-else class="badge badge-pill badge-info">No</span>
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
    import RowActions from './../../utils/RowActions';
    import PaginationLinks from './../../utils/PaginationLinks';

    export default {
        data() {
            return {
                tipocomprobante: [],
                filters: {},
                tipocompro: {
                    nombrecomprobante: '',
                    codigosunat:''
                },
                pagination: {}

            }
        },
        methods: {
            listartipocomprobante() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/tipocomprobante`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.tipocomprobante = response.data.data;
                        vm.pagination = response.data;
                        delete vm.pagination.data;

                    })
                    .catch(function (error) {
                        hidePreloader();
                        console.log(error);
                    });
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        this.$router.push({ name: 'spa.tipocomprobante.editar', params: { id: event.data.codcomprobante } })
                    break;
                    case 'delete':
                        this.eliminarTipoComprobante(event.data)
                    break;
                }
            },
             changePerPage(event) {
                this.filters.page = event.page;
                this.filters.perpage = event.perpage;
                this.listartipocomprobante();
            },
            buscarTipocompro() {
                this.filters = {};
                this.filters.page = 1;

                if (this.tipocompro.nombrecomprobante.length)
                    this.filters.nombrecomprobante = this.tipocompro.nombrecomprobante;
                     if (this.tipocompro.nombrecomprobante.length)
                    this.filters.nombrecomprobante = this.tipocompro.nombrecomprobante;
                if (this.tipocompro.codigosunat.length)
                    this.filters.codigosunat = `%${this.tipocompro.codigosunat}`;


                this.listartipocomprobante();

            },
            eliminarTipoComprobante(param){
                let vm = this;

                swalAlertConfirm(`¿Seguro que quiere eliminar el tipo de comprobante <b>${param.nombrecomprobante}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){

                            showPreloader();
                            axios.delete(`${appApiUrl}/tipocomprobante/${param.codcomprobante}`)
                                .then(function (response) {
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listartipocomprobante();
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
            limpiarFiltros(){
                this.tipocompro.nombrecomprobante = '';
                this.tipocompro.codigosunat='';
            }

        },
        mounted() {
            this.listartipocomprobante();
        },
        components: {
            MainContent,
            RowActions,
            PaginationLinks
        }
    }

</script>
