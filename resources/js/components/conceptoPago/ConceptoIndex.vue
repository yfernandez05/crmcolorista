<template>
    <main-content>
        <template v-slot:card-header-title>
            Concepto de Pago
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarConcepto()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarConcepto()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.conceptopago.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
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
                    <input type="text" class="form-control" v-model="conceptopago.nombre" @keyup.enter="buscarConcepto()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Descripción</label>
                    <input type="text" class="form-control" v-model="conceptopago.descripcion" @keyup.enter="buscarConcepto()">
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="conceptopago.estado" @change="buscarConcepto()">
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
                        <tr v-for="cps in conceptopagos" :key="cps.id">
                            <td>
                                <row-actions :rowData="cps" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="cps.id"></td>
                            <td v-text="cps.nombre"></td>                            
                            <td v-text="cps.descripcion"></td>                            
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="cps.isactive ? 'badge-success':'badge-danger'"
                                     v-text="cps.statename">
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
                conceptopagos: [],
                pagination: {},
                filters: {},
                conceptopago: {
                    nombre: '',
                    descripcion: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarConcepto() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/conceptopago`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.conceptopagos = response.data.data;
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
                this.listarConcepto();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.conceptopago.editar', params: { id: event.data.id } }) 
                    break;
                    case 'delete':
                        this.eliminarConcepto(event.data);
                    break;
                }
            },
            buscarConcepto() {
                this.filters = {};
                this.filters.page = 1;

                if (this.conceptopago.nombre.length)
                    this.filters.nombre = this.conceptopago.nombre;
                
                if (this.conceptopago.descripcion.length)
                    this.filters.descripcion = this.conceptopago.descripcion;
                
                if (this.conceptopago.estado)
                    this.filters.estado = this.conceptopago.estado;

                this.listarConcepto();  
            },
            eliminarConcepto(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar el concepto de pago <b>${param.nombre}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/conceptopago/${param.id}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarConcepto();
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
            limpiarConcepto(){
                this.conceptopago.nombre = '';
                this.conceptopago.descripcion = '';
                this.buscarConcepto();
            },
            
        },
        mounted() {
            this.listarConcepto();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
        }
    }

</script>
