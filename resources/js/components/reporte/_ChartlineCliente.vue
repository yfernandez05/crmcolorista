<script>
//importamos librerias
import { Line } from 'vue-chartjs';
import { bgColors, bgBorder } from './../../colorreport';


export default {
    extends: Line,

    props:{
       /* chartId:{ //para agregar un id espesifico a grafici
        type: String,
        default: "idGrafico",
      },  */ 
      datos:[],
    },

    data() {
        return {
            options: {
                scales: {
                    yAxes: [{
                        type: 'linear',
                        ticks: {
                            min: 0,
                            //stepSize: 50
                            callback: function(value) { return Number.isInteger(value) ? value : ''; }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Cantidad'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Fechas / Hora'
                        }
                    }],
                },
                responsive: true,
                maintainAspectRatio: false,  
                events: ["click", "mousemove"],
                plugins: {
                    labels: {
                        render: 'value' , //'label' 'percentage' precision: 2
                        fontColor: ["white", "black"]
                    } 
                },      
            },
        } 
    },

    //metodos
    methods:{
        mostrar(){

            // Función para convertir 'dd-MM HH:mm' a un objeto con día, mes y hora
            function parseDateComponents(dateStr) {
                const [day, month, hour] = dateStr.split(/[-\s:]/).map(Number);
                return { day, month, hour };
            }

            // Función de comparación de fechas personalizada
            function compareDates(dateA, dateB) {
                if (dateA.month !== dateB.month) return dateA.month - dateB.month;
                if (dateA.day !== dateB.day) return dateA.day - dateB.day;
                return dateA.hour - dateB.hour;
            }

            const allDatesSet = new Set();
            this.datos.forEach(item => {
                item.data.forEach(d => allDatesSet.add(d.fechaGeneral));
            });

            const allDates = Array.from(allDatesSet)
            .map(dateStr => ({
                original: dateStr,
                components: parseDateComponents(dateStr)
            }))
            .sort((a, b) => compareDates(a.components, b.components))
            .map(dateObj => dateObj.original);

            //console.log('All Dates:', allDates);

            let datasets = this.datos.map((item, index) => {
            const dataMap = new Map(item.data.map(d => [d.fechaGeneral, d.total]));
            const data = allDates.map(date => dataMap.has(date) ? dataMap.get(date) : null);
                
                return {
                    label: item.label,
                    data: data,
                    fill: false,
                    borderColor: bgBorder[index % bgBorder.length],
                    pointBorderColor: bgColors[index % bgColors.length],
                    pointBackgroundColor: bgColors[index % bgColors.length],
                    borderWidth: 2,
                    tension: 0.15,
                    pointHoverRadius: 5,
                    spanGaps: true,
                    pointRadius: 4
                    //borderDash: [5, 5],
                    //backgroundColor: 'transparent',
                    //fill: false,                    
                    //pointHitRadius: 30,
                    //pointBorderWidth: 2,
                    //pointStyle: 'rectRounded',
                };
            });
            /* console.log(datasets); */

            this.renderChart({
                labels: allDates,
                datasets: datasets
            }, this.options);

        },
    },
}
</script>

<style scoped>
  #line-chart{
    height: 500px !important;
    width: 100% !important;
  }
</style>