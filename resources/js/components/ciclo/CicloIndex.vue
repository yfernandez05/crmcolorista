<template>
    <main-content>
        <template v-slot:card-header-title>
            Modulo
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarCiclo()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarCiclo()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.ciclo.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Nombre</label>
                    <input type="text" class="form-control" v-model="ciclo.nombre" @keyup.enter="buscarCiclo()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Descripción</label>
                    <input type="text" class="form-control" v-model="ciclo.descripcion" @keyup.enter="buscarCiclo()">
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado"
                            v-model="ciclo.estado" @change="buscarCiclo()">
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
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Duracion meses</th>
                            <th class="p-2">Precio por Mes</th>
                            <th class="p-2">Descripción</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cic in ciclos" :key="cic.id">
                            <td>
                                <row-actions :rowData="cic" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="cic.id"></td>
                            <td v-text="cic.nombre"></td>

                            <td v-text="cic.duracion"></td>
                            <td v-text="cic.preciomes"></td>
                            <td v-text="cic.descripcion"></td>
                            <td >
                                <span class="badge badge-pill py-1 px-3"
                                    :class="cic.isactive ? 'badge-success':'badge-danger'"
                                     v-text="cic.statename">
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
                ciclos: [],
                pagination: {},
                filters: {},
                ciclo: {
                    nombre: '',
                    descripcion: '',
                    estado: false
                }
            }
        },

        methods: {
            listarCiclo() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/ciclo`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.ciclos = response.data.data;
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
                this.listarCiclo();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.ciclo.editar', params: { id: event.data.id } })
                    break;
                    case 'delete':
                        this.eliminarCiclo(event.data);
                    break;
                }
            },
            buscarCiclo() {
                this.filters = {};
                this.filters.page = 1;

                if (this.ciclo.nombre.length)
                    this.filters.nombre = this.ciclo.nombre;

                if (this.ciclo.descripcion.length)
                    this.filters.descripcion = this.ciclo.descripcion;

                if (this.ciclo.estado)
                    this.filters.estado = this.ciclo.estado;

                this.listarCiclo();
            },
            eliminarCiclo(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el ciclo <b>${param.nombre}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){

                            showPreloader();
                            axios.delete(`${appApiUrl}/ciclo/${param.id}`)
                                .then(function (response) {
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarCiclo();
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
            limpiarCiclo(){
                this.ciclo.nombre = '';
                this.ciclo.descripcion = '';
                this.buscarCiclo();
            },

        },
        mounted() {
            this.listarCiclo();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
        }
    }

</script>
