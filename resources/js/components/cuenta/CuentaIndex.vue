<template>
    <main-content>
        <template v-slot:card-header-title>
            Cuentas
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarCuenta()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarFiltroscuenta()">
                    Limpiar
                </span>
            </button>
            <router-link :to="{name:'spa.cuenta.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>    
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Empresa</label>
                    <input type="text" class="form-control" v-model="cuenta.empresa" @keyup.enter="buscarCuenta()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Ruc</label>
                    <input type="text" class="form-control" v-model="cuenta.ruc" @keyup.enter="buscarCuenta()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Razón Social</label>
                    <input type="text" class="form-control" v-model="cuenta.razonsocial" @keyup.enter="buscarCuenta()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Fecha Inicio</label>
                    <v-date-picker v-model="cuenta.fecha" range lang="es" format="DD-MM-YYYY" 
                        confirm :editable="false" placeholder="Seleccione un rango de fecha" @input="buscarCuenta()">
                        <template v-slot:footer="{ emit }">
                            <button class="mx-btn" @click="selectToday(emit)">
                                Today
                            </button>
                        </template>                        
                    </v-date-picker>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado" 
                            v-model="cuenta.estado" @change="buscarCuenta()">
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
                            <th class="p-2">Empresa</th>
                            <th class="p-2">Ruc</th>
                            <th class="p-2 text-nowrap">Razon Social</th>
                            <th class="p-2">Contrato</th>
                            <th class="p-2 text-nowrap">Fech. Inicio</th>
                            <th class="p-2">Mensual</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cta in cuentas" :key="cta.idcuenta">
                            <td>
                                <row-actions :rowData="cta" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="cta.idcuenta"></td>
                            <td v-text="cta.empresa"></td>
                            <td v-text="cta.ruc"></td>
                            <td v-text="cta.razonsocial"></td>
                            <td v-text="cta.contrato"></td>
                            <td v-text="cta.fecharegistro"></td>
                            <td v-text="cta.mensual"></td>
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="cta.isactive ? 'badge-success':'badge-danger'"
                                     v-text="cta.statename">
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
                cuentas: [],
                pagination: {},
                filters: {},
                cuenta: {
                    empresa: '',
                    ruc: '',
                    razonsocial: '',
                    fecha: '',
                    estado: false
                }
            }
        },
        
        methods: {
            listarCuenta() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/cuenta`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.cuentas = response.data.data;
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
                this.listarCuenta();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                        this.$router.push({ name: 'spa.cuenta.editar', params: { id: event.data.idcuenta } }) 
                    break;
                    case 'delete':
                        this.eliminarRol(event.data);
                    break;
                }
            },
            buscarCuenta() {
                this.filters = {};
                this.filters.page = 1;

                if (this.cuenta.empresa.length)
                    this.filters.empresa = this.cuenta.empresa;
                
                if (this.cuenta.ruc.length)
                    this.filters.ruc = `%${this.cuenta.ruc}`;
                
                if (this.cuenta.razonsocial.length)
                    this.filters.razonsocial = this.cuenta.razonsocial;
                
                if (this.cuenta.estado)
                    this.filters.estado = this.cuenta.estado;
                
                if(this.cuenta.fecha.length == 2){                
                    if(this.cuenta.fecha[0] != null)
                        this.filters.fechadesde = this.formatDate(this.cuenta.fecha[0],'DD-MM-YYYY');

                    if(this.cuenta.fecha[1] != null)
                        this.filters.fechahasta = this.formatDate(this.cuenta.fecha[1],'DD-MM-YYYY');
                }

                this.listarCuenta();  
            },
            eliminarRol(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar la cuenta de la empresa <b>${param.empresa}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/cuenta/${param.idcuenta}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarCuenta();
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
            limpiarFiltroscuenta(){
                this.cuenta.empresa = '';
                this.cuenta.ruc = '';
                this.cuenta.razonsocial = '';
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            selectToday(emit){
                emit([ new Date(), new Date() ]);
            },
        },
        mounted() {
            this.listarCuenta();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            VDatePicker,
        }
    }

</script>
