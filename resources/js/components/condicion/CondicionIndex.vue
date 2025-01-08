<template>
    <main-content>
        <template v-slot:card-header-title>
            Condiciones
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="condicionBuscar()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarfiltroCondicion()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.condicion.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
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
                    <input type="text" class="form-control" v-model="condicion.nombre" @keyup.enter="condicionBuscar()">
                </div>
                
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="condicion.estado" @change="condicionBuscar()">
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
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="condi in condiciones" :key="condi.id">
                            <td>
                                <row-actions :rowData="condi" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="condi.id"></td>
                            <td v-text="condi.nombre"></td>                            
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="condi.isactive ? 'badge-success':'badge-danger'"
                                     v-text="condi.statename">
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
                condiciones: [],
                pagination: {},
                filters: {},
                condicion: {
                    nombre: '',
                }
            }
        },
        
        methods: {
            listarCondicion() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/condicion`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.condiciones = response.data.data;
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
                this.listarCondicion();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.condicion.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarCondicion(event.data);
                    break;
                }
            },
            condicionBuscar() {
                this.filters = {};
                this.filters.page = 1;

                if (this.condicion.nombre.length)
                    this.filters.nombre = this.condicion.nombre;

                if (this.condicion.estado)
                    this.filters.estado = this.condicion.estado;


                this.listarCondicion();  
            },
            eliminarCondicion(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar la condición <b>${param.nombre}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/condicion/${param.id}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarCondicion();
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
            limpiarfiltroCondicion(){
                this.condicion.nombre = '';
            },


        },
        mounted() {
            this.listarCondicion();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
        }
    }

</script>
