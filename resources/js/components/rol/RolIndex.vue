<template>
    <main-content columnClass="col-12 col-md-11 col-lg-9">
        <template v-slot:card-header-title>
            Roles
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarRol()">
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
            <router-link :to="{name:'spa.rol.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>    
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Rol</label>
                    <input type="text" class="form-control" v-model="rol.nombre" @keyup.enter="buscarRol()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                         v-model="rol.estado" @change="buscarRol()">
                        <label class="custom-control-label" for="estado">Incluir Eliminados</label>
                    </div>
                </div>
            </div>

            

            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                    <thead>
                        <tr>

                            <th class="p-2">Acciones</th>
                            <th class="p-2">Codigo</th>
                            <th class="p-2">Rol</th>
                            <th class="p-2">Descripción</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="itemRol in roles" :key="itemRol.id">
                            <td>
                                <row-actions :rowData="itemRol" @rowItemActions="rowItemActions">

                                </row-actions>
                            </td>
                            <td v-text="itemRol.id"></td>
                            <td v-text="itemRol.nombre"></td>
                            <td v-text="itemRol.descripcion"></td>
                            <td>
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="itemRol.isactive ? 'badge-success':'badge-danger'"
                                     v-text="itemRol.statename">
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
                roles: [],
                pagination: {},
                filters: {},
                rol: {
                    nombre: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarRoles() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/rol`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.roles = response.data.data;
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
                this.listarRoles();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                       this.$router.push({ name: 'spa.rol.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                       this.eliminarRol(event.data);
                       break;
                }
            },
            buscarRol() {
                this.filters = {};
                this.filters.page = 1;

                if (this.rol.nombre.length)
                    this.filters.nombre = this.rol.nombre;

                if (this.rol.estado)
                    this.filters.estado = this.rol.estado;    

                this.listarRoles();  
            },
            eliminarRol(param){
                let vm = this;

                swalAlertConfirm(`¿Seguro que quiere eliminar el Rol <b>${param.nombre}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/rol/${param.id}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarRoles();
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
                this.rol.nombre = '';
            }


            
        },
        mounted() {
            this.listarRoles();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions
        }
    }

</script>
