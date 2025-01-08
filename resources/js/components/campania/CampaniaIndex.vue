<template>
    <main-content>
        <template v-slot:card-header-title>
            Campañas
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarCampania()">
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
            <router-link :to="{name:'spa.campania.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>    
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Nombre Campaña</label>
                    <input type="text" class="form-control" v-model="campania.nombrecampania" @keyup.enter="buscarCampania()">
                </div>

                 <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Empresa</label>
                    <select2 :options="cuenta" @input="buscarCampania()" v-model="campania.idcuenta"
                        :selectValue="campania.idcuenta" placeholder="Seleccione una cuenta" keyProperty="idcuenta"
                        textProperty="empresa">
                    </select2>
                </div>
               
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Fecha inicio-fin</label>
                    <v-date-picker v-model="campania.fechainicio" range lang="es" format="DD-MM-YYYY" 
                        confirm :editable="false" placeholder="Seleccione un rango de fecha" @input="buscarCampania()">
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
                            v-model="campania.estado" @change="buscarCampania()">
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
                            <th class="p-2">Nombre Campaña</th>
                            <th class="p-2 text-nowrap">Fecha Inicio</th>
                            <th class="p-2 text-nowrap">Hora Inicio</th>
                            <th class="p-2 text-nowrap">Fecha Fin</th>
                            <th class="p-2 text-nowrap">Hora Fin</th>
                            <th class="p-2 text-nowrap">Empresa</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="campa in campanias" :key="campa.idcampania">
                            <td>
                                <row-actions :rowData="campa" @rowItemActions="rowItemActions">
                                </row-actions>
                            </td>
                            <td v-text="campa.idcampania"></td>
                            <td v-text="campa.nombrecampania"></td>
                            <td class="text-nowrap" v-text="campa.fecharegistro"></td>
                            <td class="text-nowrap" v-text="campa.horaregistro"></td>
                            <td class="text-nowrap" v-text="campa.fechafinalizada"></td>
                            <td class="text-nowrap" v-text="campa.horafinalizada"></td>
                            <td v-text="campa.cuenta.empresa"></td>
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="campa.isactive ? 'badge-success':'badge-danger'"
                                    v-text="campa.statename">
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

    import Select2 from './../../utils/Select2';
    import MainContent from './../../utils/MainContent';
    import PaginationLinks from './../../utils/PaginationLinks';
    import RowActions from './../../utils/RowActions';
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';
    import moment,{ now, relativeTimeThreshold } from 'moment';

export default {

        data() {
            return {
                campanias: [],
                pagination: {},
                filters: {},
                cuenta:[],
                campania: {
                    nombrecampania: '',
                    fechainicio: '',
                    fechafin: '',
                    estado: false,
                    idcuenta:0,
                }
                
            }
        },
        methods: {
            listarCampanias() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/campania`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.campanias = response.data.data;
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
                this.listarCampanias();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }

                    this.$router.push({ name: 'spa.campania.editar', params: { id: event.data.idcampania } }) 
                    break;
                    case 'delete':
                        this.eliminarCampania(event.data);
                    break;
                }
            },
            buscarCampania() {
                this.filters = {};
                this.filters.page = 1;

                if (this.campania.nombrecampania.length)
                    this.filters.nombrecampania = this.campania.nombrecampania;

                if (this.campania.idcuenta.length)
                    this.filters.idcuenta = this.campania.idcuenta;

                if(this.campania.estado)
                    this.filters.estado=this.campania.estado;

                
                if(this.campania.fechainicio.length == 2){                
                    if(this.campania.fechainicio[0] != null)
                        this.filters.fechadesde = this.formatDate(this.campania.fechainicio[0],'DD-MM-YYYY');

                    if(this.campania.fechainicio[1] != null)
                        this.filters.fechahasta = this.formatDate(this.campania.fechainicio[1],'DD-MM-YYYY');
                }

                this.listarCampanias();  
            },
            listarCuentas() {
                let vm = this;
                axios.get(`${appApiUrl}/cuenta/select`)
                    .then(function (response) {
                        vm.cuenta = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            eliminarCampania(param){
                let vm = this;

                if(!param.isactive) {
                    warningMessage(appRecordIsDeletedMessage, appName);
                    return;
                }

                swalAlertConfirm(`¿Seguro que quiere eliminar la Campaña de la empresa <b>${param.nombrecampania}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            
                            showPreloader();
                            axios.delete(`${appApiUrl}/campania/${param.idcampania}`)
                                .then(function (response) {         
                                    hidePreloader();
                                    let result = response.data;

                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarCampanias();
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
                        
            limpiarFiltros(){
                this.campania.nombrecampania = '';
                this.campania.idcuenta =0;
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
            this.listarCampanias();
            this.listarCuentas();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            VDatePicker,
            Select2,
        }
    
}
</script>