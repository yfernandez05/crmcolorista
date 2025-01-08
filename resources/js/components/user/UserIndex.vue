<template>
    <main-content columnClass="col-12 col-xl-12" >
        <template v-slot:card-header-title>
            Usuario
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarUser()">
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
            <router-link :to="{name: 'spa.user.registrar'}"
                class="btn btn-sm btn-success waves-effect waves-light">
                <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block">
                    Nuevo
                </span>
            </router-link>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row mb-2">
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Nombres</label>
                    <input type="text" class="form-control" v-model="user.name" @keyup.enter="buscarUser" />
                </div>
                 <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Rol</label>
                    <select2 :options="rol" @input="buscarUser" v-model="user.rol_id"
                        :selectValue="user.rol_id" placeholder="Seleccione un Rol" keyProperty="id"
                        textProperty="nombre">
                    </select2>
                </div>

            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="p-2">Acciones</th>
                            <th class="p-2">Codigo</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Apellidos</th>
                            <th class="p-2">Especialidad</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Api_Token</th>
                            <th class="p-2">Rol</th>
                            <th class="p-2">Estado</th>
                       

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="use in users" :key="use.id">
                            <td>
                                <row-actions :rowData="use" @rowItemActions="rowItemActions" :activeDelete="false">
                                    <toggle-button :labels="true" :value="!use.isactive" @change="cambiarEstado(use)" class="mb-0"
                                        :color="{checked: '#00c292', unchecked: '#e46a76'}">
                                        <template slot="checked">
                                            <i class="fas fa-check fa-lg" title="Activar"></i>
                                        </template>
                                        <template slot="unchecked">
                                            <i class="fas fa-trash fa-lg" title="Eliminar"></i>
                                        </template>
                                    </toggle-button>                     
                                </row-actions>
                            </td>
                            <td v-text="use.id"></td>
                            <td v-text="use.name" class="ui-max-w-300 text-truncate"></td>
                            <td v-text="use.last_name" class="ui-max-w-300 text-truncate"></td>
                            <td v-text="use.specialities" class="ui-max-w-300 text-truncate"></td>
                            <td v-text="use.email"></td>
                            <td v-text="use.api_token"></td>
                            <td v-text="use.rol.rol"></td>
                            <td>
                                <span class="badge badge-pill py-1 px-3" :class="use.isactive ? 'badge-success':'badge-danger'"
                                     v-text="use.statename" ></span>
                                
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
    import RowActions from './../../utils/RowActions';
    import { ToggleButton } from 'vue-js-toggle-button';

    export default {
        data() {
            return {
                user: {
                    name:'',
                    rol_id:0,
                },
                users: [],
                filters: {},
                pagination: {},
                rol: []
            }

        },
        methods: {
            listarUser() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/user`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.users = response.data.data;
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

                this.listarUser();
            },
            buscarUser() {
                this.filters = {};
                this.filters.page = 1;

                if (this.user.name.length)
                    this.filters.name = this.user.name;

                if (this.user.rol_id.length)
                    this.filters.rol_id = this.user.rol_id;
                
                this.listarUser();
            },
            listarRoles() {
                let vm = this;
                axios.get(`${appApiUrl}/rol/select`)
                    .then(function (response) {
                        vm.rol = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                       this.$router.push({
                           name: 'spa.user.editar',
                          params: {
                            id: event.data.id
                           }
                        })
                        break;
                }
            },
            limpiarFiltros() {
                this.user = {
                    name: '',
                    rol_id: 0
                };

                this.listarUser();
            },
            cambiarEstado(param){
                let vm = this;

                showPreloader();
                axios.delete(`${appApiUrl}/user/${param.id}`)
                    .then(function (response) {         
                        hidePreloader();
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);
                            vm.listarUser();
                        } else
                            errorMessage(result.message, appName);
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        vm.listarUser();
                        console.log(error);
                    });

            }
        },
        mounted() {
            this.listarUser();
            this.listarRoles();
            //console.log(process.env.MIX_APP_DEBUG);
        },
        components: {
            MainContent,
            PaginationLinks,
            Select2,
            RowActions,
            ToggleButton
        },
    }

</script>
