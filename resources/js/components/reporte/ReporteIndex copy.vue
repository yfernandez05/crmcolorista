<template>
    <div>
        <!-- contenedor principal -->
        <main-content columnClass="col-12">
            <!-- titulo -->
            <template v-slot:card-header-title>
                Reportes 
            </template>
            <!-- acciones head -->
            <template v-slot:card-header-actions>
                <div class="col-12 d-flex align-items-center">
                    <h4 class="text-white m-0 mr-3 font-weight-bold" v-text="titleReporte"></h4>
                    <button class="btn btn-sm btn-info waves-effect waves-light" @click.prevent="printme">
                        <i class="fas fa-file"></i>
                        <span class="d-none d-sm-inline-block">
                            Generar PDF
                        </span>
                    </button>
                </div>                          
            </template>
            <!-- Contenido reporte  -->
            <template v-slot:card-body-main>
                <div class="form-row">
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label class="mb-1">TOTAL PROSPECTOS</label><br>                       
                        <span class="form-control form-control-md d-block text-truncate" v-text="totalprospectos"></span>
                    </div> 
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label class="mb-1">Sedes</label>
                        <select2 :options="sedes" @input="buscarSedes()" v-model="reporte.codsede" 
                            :selectValue="reporte.codsede" placeholder="Seleccione una Sede"
                            textProperty="sedesalumno">
                        </select2>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label class="mb-1">Campaña</label>
                        <select2 :options="campanias" @input="buscarEvento()" v-model="reporte.idcampania" 
                            :selectValue="reporte.idcampania" placeholder="Seleccione una campaña"
                            keyProperty="idcampania" textProperty="nombrecampania" id="campaniaChart">
                        </select2>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label class="mb-1">Evento</label>
                        <select2 :options="eventos" @input="buscarReport()" v-model="reporte.idevento" 
                            :selectValue="reporte.idevento" placeholder="Seleccione un evento"
                            keyProperty="idevento" textProperty="nombreevento" id="eventoChart">
                        </select2>
                    </div>
                </div>  
                <div class="form-row border_report">      
                    <!-- chartr lienal clientes registros -->
                    <div class="form-group m-0 pt-2 pb-5 px-2 col-12 ">
                        <label class="title_report">Leads registrados</label>
                        <div class="d-flex align-items-center">
                            <div class="col-12">
                                <chart-line-cliente ref="chartlineCliente" :datos="reporte.clientedata" :titulo="reporte.clientetitle"></chart-line-cliente>
                            </div>                             
                        </div>                                       
                    </div>
                    <!-- chart pie x eventos -->
                    <div class="form-group m-0 pt-2 pb-5 px-2 col-12 col-sm-12 ">
                        <label class="title_report">Eventos</label>
                        <div class="movil-flex  d-flex align-items-center">
                            <div class="table-responsive p-0 col-5">
                                <table class="table table-sm table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="p-2">Eventos</th>
                                            <th class="p-2">Cant. Registros</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="event in reporte.eventos" :key="event.idevento">
                                            <td v-text="event.nombreevento"></td>
                                            <td v-text="event.total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>  
                            <div class="col-7">
                                <chart-pie-evento ref="chartPieevento" :datos="reporte.eventoclientesdata" :titulo="reporte.eventoclientestitle"></chart-pie-evento>
                            </div>                             
                        </div>                        
                    </div>
                    <!-- chart pie x colegios -->
                    <div class="form-group m-0 pt-2 pb-5 px-2 col-12 col-sm-12 ">
                        <label class="title_report">Grados</label>
                        <div class="movil-flex  d-flex align-items-center">
                            <div class="table-responsive p-0 col-5">
                                <table class="table table-sm table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="p-2">Grados</th>
                                            <th class="p-2">Cant. Registros</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="grad in reporte.grados" :key="grad.idevento">
                                            <td v-text="grad.grado"></td>
                                            <td v-text="grad.total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>  
                            <div class="col-7">
                                <chart-pie-grado ref="ChartPieGrado" :datos="reporte.gradosdata" :titulo="reporte.gradostitle"></chart-pie-grado>
                            </div>                             
                        </div>                        
                    </div>
                    <!-- chart pie procedencia leads -->
                    <div class="form-group m-0 pt-2 pb-5 px-2 col-12 col-sm-6 ">
                        <label class="title_report">Procedencia Leads</label>
                        <div class="movil-flex  d-flex align-items-center">
                            <div class="table-responsive p-0 col-5">
                                <table class="table table-sm table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="p-2">Fuente Canal</th>
                                            <th class="p-2">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="proc in reporte.procedencias" :key="proc.procedencia">
                                            <td v-text="proc.procedencia"></td>
                                            <td v-text="proc.total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>  
                            <div class="col-7">
                                <chart-pie-procedencia ref="chartPieProcedencia" :datos="reporte.procedenciadata" :titulo="reporte.procedenciatitle"></chart-pie-procedencia>
                            </div>                             
                        </div>                        
                    </div>
                    <!-- chart pie asistencias -->
                    <div class="form-group m-0 pt-2 pb-5 px-2 col-12 col-sm-6 ">
                        <label class="title_report">¿Cuantas Asistieron?</label>
                        <div class="movil-flex d-flex align-items-center">
                            <div class="table-responsive p-0 col-5">
                                <table class="table table-sm table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="p-2">Asistencia</th>
                                            <th class="p-2">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="asist in reporte.asistencias" :key="asist.asistencia">
                                            <td v-text="asist.asistencianame"></td>
                                            <td v-text="asist.total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>  
                            <div class="col-7">
                                <chart-pie-asistencia ref="chartPieAsistencia" :datos="reporte.asistenciasdata" :titulo="reporte.asistenciastitle"></chart-pie-asistencia>
                            </div>                             
                        </div>                                       
                    </div>                    
                    <!-- tabla registros x carrera -->
                    <div class="form-group m-0 pt-2 pb-5 px-2 col-12 col-lg-4">
                        <label class="title_report">Registros por Carreras</label>
                        <div class="d-flex align-items-center">
                            <div class="table-responsive p-0 col-12" style="height: 500px;">
                                <table class="table table-sm table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="p-2">Carrera</th>
                                            <th class="p-2">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="carre in reporte.clientecarreras" :key="carre.carrera">
                                            <td v-text="carre.carrera"></td>
                                            <td v-text="carre.total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                           
                        </div>                                       
                    </div>
                    <!-- tabla registros x Edad -->
                    <div class="form-group m-0 pt-2 pb-5 px-2 col-12 col-lg-4">
                        <label class="title_report">Registros por Edad</label>
                        <div class="d-flex align-items-center">
                            <div class="table-responsive p-0 col-12" style="height: 500px;">
                                <table class="table table-sm table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="p-2">Edad</th>
                                            <th class="p-2">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="edad in reporte.clienteedades" :key="edad.carrera">
                                            <td v-text="edad.edad"></td>
                                            <td v-text="edad.total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                           
                        </div>                                       
                    </div>
                    <!-- tabla registros x Institución -->
                    <div class="form-group m-0 pt-2 pb-5 px-2 col-12 col-lg-4">
                        <label class="title_report">Registros por Institución</label>
                        <div class="d-flex align-items-center">
                            <div class="table-responsive p-0 col-12" style="height: 500px;">
                                <table class="table table-sm table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="p-2">institucion</th>
                                            <th class="p-2">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="insti in reporte.clienteinstituciones" :key="insti.carrera">
                                            <td v-if="insti.colegio" class="ui-max-w-300 text-truncate">{{insti.colegio}}</td><td v-else> SIN DEFINIR </td>
                                            <td v-text="insti.total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                           
                        </div>                                       
                    </div>                  
                </div>
            </template>
        </main-content>
    </div>   
</template>

<script>
//importamos librerias y utilerias
import MainContent from '../../utils/MainContent';
import Select2 from './../../utils/Select2';
import chartGroup from './../../utils/ChartGroup';
import ChartPieCampania from './_ChartpieCampania';
import ChartPieEvento from './_ChartpieEventos';
import ChartPieAsistencia from './_ChartpieAsistencia';
import ChartPieProcedencia from './_ChartpieProcedencia';
import ChartPieGrado from './_ChartpieGrado';
import ChartLineCliente from './_ChartlineCliente';

export default {
    data() {
        return {
            reporte:{
                idcampania: 0,
                idevento: 0,

                eventoclientesdata:[],
                eventoclientestitle:[],
                eventos:[],

                gradosdata:[],
                gradostitle:[],
                grados:[],

                asistenciasdata:[],
                asistenciastitle:[],
                asistencias:[],

                procedenciadata:[],
                procedenciatitle:[],
                procedencias:[],

                clientedata:[],
                clientetitle:[],
                clientes:[],

                clientecarreras:[],
                clienteedades:[],
                clienteinstituciones:[],
            },
            totalprospectos:'Seleccione una campaña',
            campanias:[],
            titleReporte:'',
            idevento: 0,
            eventos:[],
            sedes:[],
            filters:[],
            
        }
    },

    //metodos
    methods:{

        listarCampania(){
            showPreloader();
            let vm = this;
            axios.get(`${appApiUrl}/campania/select`)
            .then(function (response){
                hidePreloader();
                vm.campanias = response.data;
            })
            .catch(function (error) {
                hidePreloader();
                console.log(error);
            })
        },

        buscarEvento(){
            this.reporte.idevento = 0; 
            this.eventos = [];

            let vm = this;
            axios.get(`${appApiUrl}/evento/getevento`, {params: {idcampania: this.reporte.idcampania}})
            .then(function (response) {

                if (response.data == null || response.data == '') {
                    warningMessage(`Esta campaña no tiene ningun evento`, appName);
                }
                                
                vm.eventos = response.data;
                vm.buscarReport();
            })
            .catch(function (error) {
                errorMessage(appErrorMessage, appName);
                console.log(error);
            })
        },

        /* listarEventos(){
            let vm = this;
            axios.get(`${appApiUrl}/evento`)
            .then(function (response){
                vm.eventos = response.data.data;
            })
            .catch(function (error) {
                console.log(error);
            })
        }, */

        cantidadclientesevento(){
            let vm = this;
            this.limpiarDataRepor();
            axios.get(`${appApiUrl}/reporte/cantidadclientesevento`,{params: {idevento:this.reporte.idevento, idcampania: this.reporte.idcampania}})
            .then(function (response){
                vm.renderizarReportepieevento(); 
                let result = response.data; //
                vm.reporte.eventos = result;
                result.forEach(element => {
                    vm.reporte.eventoclientesdata.push(element.total);              
                    vm.reporte.eventoclientestitle.push(element.nombreevento);   
                    vm.renderizarReportepieevento();            
                 });
            })
            .catch(function (error){
                console.log(error);
            })
        },
        renderizarReportepieevento(){
            this.$refs.chartPieevento.mostrar();
        },

        procedencia(){
            let vm = this;
            this.limpiarDataRepor();
            axios.get(`${appApiUrl}/reporte/cantidadpautas`,{params: {idevento:this.reporte.idevento, idcampania: this.reporte.idcampania}})
            .then(function (response){
                vm.renderizarReportepieprocedncia();
                let result = response.data; //
                vm.reporte.procedencias = result;
                result.forEach(element => {
                    vm.reporte.procedenciadata.push(element.total);              
                    vm.reporte.procedenciatitle.push(element.procedencia);   
                    vm.renderizarReportepieprocedncia();             
                });
            })
            .catch(function (error){
                console.log(error);
            })            
        }, 
        renderizarReportepieprocedncia(){
            this.$refs.chartPieProcedencia.mostrar();
        },

        asistencia(){
            this.limpiarDataRepor();
            let vm = this;
            axios.get(`${appApiUrl}/reporte/asistenciasevento`,{params: {idevento:this.reporte.idevento, idcampania: this.reporte.idcampania}})
            .then(function (response){
                    vm.renderizarReporteevent();                     
                    let result = response.data; //  
                    vm.reporte.asistencias = result;
                                      
                    result.forEach(element => {
                        vm.reporte.asistenciasdata.push(element.total);              
                        vm.reporte.asistenciastitle.push(element.asistencianame); 
                        //console.log(vm.reporte.asistenciasdata)     
                        vm.renderizarReporteevent();         
                    });
            })
            .catch(function (error){
                console.log(error);
            })            
        },
        renderizarReporteevent(){
            this.$refs.chartPieAsistencia.mostrar();
        },

        grados(){
            this.limpiarDataRepor();
            let vm = this;
            axios.get(`${appApiUrl}/reporte/clientegrados`,{params: {idevento:this.reporte.idevento, idcampania: this.reporte.idcampania}})
            .then(function (response){
                    vm.renderizarReportepiegrado();                     
                    let result = response.data; //  
                    vm.reporte.grados = result;
                                      
                    result.forEach(element => {
                        vm.reporte.gradosdata.push(element.total);              
                        vm.reporte.gradostitle.push(element.grado); 
                        //console.log(vm.reporte.asistenciasdata)     
                        vm.renderizarReportepiegrado();         
                    });
            })
            .catch(function (error){
                console.log(error);
            }) 
        },
        renderizarReportepiegrado(){
            this.$refs.ChartPieGrado.mostrar();
        },
        
        registrosClientes(){
            showPreloader();
            let vm = this;
            this.limpiarDataRepor();
            axios.get(`${appApiUrl}/reporte/registroclientes`,{params: {idevento:this.reporte.idevento, idcampania: this.reporte.idcampania}})
            .then(function (response){
                hidePreloader();
                vm.renderizarReporteline();                     
                let result = response.data; //  
                vm.reporte.clientes = result;
                vm.totalprospectos = result[0].cantidadprospectos;
                                    
                result.forEach(element => {            
                    vm.reporte.clientedata.push(element.total);              
                    vm.reporte.clientetitle.push(element.fechaGeneral);    
                    vm.renderizarReporteline();         
                });
            })
            .catch(function (error){
                hidePreloader();
                console.log(error);
            })            
        },
        renderizarReporteline(){
            this.$refs.chartlineCliente.mostrar();
        },        

        listarporcarreras(){
            let vm = this;
            axios.get(`${appApiUrl}/reporte/clienteCarrera`,{params: {idevento:this.reporte.idevento, idcampania: this.reporte.idcampania}})
            .then(function (response){                                     
                let result = response.data; //  
                vm.reporte.clientecarreras = result;
            })
            .catch(function (error){
                console.log(error);
            }) 
        },

        listarporedades(){
            let vm = this;
            axios.get(`${appApiUrl}/reporte/clienteedad`,{params: {idevento:this.reporte.idevento, idcampania: this.reporte.idcampania}})
            .then(function (response){                                     
                let result = response.data; //  
                vm.reporte.clienteedades = result;
            })
            .catch(function (error){
                console.log(error);
            }) 
        },

        listarporinstituciones(){
            let vm = this;
            axios.get(`${appApiUrl}/reporte/clienteinstitucion`,{params: {idevento:this.reporte.idevento, idcampania: this.reporte.idcampania}})
            .then(function (response){                                     
                let result = response.data; //  
                vm.reporte.clienteinstituciones = result;
            })
            .catch(function (error){
                console.log(error);
            }) 
        },

        listarSede(){
                let vm = this;
                axios.get(`${appApiUrl}/cliente/sedesUni`)
                .then(function (response) {
                    vm.sedes = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

        buscarReport(){
            /* this.filters = {}; //indicamos el filtro como array vacio

            if (this.reporte.idcampania.length)
                this.filters.idevento = this.reporte.idcampania; */
            if(this.reporte.campanias == null)
                this.totalprospectos = 'Seleccione una campaña';
            this.registrosClientes();
            this.grados();
            this.cantidadclientesevento();                
            this.procedencia();    
            this.asistencia();
            this.listarporcarreras();    
            this.listarporedades();    
            this.listarporinstituciones();
        },

        limpiarDataRepor(){
            this.reporte.clientedata.splice(0, this.reporte.clientedata.length);
            this.reporte.clientetitle.splice(0, this.reporte.clientetitle.length);

            this.reporte.eventoclientesdata.splice(0, this.reporte.eventoclientesdata.length);
            this.reporte.eventoclientestitle.splice(0, this.reporte.eventoclientestitle.length);

            this.reporte.asistenciasdata.splice(0, this.reporte.asistenciasdata.length);
            this.reporte.asistenciastitle.splice(0, this.reporte.asistenciastitle.length);

            this.reporte.procedenciadata.splice(0, this.reporte.procedenciadata.length);
            this.reporte.procedenciatitle.splice(0, this.reporte.procedenciatitle.length);

            this.reporte.gradosdata.splice(0, this.reporte.gradosdata.length);
            this.reporte.gradostitle.splice(0, this.reporte.gradostitle.length);
        },

        printme(){
            window.print();
        },

    },
    mounted(){
        let vm = this;  
        var $select = $("#campaniaChart").change(function() {
        // obtengo el value
        var value = $(this).val();
        // obtengo el texto segun el value
        var text = $select.find('option[value=' + value + ']').text();

        value == null ? vm.titleReporte = 'Registros Totales' : vm.titleReporte = text; 
       
        //console.log(vm.titleReporte)
        // imprime el seleccionado
        //console.log(value, text);
      });
    },

    //iniciamos metodos
    created(){
        this.listarCampania();
        this.listarSede();   
        //this.listarEventos();
        //this.cantidadclientesevento();
        //this.procedencia();
        //this.asistencia();
        this.registrosClientes();
        //this.listarporcarreras();
        //this.listarporedades();
        //this.listarporinstituciones();
    },

    //iniciamos componentes
    components:{
        MainContent,
        Select2,
        chartGroup,
        ChartPieCampania,
        ChartPieEvento,
        ChartPieAsistencia,
        ChartPieProcedencia,
        ChartLineCliente,
        ChartPieGrado
    },
}
</script>

<style scoped>
.movil-flex{
    display: flex !important;
    flex-wrap: wrap;
} 
</style>