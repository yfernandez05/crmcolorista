<template>
    <form-conceptopago cardTitle="Editar Concepto de Pago" :conceptopago="conceptopago" @saveData="saveData">
    </form-conceptopago>
</template>


<script>
    import FormConceptopago from './_FormConceptoPago';

    export default {
        data() {
            return {
                conceptopago: {}
            }
        },
        created() {
            this.conceptopago.id = this.$route.params.id;

            if (isNaN(this.conceptopago.id)) {
                this.backToList();
            }

            this.obtenerConcepto(this.conceptopago.id);
        },
        methods: {
            obtenerConcepto(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/conceptopago/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún concepto de pago con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.conceptopago = response.data;

                        if(!vm.conceptopago.isactive){
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
                axios.put(`${appApiUrl}/conceptopago/${this.conceptopago.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.conceptopago'
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
                    name: 'spa.conceptopago'
                });
            },
        },
        components: {
            FormConceptopago
        }
    }

</script>
