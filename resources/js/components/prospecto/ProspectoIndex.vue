<template>
    <main-content>
        <template v-slot:card-header-title>
            Prospecto
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="prospectobuscar()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarprospecto()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.prospecto.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
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
                    <input type="text" class="form-control" v-model="prospecto.nombre" @keyup.enter="prospectobuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Apellido</label>
                    <input type="text" class="form-control" v-model="prospecto.apellido" @keyup.enter="prospectobuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Correo</label>
                    <input type="text" class="form-control" v-model="prospecto.correo" @keyup.enter="prospectobuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado"
                            v-model="prospecto.estado" @change="prospectobuscar()">
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
                            <th class="p-2">Apellido</th>
                            <th class="p-2">Fecha Nacimiento</th>
                            <th class="p-2">Correo</th>
                            <th class="p-2">Telefono</th>
                            <th class="p-2">Procedencia</th>

                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="pros in prospectos" :key="pros.id">
                            <td>
                                <row-actions :rowData="pros" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="pros.id"></td>
                            <td v-text="pros.nombre"></td>
                            <td v-text="pros.apellido"></td>
                            <td v-text="pros.fechanacimiento"></td>
                            <td v-text="pros.correo"></td>
                            <td v-text="pros.telefono"></td>
                            <td v-text="pros.procedencia"></td>
                            <td >
                                <span class="badge badge-pill py-1 px-3"
                                    :class="pros.isactive ? 'badge-success':'badge-danger'"
                                     v-text="pros.statename">
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
                prospectos: [],
                pagination: {},
                filters: {},
                prospecto: {
                    nombre: '',
                    apellido: '',
                    correo: '',

                }
            }
        },

        methods: {
            listarprospecto() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/prospecto`, {
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
                this.listarprospecto();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.prospecto.editar', params: { id: event.data.id } })
                    break;
                    case 'delete':
                        this.eliminarprospecto(event.data);
                    break;
                }
            },
            prospectobuscar() {
                this.filters = {};
                this.filters.page = 1;

                if (this.prospecto.nombre.length)
                    this.filters.nombre = this.prospecto.nombre;
                if (this.prospecto.apellido.length)
                    this.filters.apellido = this.prospecto.apellido;
                if (this.prospecto.correo.length)
                    this.filters.correo = this.prospecto.correo;

                if (this.prospecto.estado)
                    this.filters.estado = this.prospecto.estado;


                this.listarprospecto();
            },
            eliminarprospecto(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el prospecto <b>${param.nombre}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){

                            showPreloader();
                            axios.delete(`${appApiUrl}/prospecto/${param.id}`)
                                .then(function (response) {
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarprospecto();
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
            limpiarprospecto(){
                this.prospecto.nombre = '';
                this.prospecto.apellido = '';
                this.prospecto.correo = '';
            },
        },
        mounted() {
            this.listarprospecto();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
        }
    }

</script>
