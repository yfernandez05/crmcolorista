<template>
    <main-content>
        <template v-slot:card-header-title>
            Stands
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarEvento()">
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
            <router-link :to="{name:'spa.evento.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>    
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Nombre Stand</label>
                    <input type="text" class="form-control" v-model="evento.nombreevento" @keyup.enter="buscarEvento()">
                </div>

                 <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-1">Campaña</label>
                    <select2 :options="campanias" @input="buscarEvento()" v-model="evento.idcampania"
                        :selectValue="evento.idcampania" placeholder="Seleccione una campaña" keyProperty="idcampania"
                        textProperty="nombrecampania">
                    </select2>
                </div>
               
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Fecha inicio-fin</label>
                    <v-date-picker v-model="evento.fechainicio" range lang="es" format="DD-MM-YYYY" 
                        confirm :editable="false" placeholder="Seleccione un rango de fecha" @input="buscarEvento()">
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
                            v-model="evento.estado" @change="buscarEvento()">
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
                            <th class="p-2">Nombre Stand</th>
                            <th class="p-2 text-nowrap">Campaña</th>
                            <th class="p-2 text-nowrap">Fecha Inicio</th>
                            <th class="p-2 text-nowrap">Hora Inicio</th>
                            <!-- <th class="p-2 text-nowrap">Fecha Fin</th>
                            <th class="p-2 text-nowrap">Hora Fin</th> -->
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="event in eventos" :key="event.idevento">
                            <td>
                                <row-actions :rowData="event" @rowItemActions="rowItemActions" :activeDelete="false">
                                     <button type="button" :title="event.isactive ? 'Desactivar':'Activar'" @click="eliminarEvento(event)"
                                            class="btn btn-sm  waves-effect waves-light border-0 mr-1"
                                            :class="event.isactive ? 'btn-outline-danger':'btn-outline-success'">
                                        <i class="fas fa-lg" :class="event.isactive ? 'fa-trash':'fa-check-circle'"></i>
                                    </button>
                                </row-actions>
                            </td>
                            <td v-text="event.idevento"></td>
                            <td class="text-nowrap" v-text="event.nombreevento"></td>
                            <td class="text-nowrap" v-text="event.campania.nombrecampania"></td>
                            <td class="text-nowrap" v-text="event.fecharegistro"></td>
                            <td class="text-nowrap" v-text="event.horaregistro"></td>
                            <!-- <td class="text-nowrap" v-text="event.fechafinalizada"></td>
                            <td class="text-nowrap" v-text="event.horafinalizada"></td> -->
                            <td >
                                <span class="badge badge-pill py-1 px-3" 
                                    :class="event.isactive ? 'badge-success':'badge-danger'"
                                    v-text="event.statename">
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
//importamos librerias y componentes
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
            eventos: [],
            pagination: {},
            filters: {},
            campanias:[],
            evento: {
                nombreevento: '',
                fechainicio: '',
                fechafin: '',
                estado: false,
                idcampania:0,
            }
            
        }
    },

    //metodos
    methods: {
        listarEventos() {
            showPreloader();
            let vm = this;
            axios.get(`${appApiUrl}/evento`, {params: this.filters})
            .then(function (response) {
                hidePreloader();
                vm.eventos = response.data.data;
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
            this.listarEventos();
        },
        rowItemActions(event) {
            switch (event.action) {
                case 'edit':
                    if(!event.data.isactive) {
                        warningMessage(appCannotDeleteMessage, appName);
                        break;
                    }

                this.$router.push({ name: 'spa.evento.editar', params: { id: event.data.idevento } }) 
                break;
                case 'delete':
                    this.eliminarEvento(event.data);
                break;
            }
        },
        buscarEvento() {
            this.filters = {};
            this.filters.page = 1;

            if (this.evento.nombreevento.length)
                this.filters.nombreevento = this.evento.nombreevento;

            if (this.evento.idcampania.length)
                this.filters.idcampania = this.evento.idcampania;

            if(this.evento.estado)
                this.filters.estado=this.evento.estado;

            
            if(this.evento.fechainicio.length == 2){                
                if(this.evento.fechainicio[0] != null)
                    this.filters.fechadesde = this.formatDate(this.evento.fechainicio[0],'DD-MM-YYYY');

                if(this.evento.fechainicio[1] != null)
                    this.filters.fechahasta = this.formatDate(this.evento.fechainicio[1],'DD-MM-YYYY');
            }

            this.listarEventos();  
        },
        listarCampanias() {
            let vm = this;
            axios.get(`${appApiUrl}/campania/select`)
                .then(function (response) {
                    vm.campanias = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
        },
        eliminarEvento(param){
            let vm = this;

           /*  if(!param.isactive) {
                warningMessage(appRecordIsDeletedMessage, appName);
                return;
            } */

            let optionMessage ='Eliminar';
            if(!param.isactive) optionMessage = 'Activar';

            swalAlertConfirm(`¿Seguro que quiere ${optionMessage} el stand <b>${param.nombreevento}</b> de la campaña <b>${param.campania.nombrecampania}</b>?`, appName)
                .then(function(optionSelected){
                    if(optionSelected.value){
                        
                        showPreloader();
                        axios.delete(`${appApiUrl}/evento/${param.idevento}`)
                            .then(function (response) {         
                                hidePreloader();
                                let result = response.data;

                                if (result.status) {
                                    successMessage(result.message, appName);
                                    vm.listarEventos();
                                } else if (result.warning){
                                    warningMessage(result.message, appName);
                                    vm.listarEventos();
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
            this.evento.nombreevento = '';
            this.evento.idcampania =0;
            this.buscarEvento();
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

    //iniciamos los metodos
    mounted() {
        this.listarEventos();
        this.listarCampanias();
    },

    //iniciamos los componentes
    components: {
        MainContent,
        PaginationLinks,
        RowActions,
        VDatePicker,
        Select2,
    }
    
}
</script>