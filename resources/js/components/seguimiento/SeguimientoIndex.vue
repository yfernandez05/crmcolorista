<template>
    <main-content>
        <template v-slot:card-header-title>
            seguimiento
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="seguimientobuscar()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarseguimiento()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.seguimiento.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Atencion</label>
                    <input type="text" class="form-control" v-model="seguimiento.atencion" @keyup.enter="seguimientobuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Toque</label>
                    <input type="text" class="form-control" v-model="seguimiento.toque" @keyup.enter="seguimientobuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>respuesta</label>
                    <input type="text" class="form-control" v-model="seguimiento.respuesta" @keyup.enter="seguimientobuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado"
                            v-model="seguimiento.estado" @change="seguimientobuscar()">
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
                            <th class="p-2">Atencion</th>
                            <th class="p-2">Toque</th>
                            <th class="p-2">Respuesta</th>
                            <th class="p-2">Prospecto</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="segui in seguimientos" :key="segui.id">
                            <td>
                                <row-actions :rowData="segui" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="segui.id"></td>
                            <td v-text="segui.atencion"></td>
                            <td v-text="segui.toque"></td>
                            <td v-text="segui.respuesta"></td>
                            <td v-text="segui.prospecto.nombre"></td>
                            <td >
                                <span class="badge badge-pill py-1 px-3"
                                    :class="segui.isactive ? 'badge-success':'badge-danger'"
                                     v-text="segui.statename">
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

    export default {
        data() {
            return {
                seguimientos: [],
                pagination: {},
                filters: {},
                seguimiento: {
                    atencion: '',
                    toque: '',
                    respuesta: '',
                    prospecto_id: '',

                }
            }
        },

        methods: {
            listarseguimiento() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/seguimiento`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.seguimientos = response.data.data;
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
                this.listarseguimiento();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.seguimiento.editar', params: { id: event.data.id } })
                    break;
                    case 'delete':
                        this.eliminarseguimiento(event.data);
                    break;
                }
            },
            seguimientobuscar() {
                this.filters = {};
                this.filters.page = 1;

                if (this.seguimiento.atencion)
                    this.filters.atencion = this.seguimiento.atencion;

                if (this.seguimiento.toque)
                    this.filters.toque = this.seguimiento.toque;

                if (this.seguimiento.respuesta)
                    this.filters.respuesta = this.seguimiento.respuesta;


                if (this.seguimiento.estado)
                    this.filters.estado = this.seguimiento.estado;


                this.listarseguimiento();
            },
            eliminarseguimiento(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el seguimiento <b>${param.atencion}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){

                            showPreloader();
                            axios.delete(`${appApiUrl}/seguimiento/${param.id}`)
                                .then(function (response) {
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarseguimiento();
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
            limpiarseguimiento(){
                this.seguimiento.atencion = '';
                this.seguimiento.toque = '';
                this.seguimiento.respuesta = '';

            },
        },
        mounted() {
            this.listarseguimiento();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
        }
    }

</script>
