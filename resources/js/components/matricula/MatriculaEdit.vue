<template>
    <form-matricula cardTitle="Editar Inscripción" :matricula="matricula" @saveData="saveData">
    </form-matricula>
</template>


<script>
    import FormMatricula from './_FormMatricula';
    import moment,{ now } from 'moment';

    export default {
        data() {
            return {
                matricula: {}
            }
        },
        created() {
            this.matricula.id = this.$route.params.id;

            if (isNaN(this.matricula.id)) {
                this.backToList();
            }

            this.obtenermatricula(this.matricula.id);
        },
        methods: {
            obtenermatricula(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/matricula/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningúna matricula con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.matricula = response.data;
                        vm.matricula.fecha = vm.formatDate(vm.matricula.fecha, 'DD-MM-YYYY');

                        if(!vm.matricula.isactive){
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
                axios.put(`${appApiUrl}/matricula/${this.matricula.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.matricula'
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
                    name: 'spa.matricula'
                });
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
        },
        components: {
            FormMatricula
        }
    }

</script>
