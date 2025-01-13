<template>
    <form-prospecto cardTitle="Editar prospecto" :prospecto="prospecto" @saveData="saveData">
    </form-prospecto>
</template>


<script>
    import FormProspecto from './_FormProspecto';
    import moment,{ now } from 'moment';

    export default {
        data() {
            return {
                prospecto: {}
            }
        },
        created() {
            this.prospecto.id = this.$route.params.id;

            if (isNaN(this.prospecto.id)) {
                this.backToList();
            }

            this.obtenerprospecto(this.prospecto.id);
        },
        methods: {
            obtenerprospecto(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/prospecto/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún prospecto con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.prospecto = response.data;
                        vm.prospecto.fecha_nac = vm.formatDate(vm.prospecto.fecha_nac, 'DD-MM-YYYY');

                        if(!vm.prospecto.isactive){
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
                //console.log('save data', event);

                let vm = this;
                axios.put(`${appApiUrl}/prospecto/${this.prospecto.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.prospecto'
                            });
                        } else
                            errorMessage(result.message, appName);
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        console.log(error);
                    })
            },
            backToList(){
                this.$router.push({
                    name: 'spa.prospecto'
                });
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
        },
        components: {
            FormProspecto
        }
    }

</script>
