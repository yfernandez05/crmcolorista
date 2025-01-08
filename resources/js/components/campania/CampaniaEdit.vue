<template>
    <form-campania cardTitle="Editar Campaña" :campania="campania" @saveData="saveData">
    </form-campania>
</template>

<script>
    import FormCampania from './_FormCampania';
    import moment,{ now } from 'moment';

    export default {
        data() {
            return {
                campania: {}
            }
        },
        created() {
            this.campania.idcampania = this.$route.params.id;

            if (isNaN(this.campania.idcampania)) {
                this.backToList();
            }

            this.obtenerCampania(this.campania.idcampania);
        },
        methods: {
            obtenerCampania(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/campania/${id}/edit`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningúna campaña con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.campania = response.data;
                        vm.campania.fechainicio = vm.formatDate(vm.campania.fechainicio, 'DD-MM-YYYY HH:mm:ss');
                        vm.campania.fechafin = vm.formatDate(vm.campania.fechafin, 'DD-MM-YYYY HH:mm:ss');

                        if(!vm.campania.isactive){
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
                axios.put(`${appApiUrl}/campania/${this.campania.idcampania}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.campania'
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
                    name: 'spa.campania'
                });
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
        },
        components: {
            FormCampania
        }
    }

</script>
