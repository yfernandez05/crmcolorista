<template>
    <main-content>
        <template v-slot:card-header-title>
            Planes de estudio
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarPlanEstudio()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarPlanEstudio()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.planestudio.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>    
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Costo</label>
                    <input type="text" class="form-control" v-model="planestudio.costo" @keyup.enter="buscarPlanEstudio()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Duración de Meses</label>
                    <input type="text" class="form-control" v-model="planestudio.duracion_meses" @keyup.enter="buscarPlanEstudio()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Carrera</label>
                    <select2 :options="carreras" @input="buscarPlanEstudio()" v-model="planestudio.carrera_id"
                        :selectValue="planestudio.carrera_id" placeholder="Seleccione una carrera" keyProperty="carrera_id"
                        textProperty="nombre">
                    </select2>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Ciclo</label>
                    <select2 :options="ciclos" @input="buscarPlanEstudio()" v-model="planestudio.ciclo_id"
                        :selectValue="planestudio.ciclo_id" placeholder="Seleccione una carrera" keyProperty="ciclo_id"
                        textProperty="nombre">
                    </select2>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="planestudio.estado" @change="buscarPlanEstudio()">
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
                            <th class="p-2">Costo</th>
                            <th class="p-2">Duracion de Meses</th>
                            <th class="p-2">Carrera</th>
                            <th class="p-2">Ciclo</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="pde in planestudios" :key="pde.id">
                            <td>
                                <row-actions :rowData="pde" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="pde.id"></td>
                            <td v-text="pde.costo"></td>                            
                            <td v-text="pde.duracion_meses"></td>                            
                            <td v-text="pde.carrera?.nombre"></td>                            
                            <td v-text="pde.ciclo?.nombre"></td>                            
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="pde.isactive ? 'badge-success':'badge-danger'"
                                     v-text="pde.statename">
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
                planestudios: [],
                carreras: [],
                ciclos: [],
                pagination: {},
                filters: {},
                planestudio: {
                    costo: '',
                    duracion_meses: '',
                    carrera_id: '',
                    ciclo_id: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarPlanEstudio() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/planestudio`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.planestudios = response.data.data;
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
            listarCiclos() {
                let vm = this;
                axios.get(`${appApiUrl}/ciclo/select`)
                    .then(function (response) {
                        vm.ciclos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            changePerPage(event) {
                this.filters.page = event.page;
                this.filters.perpage = event.perpage;
                this.listarPlanEstudio();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.planestudio.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarPlanEstudio(event.data);
                    break;
                }
            },
            buscarPlanEstudio() {
                this.filters = {};
                this.filters.page = 1;

                if (this.planestudio.costo.length)
                    this.filters.costo = this.planestudio.costo;
                
                if (this.planestudio.duracion_meses.length)
                    this.filters.duracion_meses = this.planestudio.duracion_meses;
                
                if (this.planestudio.carrera_id.length)
                    this.filters.carrera_id = this.planestudio.carrera_id;
                
                if (this.planestudio.ciclo_id.length)
                    this.filters.ciclo_id = this.planestudio.ciclo_id;
                
                if (this.planestudio.estado)
                    this.filters.estado = this.planestudio.estado;

                this.listarPlanEstudio();  
            },
            eliminarPlanEstudio(param){
                let vm = this;

                if(!planestudio.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el plan de estudio con codigo <b>${param.id}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/planestudio/${param.id}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarPlanEstudio();
                                    } else
                                        errorMessage(result.message, appName);
                                })
                                .catch(function (error) {
                                    hidePreloader();
                                    planestudio(appErrorMessage, appName);
                                    console.log(error);
                                });
                        }
                    });
            },             
            limpiarPlanEstudio(){
                this.planestudio.costo = '';
                this.planestudio.duracion_meses = '';
                this.planestudio.carrera_id = '';
                this.planestudio.ciclo_id = '';
                this.buscarPlanEstudio();
            },
            
        },
        mounted() {
            this.listarPlanEstudio();
            this.listarCarreras();
            this.listarCiclos();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            Select2
        }
    }

</script>
