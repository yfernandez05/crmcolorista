<template>
    <main-content columnClass="col-12">
        <template v-slot:card-header-title>
            Tipos de Atencion
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarTipoAtencion()">
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
            <router-link :to="{name:'spa.tipoAtencion.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>    
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Tipo de Atencion</label>
                    <input type="text" class="form-control" v-model="tipoatencion.tipoatencion" @keyup.enter="buscarTipoAtencion()"> 
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                         v-model="tipoatencion.estado" @change="buscarTipoAtencion()">
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
                            <th class="p-2 text-nowrap">Estado Atencion</th>                      
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="tipoatencion in tipoatenciones" :key="tipoatencion.idtipoatencion" >
                            <td>
                                <row-actions :rowData="tipoatencion" @rowItemActions="rowItemActions" >
                                </row-actions>
                            </td>
                            <td v-text="tipoatencion.idtipoatencion"></td>
                            <td><span class="badge badge-pill badge-lg py-1" v-bind:style="{background: tipoatencion.backgroundColor, color:tipoatencion.textColor}" v-text="tipoatencion.tipoatencion"></span></td>
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="tipoatencion.isactive ? 'badge-success':'badge-danger'"
                                     v-text="tipoatencion.statename">
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
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';
    import moment,{ now } from 'moment';

    export default {
        data() {
            return {
                tipoatenciones: [],
                pagination: {},
                filters: {},
                tipoatencion: {
                    tipoatencion: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarTipoAtencion() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/tipoAtencion`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.tipoatenciones = response.data.data;
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
                this.listarTipoAtencion();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }
                        if(event.data.idtipoatencion <= 3) {
                            warningMessage('No esta permitido editar estos registros preestablecidos.', appName);
                            break;
                        }
                        this.$router.push({ name: 'spa.tipoAtencion.editar', params: { id: event.data.idtipoatencion } }) 
                    break;
                    case 'delete':
                        if(event.data.idtipoatencion <= 3) {
                            warningMessage('No esta permitido eliminar estos registros preestablecidos.', appName);
                            break;
                        }
                        this.eliminarTipoAtencion(event.data);
                    break;
                }
            },
            buscarTipoAtencion() {
                this.filters = {};
                this.filters.page = 1;

                if (this.tipoatencion.tipoatencion.length)
                    this.filters.tipoatencion = this.tipoatencion.tipoatencion;                
                               
                if (this.tipoatencion.estado)
                    this.filters.estado = this.tipoatencion.estado;

                this.listarTipoAtencion();  
            },
            eliminarTipoAtencion(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el Tipo de Atencion <b>${param.tipoatencion}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/tipoAtencion/${param.idtipoatencion}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarTipoAtencion();
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
                this.tipoatencion.tipoatencion = '';
            }

        },
        mounted() {
            this.listarTipoAtencion();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            VDatePicker,
        }
    }

</script>
