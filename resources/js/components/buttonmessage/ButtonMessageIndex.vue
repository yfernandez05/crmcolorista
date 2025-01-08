<template>
    <main-content columnClass="col-12">
        <template v-slot:card-header-title>
            Botones
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarbotones()">
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
            <router-link :to="{name:'spa.buttonmessage.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
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
                    <input type="text" class="form-control" v-model="buttonmessage.name" @keyup.enter="buscarbotones()"> 
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                         v-model="buttonmessage.estado" @change="buscarbotones()">
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
                            <th class="p-2 text-nowrap">Nombre</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="btnmgs in buttonmessages" :key="btnmgs.idbutton" >
                            <td>
                                <row-actions :rowData="btnmgs" @rowItemActions="rowItemActions" :activeDelete="false">
                                    <button v-if="authenticatedUser.idrol < 6" type="button" :title="btnmgs.isactive ? 'Desactivar':'Activar'" @click="eliminarbutttons(btnmgs)"
                                            class="btn btn-sm  waves-effect waves-light border-0 mr-1"
                                            :class="btnmgs.isactive ? 'btn-outline-danger':'btn-outline-success'">
                                        <i class="fas fa-lg" :class="btnmgs.isactive ? 'fa-trash':'fa-check-circle'"></i>
                                    </button>
                                </row-actions>
                            </td>
                            <td v-text="btnmgs.idbutton"></td>
                            <td><span class="badge badge-pill badge-lg py-1" v-bind:style="{background: btnmgs.backgroundColor, color:btnmgs.textColor}" v-text="btnmgs.name"></span></td>
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="btnmgs.isactive ? 'badge-success':'badge-danger'"
                                     v-text="btnmgs.statename">
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

    export default {
        data() {
            return {
                buttonmessages: [],
                pagination: {},
                filters: {},
                buttonmessage: {
                    name: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarbuttons() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/buttonmessage`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.buttonmessages = response.data.data;
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
                this.listarbuttons();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }
                        this.$router.push({ name: 'spa.buttonmessage.editar', params: { id: event.data.idbutton } }) 
                    break;
                    /* case 'delete':
                        this.eliminarbutttons(event.data);
                    break; */
                }
            },
            buscarbotones() {
                this.filters = {};
                this.filters.page = 1;

                if (this.buttonmessage.name.length)
                    this.filters.name = this.buttonmessage.name;                
                               
                if (this.buttonmessage.estado)
                    this.filters.estado = this.buttonmessage.estado;

                this.listarbuttons();  
            },
            eliminarbutttons(param){
                let vm = this;

                let optionMessage ='Eliminar';
                if(!param.isactive) optionMessage = 'Restaurar';

                swalAlertConfirm(`¿Seguro que quiere ${optionMessage} el boton <b>${param.name}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/buttonmessage/${param.idbutton}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarbuttons();
                                    }  else if (result.warning){
                                        warningMessage(result.message, appName);
                                        vm.listarbuttons();
                                    } else{
                                        errorMessage(result.message, appName);
                                    }
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
                this.buttonmessages.name = '';
            }

        },
        mounted() {
            this.listarbuttons();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            VDatePicker,
        }
    }

</script>
