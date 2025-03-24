<template>
    <main-content>
        <template v-slot:card-header-title>
            Cursos Asignados Docente
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarDocente()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarDocente()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.docente.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>    
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Docente</label>
                    <select2 :options="userdocentes" @input="buscarDocente()" v-model="docente.usuario_id"
                        :selectValue="docente.usuario_id" placeholder="Seleccione un docente" keyProperty="id"
                        textProperty="name">
                    </select2>
                </div>
               
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="docente.estado" @change="buscarDocente()">
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
                            <th class="p-2">Docente</th>
                            <th class="p-2">cursos</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="doce in docentes" :key="doce.id">
                            <td>
                                <row-actions :rowData="doce" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="doce.id"></td>                        
                            <td v-text="doce.user?.nombrecompleto"></td>                          
                            <td>
                                <ul class="list-unstyled m-0" style="display: flex; flex-wrap: wrap;">
                                    <li v-for="detalle in doce.detalles" :key="detalle.id">
                                        <span class="badge badge-pill py-1 px-3 badge-info m-1" v-text="detalle.curso?.nombre"></span>
                                    </li>
                                </ul>
                            </td>                         
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="doce.isactive ? 'badge-success':'badge-danger'"
                                     v-text="doce.statename">
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
                docentes: [],
                userdocentes: [],
                pagination: {},
                filters: {},
                docente: {
                    usuario_id: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarDocenteCursos() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/docente`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.docentes = response.data.data;
                        vm.pagination = response.data;
                        delete vm.pagination.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        console.log(error);
                    });
            },
            listarUserDocente() {
                let vm = this;
                axios.get(`${appApiUrl}/user/selectdocente`)
                    .then(function (response) {
                        vm.userdocentes = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            changePerPage(event) {
                this.filters.page = event.page;
                this.filters.perpage = event.perpage;
                this.listarDocenteCursos();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.docente.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarDocentes(event.data);
                    break;
                }
            },
            buscarDocente() {
                this.filters = {};
                this.filters.page = 1;
                
                if (this.docente.usuario_id.length)
                    this.filters.usuario_id = this.docente.usuario_id;
                
                if (this.docente.estado)
                    this.filters.estado = this.docente.estado;

                this.listarDocenteCursos();  
            },
            eliminarDocentes(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el los cursos asignados al docente con codigo <b>${param.users.name}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/docente/${param.id}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarDocenteCursos();
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
            limpiarDocente(){
                this.docente.usuario_id = '';
                this.buscarDocente();
            },
            
        },
        mounted() {
            this.listarDocenteCursos();
            this.listarUserDocente();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            Select2
        }
    }

</script>
