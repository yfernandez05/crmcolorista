<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            Etiqueta Telefonica
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="buscarEtiquetatele()">
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
            <router-link :to="{name:'spa.etiquetaTelefonica.registrar'}" class="btn btn-sm btn-success waves-effect waves-light" >
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Etiqueta Telefonica</label>
                    <input type="text" class="form-control" v-model="etiquetatelefonica.etiquetatele" @keyup.enter="buscarTipoAtencion()">
                </div>
                <!-- <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="estado"
                         v-model="etiquetatelefonica.estado" @change="buscarEtiquetatele()">
                        <label class="custom-control-label" for="estado">Incluir Eliminados</label>
                    </div>
                </div> -->
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="p-2">Acciones</th>
                            <th class="p-2">Codigo</th>
                            <th class="p-2 text-nowrap">Estado Atencion</th>
                            <th class="p-2">Color</th>
                            <th class="p-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="etiquetatelefonica in etiquetatelefonicas" :key="etiquetatelefonica.idetiquetatele" >
                            <td>
                                <row-actions :rowData="etiquetatelefonica" @rowItemActions="rowItemActions" :activeDelete="false">
                                    <toggle-button :labels="true" :value="!etiquetatelefonica.isactive" @change="cambiarEstado(etiquetatelefonica)" class="mb-0"
                                        :color="{checked: '#00c292', unchecked: '#e46a76'}">
                                        <template slot="checked">
                                            <i class="fas fa-check fa-lg" title="Activar"></i>
                                        </template>
                                        <template slot="unchecked">
                                            <i class="fas fa-trash fa-lg" title="Eliminar"></i>
                                        </template>
                                    </toggle-button>
                                </row-actions>
                            </td>
                            <td v-text="etiquetatelefonica.idetiquetatele"></td>
                            <td v-text="etiquetatelefonica.etiquetatele"></td>
                            <!-- <td>
                                <span class="badge badge-pill py-1 px-3"
                                    :class="etiquetatelefonica.color"
                                     v-text="etiquetatelefonica.color">
                                </span>
                            </td> -->
                            <td><span class="badge badge-pill badge-lg" v-bind:style="{background: etiquetatelefonica.backgroundColor, color:etiquetatelefonica.textColor}" v-text="etiquetatelefonica.etiquetatele"></span></td>
                            <td >
                                <span class="badge badge-pill py-1 px-3"
                                    :class="etiquetatelefonica.isactive ? 'badge-success':'badge-danger'"
                                     v-text="etiquetatelefonica.statename">
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
    import { ToggleButton } from 'vue-js-toggle-button';

    export default {
        data() {
            return {
                etiquetatelefonicas: [],
                pagination: {},
                filters: {},
                etiquetatelefonica: {
                    etiquetatele: '',
                    //estado: false
                }
            }
        },

        methods: {
            listarEtiquetaTelefonica() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/etiquetaTelefonica`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.etiquetatelefonicas = response.data.data;
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
                this.listarEtiquetaTelefonica();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'edit':
                        if(!event.data.isactive) {
                            warningMessage(appCannotDeleteMessage, appName);
                            break;
                        }
                        this.$router.push({ name: 'spa.etiquetaTelefonica.editar', params: { id: event.data.idetiquetatele } })
                    break;
                    case 'delete':
                        this.eliminarEtiquetaTele(event.data);
                    break;
                }
            },
            buscarEtiquetatele() {
                this.filters = {};
                this.filters.page = 1;

                if (this.etiquetatelefonicas.etiquetatele.length)
                    this.filters.etiquetatele = this.etiquetatelefonicas.etiquetatele;

                if (this.etiquetatelefonicas.estado)
                    this.filters.estado = this.etiquetatelefonicas.estado;

                this.listarEtiquetaTelefonica();
            },
            cambiarEstado(param){
                let vm = this;

                showPreloader();
                axios.delete(`${appApiUrl}/etiquetaTelefonica/${param.idetiquetatele}`)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);
                            vm.listarEtiquetaTelefonica();
                        } else
                            errorMessage(result.message, appName);
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        vm.listarEtiquetaTelefonica();
                        console.log(error);
                    });

            },
            limpiarFiltros(){
                this.tipoatencion.etiquetatele = '';
            }

        },
        mounted() {
            this.listarEtiquetaTelefonica();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            VDatePicker,
            ToggleButton
        }
    }

</script>
