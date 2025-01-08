<script>
//importamos libreria
import {Pie} from "vue-chartjs";


export default {
  extends: Pie,

    props:{
        datos:[],
        titulo:[],
    },

    data(){
        return{
            pieNombre: [],
            pieDatos: [],
            bgColors: [
                'rgba(45, 82, 199, 0.90)',              
                'rgba(246, 136, 102 , 0.90)',
                'rgba(30, 135, 183, 0.90)',
                'rgba(58, 94, 165, 0.90)',
                'rgba(65, 163, 49, 0.90)',
                'rgba(119, 85, 85, 0.90)',
                'rgba(153, 85, 102, 0.90)',
                'rgba(80, 223, 201, 0.8)',
                'rgba(172, 146, 204, 0.8)',
                'rgba(32, 219, 216, 0.8)',
                ],
            bgBorder: [
                'rgba(45, 82, 199, 1)',              
                'rgba(246, 136, 102 , 1)',
                'rgba(30, 135, 183, 1)',
                'rgba(58, 94, 165, 1)',
                'rgba(65, 163, 49, 1)',
                'rgba(119, 85, 85, 1)',
                'rgba(153, 85, 102, 1)',
                'rgba(80, 223, 201, 1)',
                'rgba(172, 146, 204, 1)',
                'rgba(32, 219, 216, 1)',
            ]   
        }
    },

    mounted(){      
        //this.cantidadclientesevento()      
    },

    //metodos
    methods:{
        /* cantidadclientesevento(){
            let vm = this;
            axios.get(`${appApiUrl}/reporte/cantidadclientesevento`)
            .then(function (response){
                let result = response.data; //
                result.forEach(element => {
                    vm.pieDatos.push(element.total);              
                    vm.pieNombre.push(element.nombreevento);   
                    vm.mostrar();             
                 });
            })
            .catch(function (error){
                console.log(error);
            })
        }, */

        mostrar(){
            this.renderChart({
                labels: this.titulo ,
                datasets:[
                    {
                        label: 'GRAFICOS',
                        backgroundColor: this.bgColors,
                        borderColor: this.bgBorder,
                        borderWidth: 2,
                        //data: [21,20,19,18,17,16,12,10,9,2,1]
                        data:  this.datos,
                    }
                ],
            }, {responsive: true, maintainAspectRatio: false ,plugins: {
                    labels: {                    
                    fontColor: ["white", "black","white","white","white","white","white"]
                    } 
                } }) //para que sea responsivo
        },

    },

}
</script>

<style scoped>
 #pie-chart{
     height:265px !important;
     display: block !important;
 }
</style>