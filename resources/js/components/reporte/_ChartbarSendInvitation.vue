<script>
// Importamos librerías
import { Bar } from "vue-chartjs";
import 'chartjs-plugin-labels'; // Importa el plugin

import { bgColors, bgBorder } from './../../colorreport';

export default {
    extends: Bar,

    props: {
        datos: [],
    },

    data() {
        return {
            bgColors,
            bgBorder,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0,
                            //stepSize: 50
                            callback: function(value) { return Number.isInteger(value) ? value : ''; },
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Cantidad'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Carreras'
                        }
                    }],
                },
                plugins: {
                    labels: {
                        render: 'value', //'label' 'percentage' precision: 2
                        fontColor: ["white", "black"]
                    } 
                }, 
                legend: {
                    labels: {
                        usePointStyle: true,
                        generateLabels: (chart) => {
                            const datasets = chart.data.datasets;
                            return datasets.map((dataset, i) => {
                                return {
                                    text: 'Registros',                 // Texto que se mostrará en la leyenda
                                    fillStyle: '#c0c0c0',              // Color del cuadro de la leyenda en formato hexadecimal
                                    hidden: !chart.isDatasetVisible(i),// Oculta el conjunto de datos si no está visible
                                    //lineCap: dataset.borderCapStyle,   // Estilo de extremo de línea (por ejemplo, 'butt', 'round', 'square')
                                    //lineDash: dataset.borderDash,      // Patrón de guiones para la línea (por ejemplo, [5, 10])
                                    //lineDashOffset: dataset.borderDashOffset, // Desplazamiento del patrón de guiones
                                    //lineJoin: 'miter', // Estilo de unión de líneas (por ejemplo, 'miter', 'round', 'bevel')
                                    //strokeStyle: dataset.borderColor, // Color del borde de la línea
                                    //pointStyle: dataset.pointStyle,   // Estilo del punto (por ejemplo, 'circle', 'cross', 'triangle')
                                    datasetIndex: i                   // Índice del conjunto de datos
                                };
                            });
                        }
                    }
                }
            }
        }
    },

    methods: {
        mostrar() {
            const { labels, data } = this.datos.reduce((acc, item) => {
                //const label = `${item.fecha_atencion}\n${item.tipoatencion}: ${item.total_atenciones}`;
                acc.labels.push(item.fecha_atencion);
                acc.data.push(item.total_atenciones);
                return acc;
            }, { labels: [], data: [] });

            //console.log(this.options);

            this.renderChart({
                labels: labels,
                datasets: [
                    {
                        backgroundColor: this.bgColors,
                        borderColor: this.bgBorder,
                        borderWidth: 2,
                        data: data,
                    }
                ]
            }, this.options); // Para que sea responsivo
        },
    },

    mounted() {
        this.mostrar();
    }
}
</script>

<style scoped>
 #bar-chart {
     height: 237px !important;
     display: block !important;
 }
</style>
