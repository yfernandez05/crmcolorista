<template>
    <main-content>
        <template v-slot:card-header-title>
            Cursos
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarCursos()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarCursos()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.curso.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
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
                    <input type="text" class="form-control" v-model="curso.nombre" @keyup.enter="buscarCursos()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Descripción</label>
                    <input type="text" class="form-control" v-model="curso.descripcion" @keyup.enter="buscarCursos()">
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="curso.estado" @change="buscarCursos()">
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
                            <th class="p-2">Duración Meses</th>
                            <th class="p-2">Descripción</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="crs in cursos" :key="crs.id">
                            <td>
                                <row-actions :rowData="crs" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="crs.id"></td>
                            <td v-text="crs.nombre"></td>                            
                            <td v-text="crs.duracion_meses"></td>                            
                            <td v-text="crs.descripcion"></td>                            
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="crs.isactive ? 'badge-success':'badge-danger'"
                                     v-text="crs.statename">
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
                cursos: [],
                pagination: {},
                filters: {},
                curso: {
                    nombre: '',
                    descripcion: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarCursos() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/curso`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.cursos = response.data.data;
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
                this.listarCursos();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.curso.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarCurso(event.data);
                    break;
                }
            },
            buscarCursos() {
                this.filters = {};
                this.filters.page = 1;

                if (this.curso.nombre.length)
                    this.filters.nombre = this.curso.nombre;
                
                if (this.curso.descripcion.length)
                    this.filters.descripcion = this.curso.descripcion;
                
                if (this.curso.estado)
                    this.filters.estado = this.curso.estado;

                this.listarCursos();  
            },
            eliminarCurso(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el curso <b>${param.nombre}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/curso/${param.id}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarCursos();
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
            limpiarCursos(){
                this.curso.nombre = '';
                this.curso.descripcion = '';
                this.buscarCursos();
            },
            
        },
        mounted() {
            this.listarCursos();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
        }
    }

</script>
