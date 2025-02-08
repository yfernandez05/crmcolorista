<template>
    <main-content>
        <template v-slot:card-header-title>
            Contrato
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="contratoBuscar()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarFiltroContrato()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.contrato.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
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
                    <input type="text" class="form-control" v-model="contrato.nombre" @keyup.enter="contratoBuscar()">
                </div>
                
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="contrato.estado" @change="contratoBuscar()">
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
                            <th class="p-2">Alumno</th>
                            <th class="p-2">Matricula</th>
                            <th class="p-2">F. Contrato</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cont in contratos" :key="cont.id">
                            <td>
                                <row-actions :rowData="cont" @rowItemActions="rowItemActions" :activeEdit="false">
                                    <button type="button" title="PDF Contrato" @click="viewPdf(cont.id)"
                                           class="btn btn-sm  waves-effect waves-light border-0 mr-1 btn-outline-info">
                                        <i class="fa-lg fas fa-file-pdf"></i>
                                    </button>
                                </row-actions>
                            </td>
                            <td v-text="cont.id"></td>
                            <td v-text="cont.alumno?.nombrecompleto"></td>                            
                            <td v-text="cont.matricula?.detalle"></td>                            
                            <td v-text="cont.fecharegistro"></td>                            
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="cont.isactive ? 'badge-success':'badge-danger'"
                                     v-text="cont.statename">
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
                contratos: [],
                pagination: {},
                filters: {},
                contrato: {
                    fechacontrato: '',
                    idalumno: '',
                    idcarrera: '',
                }
            }
        },
        
        methods: {
            listarContrato() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/contrato`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.contratos = response.data.data;
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
                this.listarContrato();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.contrato.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarContrato(event.data);
                    break;
                }
            },
            contratoBuscar() {
                this.filters = {};
                this.filters.page = 1;

                if (this.contraro.idalumno.length)
                    this.filters.idalumno = this.idalumno.nombre;

                if (this.contraro.idcarrera.length)
                    this.filters.idcarrera = this.idcarrera.nombre;

                if (this.contraro.estado)
                    this.filters.estado = this.contraro.estado;


                this.listarContrato();  
            },
            aliminarContraro(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el contrato <b>${param.nombre}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/contrato/${param.id}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarContrato();
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
            limpiarFiltroContrato(){
                this.contrato.idalumno = '';
                this.contrato.idcarrera = '';
            },

            viewPdf(data){
                // console.log(data);
                let urlPdf = `${appApiUrl}/contrato/pdfcontrato/${data}`;
                window.open(urlPdf,'_blank')
            }

        },
        mounted() {
            this.listarContrato();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
        }
    }

</script>
