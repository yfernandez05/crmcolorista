<template>
    <main-content columnClass="col-12 col-md-11 col-lg-12">
        <template v-slot:card-header-title>
            Log Error
        </template>
        <template v-slot:card-header-actions>
            
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4 col-xl-3 ">
                        <label>Fecha</label>
                        <v-date-picker v-model="logerrores.dateoccurred" range lang="es" format="DD-MM-YYYY" confirm
                            :editable="false" placeholder="Seleccione un rango de fecha" @input="buscarlogerror()">
                            <template v-slot:footer="{ emit }">
                                <button class="mx-btn" @click="selectToday(emit)">
                                    Today
                                </button>
                            </template>
                        </v-date-picker>
                    </div>

            </div>

            

            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                    <thead>
                        <tr>

                            <th class="p-2">Codigo</th>
                            <th class="p-2">classname</th>
                            <th class="p-2">methodname</th>
                            <th class="p-2">errormessage</th>
                            <th class="p-2">dateoccurred</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="itemlog in logerror" :key="itemlog.id">
                            <td v-text="itemlog.id"></td>
                            <td v-text="itemlog.classname"></td>
                            <td v-text="itemlog.methodname"></td>
                            <td v-text="itemlog.errormessage"></td>
                            <td v-text="itemlog.dateoccurred"></td>
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
                logerror: [],
                pagination: {},
                filters: {},
                logerrores: {
                    dateoccurred:'',
                }
            }
        },
        
        methods: {
            listarlogerror() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/logerror`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.logerror = response.data.data;
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
                this.listarlogerror();
            },
            rowItemActions(event) {
                // switch (event.action) {
                //     case 'edit':
                //        this.$router.push({ name: 'spa.rol.editar', params: { id: event.data.idrol } }) 
                //     break;
                //     case 'delete':
                //        this.eliminarRol(event.data);
                //        break;
                // }
            },
             buscarlogerror() {
                this.addFilterslogerror();
                this.listarlogerror();
            },
             formatDate(value, fmt = 'D MMM YYYY') {
                return (value == null) ?
                    '' :
                    moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            selectToday(emit) {
                emit([new Date(), new Date()]);
            },
            addFilterslogerror() {
                this.filters = {};
                this.filters.page = 1;

                if (this.logerrores.dateoccurred.length == 2) {
                    if (this.logerrores.dateoccurred[0] != null)
                        this.filters.fechadesde = this.formatDate(this.logerrores.dateoccurred[0], 'DD-MM-YYYY');

                    if (this.logerrores.dateoccurred[1] != null)
                        this.filters.fechahasta = this.formatDate(this.logerrores.dateoccurred[1], 'DD-MM-YYYY');
                }
            },
           
              
        },
        mounted() {
            this.listarlogerror();
        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            VDatePicker,
        }
    }

</script>
