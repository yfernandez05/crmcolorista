<template>
    <main-content>
        <template v-slot:card-header-title>
            Alumnos
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="alumnosBuscar()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarAlumno()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.alumno.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
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
                    <input type="text" class="form-control" v-model="alumno.nombre" @keyup.enter="alumnosBuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Apellido</label>
                    <input type="text" class="form-control" v-model="alumno.apellido" @keyup.enter="alumnosBuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Correo</label>
                    <input type="text" class="form-control" v-model="alumno.correo" @keyup.enter="alumnosBuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="alumno.estado" @change="alumnosBuscar()">
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
                            <th class="p-2">Correo</th>
                            <th class="p-2">Curso</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="alum in alumnos" :key="alum.id">
                            <td>
                                <row-actions :rowData="alum" @rowItemActions="rowItemActions">
                                    <button type="button" title="Carnet Acceso" @click="donwloadcard(alum.id)"
                                           class="btn btn-sm  waves-effect waves-light border-0 mr-1 btn-outline-warning">
                                        <i class="fas fa-lg fa-address-card"></i>
                                    </button>
                                </row-actions>
                            </td>
                            <td v-text="alum.id"></td>
                            <td v-text="alum.nombre"></td>                            
                            <td v-text="alum.apellido"></td>                            
                            <td v-text="alum.correo"></td>                            
                            <td v-text="alum.curso"></td>                            
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="alum.isactive ? 'badge-success':'badge-danger'"
                                     v-text="alum.statename">
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
                alumnos: [],
                pagination: {},
                filters: {},
                alumno: {
                    nombre: '',
                    apellido: '',
                    correo: '',
                }
            }
        },
        
        methods: {
            listarAlumno() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/alumno`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.alumnos = response.data.data;
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
                this.listarAlumno();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.alumno.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarAlumno(event.data);
                    break;
                }
            },
            alumnosBuscar() {
                this.filters = {};
                this.filters.page = 1;

                if (this.alumno.nombre.length)
                    this.filters.nombre = this.alumno.nombre;
                if (this.alumno.apellido.length)
                    this.filters.apellido = this.alumno.apellido;
                if (this.alumno.correo.length)
                    this.filters.correo = this.alumno.correo;

                if (this.alumno.estado)
                    this.filters.estado = this.alumno.estado;


                this.listarAlumno();  
            },
            eliminarAlumno(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el alumno <b>${param.nombre}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/alumno/${param.id}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarAlumno();
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
            limpiarAlumno(){
                this.alumno.nombre = '';
                this.alumno.apellido = '';
                this.alumno.correo = '';
            },

            donwloadcard(data){
                // console.log(data);
                let urlPdf = `${appApiUrl}/alumno/generatecard/${data}`;
                window.open(urlPdf,'_blank')
            }


        },
        mounted() {
            this.listarAlumno();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
        }
    }

</script>
