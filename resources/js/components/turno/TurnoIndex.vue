<template>
    <main-content>
        <template v-slot:card-header-title>
            Turnos
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarTurno()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarTurno()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.turno.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
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
                    <input type="text" class="form-control" v-model="turno.nombre" @keyup.enter="buscarTurno()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Descripción</label>
                    <input type="text" class="form-control" v-model="turno.descripcion" @keyup.enter="buscarTurno()">
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="turno.estado" @change="buscarTurno()">
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
                            <th class="p-2">Descripción</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="trn in turnos" :key="trn.id">
                            <td>
                                <row-actions :rowData="trn" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="trn.id"></td>
                            <td v-text="trn.nombre"></td>                            
                            <td v-text="trn.descripcion"></td>                            
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="trn.isactive ? 'badge-success':'badge-danger'"
                                     v-text="trn.statename">
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
                turnos: [],
                pagination: {},
                filters: {},
                turno: {
                    nombre: '',
                    descripcion: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarTurno() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/turno`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.turnos = response.data.data;
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
                this.listarTurno();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.turno.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarTurno(event.data);
                    break;
                }
            },
            buscarTurno() {
                this.filters = {};
                this.filters.page = 1;

                if (this.turno.nombre.length)
                    this.filters.nombre = this.turno.nombre;
                
                if (this.turno.descripcion.length)
                    this.filters.descripcion = this.turno.descripcion;
                
                if (this.turno.estado)
                    this.filters.estado = this.turno.estado;

                this.listarTurno();  
            },
            eliminarTurno(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el turno <b>${param.nombre}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/turno/${param.id}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarTurno();
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
            limpiarTurno(){
                this.turno.nombre = '';
                this.turno.descripcion = '';
            },
            
        },
        mounted() {
            this.listarTurno();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
        }
    }

</script>
