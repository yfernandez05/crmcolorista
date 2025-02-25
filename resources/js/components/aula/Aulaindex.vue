<template>
    <main-content>
        <template v-slot:card-header-title>
            Aula
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarAula()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarAula()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.aula.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>    
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Carrera</label>
                    <select2 :options="carreras" @input="buscarAula()" v-model="aula.carrera_id"
                        :selectValue="aula.carrera_id" placeholder="Seleccione una carrera" keyProperty="carrera_id"
                        textProperty="nombre">
                    </select2>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="aula.estado" @change="buscarAula()">
                        <label class="custom-control-label" for="estado">Incluir Eliminados</label>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="p-2">Acciones</th>
                            <th class="p-2">Cod.</th>
                            <th class="p-2">Carrera</th>
                            <th class="p-2">Aulas</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="au in aulas" :key="au.id">
                            <td>
                                <row-actions :rowData="au" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="au.id"></td>                        
                            <td v-text="au.carrera?.nombre"></td>                            
                            <td>
                                <ul class="list-unstyled m-0" style="display: flex; flex-wrap: wrap;">
                                    <li v-for="detalle in au.detalles" :key="detalle.id">
                                        <span class="badge badge-pill py-1 px-3 badge-info m-1" v-text="detalle.nombre_aula"></span>
                                    </li>
                                </ul>
                            </td>                          
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="au.isactive ? 'badge-success':'badge-danger'"
                                     v-text="au.statename">
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
    import Select2 from './../../utils/Select2';

    export default {
        data() {
            return {
                aulas: [],
                carreras: [],
                pagination: {},
                filters: {},
                aula: {
                    carrera_id: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarAulas() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/aula`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.aulas = response.data.data;
                        vm.pagination = response.data;
                        delete vm.pagination.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        console.log(error);
                    });
            },
            listarCarreras() {
                let vm = this;
                axios.get(`${appApiUrl}/carrera/select`)
                    .then(function (response) {
                        vm.carreras = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            changePerPage(event) {
                this.filters.page = event.page;
                this.filters.perpage = event.perpage;
                this.listarAulas();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.aula.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarAula(event.data);
                    break;
                }
            },
            buscarAula() {
                this.filters = {};
                this.filters.page = 1;
                
                if (this.aula.carrera_id.length)
                    this.filters.carrera_id = this.aula.carrera_id;
                
                if (this.aula.estado)
                    this.filters.estado = this.aula.estado;

                this.listarAulas();  
            },
            eliminarAula(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }
                

                swalAlertConfirm(`¿Seguro que quiere eliminar el aula con codigo <b>${param.id}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/aula/${param.id}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarAulas();
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
            limpiarAula(){
                this.aulas.carrera_id = '';
                this.buscarAula();
            },
            
        },
        mounted() {
            this.listarAulas();
            this.listarCarreras();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            Select2
        }
    }

</script>
