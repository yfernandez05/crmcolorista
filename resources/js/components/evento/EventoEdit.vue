<template>
    <form-evento cardTitle="Editar Stand" :evento="evento" @saveData="saveData">
    </form-evento>
</template>

<script>
//importamos componentes librerias
import FormEvento from './_FormEvento';
import moment,{ now } from 'moment';

export default {
    data() {
        return {
            evento: {}
        }
    },

    //iniciamos los metodos
    created() {
        this.evento.idevento = this.$route.params.id;

        if (isNaN(this.evento.idevento)) {
            this.backToList();
        }

        this.obtenerEvento(this.evento.idevento);
    },

    //metodos
    methods: {
        obtenerEvento(id) {
            showPreloader();

            let vm = this;
            axios.get(`${appApiUrl}/evento/${id}`)
                .then(function (response) {
                    hidePreloader();
                    //console.log(response);
                    if (response.data == null || response.data == '') {
                        warningMessage(`No se encontró ningún stand con el código ${id}`, appName);
                        this.backToList();
                    }
                    vm.evento = response.data;
                    vm.evento.fechainicio = vm.formatDate(vm.evento.fechainicio, 'DD-MM-YYYY HH:mm:ss');
                    vm.evento.fechafin = vm.formatDate(vm.evento.fechafin, 'DD-MM-YYYY HH:mm:ss');

                    if(!vm.evento.isactive){
                        warningMessage(appCannotDeleteMessage, appName);
                        vm.backToList();
                    }
                })
                .catch(function (error) {
                    hidePreloader();
                    errorMessage(appErrorMessage, appName);
                    vm.backToList();
                    console.log(error);
                })
        },
        saveData(event) {
            showPreloader();

            let vm = this;
            axios.put(`${appApiUrl}/evento/${this.evento.idevento}`, event)
                .then(function (response) {
                    hidePreloader();
                    //console.log(response);
                    let result = response.data;

                    if (result.status) {
                        successMessage(result.message, appName);
                        vm.$router.push({
                            name: 'spa.evento'
                        });
                    } else{
                        errorMessage(result.message, appName);
                    }
                        
                })
                .catch(function (error) {
                    hidePreloader();
                    errorMessage(appErrorMessage, appName);
                    console.log(error);
                })
        },
        backToList(){
            this.$router.push({
                name: 'spa.evento'
            });
        },
        formatDate (value, fmt = 'D MMM YYYY') {
            return (value == null)
                ? ''
                : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
        },
    },

    //iniciamos componentes
    components: {
        FormEvento
    }
}

</script>