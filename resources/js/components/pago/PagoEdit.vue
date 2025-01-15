<template>
    <form-pago cardTitle="Editar Pago" :pago="pago" @saveData="saveData">
    </form-pago>
</template>


<script>
    import FormPago from './_FormPago';

    export default {
        data() {
            return {
                pago: {}
            }
        },
        created() {
            this.pago.id = this.$route.params.id;

            if (isNaN(this.pago.id)) {
                this.backToList();
            }

            this.obtenerpago(this.pago.id);
        },
        methods: {
            obtenerpago(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/pago/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningúna pago con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.pago = response.data;

                        if(!vm.pago.isactive){
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
                axios.put(`${appApiUrl}/pago/${this.pago.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.pago'
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
                    name: 'spa.pago'
                });
            },
        },
        components: {
            FormPago
        }
    }

</script>
