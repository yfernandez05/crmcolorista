<template>
    <form-tipo-comprobante cardTitle="Registrar Tipo Comprobante" @saveData="saveData">
    </form-tipo-comprobante>
</template>
<script>
    import FormTipoComprobante from './_FormTipoComprobante';

    export default {
        methods: {
            saveData(event) {
                showPreloader();
                //console.log('save data', event);
                let vm = this;
                axios.post(`${appApiUrl}/tipocomprobante`, event)
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
