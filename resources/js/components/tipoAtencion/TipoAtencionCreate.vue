<template>
    <form-tipoAtencion cardTitle="Registrar Tipo de Atencion" @saveData="saveData">
    </form-tipoAtencion>
</template>

<script>
    import FormTipoAtencion from './_FormTipoAtencion';

    export default {
        methods: {
            saveData(event) {
                showPreloader();
                console.log('save data', event);
                let vm = this;
                axios.post(`${appApiUrl}/tipoAtencion`, event)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.tipoAtencion'
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
            FormTipoAtencion
        }
    }

</script>
