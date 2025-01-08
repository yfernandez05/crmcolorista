<script>
//importamos librerias 
import {Pie} from "vue-chartjs";
import 'chartjs-plugin-labels'; // Importa el plugin
import { bgColors, bgBorder } from './../../colorreport';

export default {
    extends: Pie,
    
    props:{
        datos:[],
    },

    data(){
        return{
            bgColors,
            bgBorder,
            options: {
                responsive: true,
                maintainAspectRatio: false,  
                events: ["click", "mousemove"],
                plugins: {
                    labels: {
                        render: 'percentage', // Otras opciones: 'label', 'value'
                        fontColor: 'black', // Especifica el color de la fuente
                        shadowColor: 'rgba(5,5,5,1)',
                        precision: 2,
                        outsidePadding: 10, // Ajuste opcional para etiquetas fuera del gráfico
                        textMargin: 6, // Ajuste opcional para margen del texto
                        align: 'start', // Alinea las etiquetas a la izquierda
                        anchor: 'end',
                    }                    
                }       
            },
        }
    },

    //metodos
    methods:{
        mostrar(){

            const { labels, data } = this.datos.reduce((acc, item) => {
                acc.labels.push(item.colegio);
                acc.data.push(item.total);
                return acc;
            }, { labels: [], data: [] });

            this.renderChart({
            labels: labels,
            datasets:[
                {
                    backgroundColor: this.bgBorder,
                    borderColor: this.bgColors,
                    borderWidth: 2,
                    data: data,
                }
            ]},this.options)       //para que sea responsivo
        },

    }
}
</script>

<style scoped>
 #pie-chart{
     height:237px !important;
     display: block !important;
 }
</style>