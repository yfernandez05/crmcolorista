<template>
    <form-tipo-comprobante cardTitle="Editar Tipo Comprobante" :tipoComprobante="tipoComprobante" @saveData="saveData">
    </form-tipo-comprobante>
</template>
<script>
    import FormTipoComprobante from './_FormTipoComprobante';

    export default {
        data() {
            return {
                tipoComprobante: {}
            }
        },
        created() {
            this.tipoComprobante.codcomprobante = this.$route.params.id;

            if (isNaN(this.tipoComprobante.codcomprobante)) {
                this.$router.push({
                    name: 'spa.tipocomprobante'
                });
            }

            this.obtenerTipoComprobante(this.tipoComprobante.codcomprobante);
        },
        methods: {
            obtenerTipoComprobante(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/tipocomprobante/${id}/edit`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún tipo de comprobante con el código ${id}`, appName);

                            vm.$router.push({
                                name: 'spa.tipocomprobante'
                            });
                        }
                        vm.tipoComprobante = response.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);

                        vm.$router.push({
                            name: 'spa.tipocomprobante'
                        });
                        console.log(error);
                    })
            },
            saveData(event) {
                showPreloader();
                //console.log('save data', event);
                let vm = this;
                axios.put(`${appApiUrl}/tipocomprobante/${this.tipoComprobante.codcomprobante}`, event)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.tipocomprobante'
                            });
                        } else
                            errorMessage(result.message, appName);
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        console.log(error);
                    })
            }
        },
        components: {
            FormTipoComprobante
        }
    }

</script>
