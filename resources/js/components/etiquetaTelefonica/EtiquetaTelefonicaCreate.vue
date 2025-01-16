<template>
    <form-etiquetaTelefonica cardTitle="Registrar Etiqueta Telefonica" @saveData="saveData">
    </form-etiquetaTelefonica>
</template>

<script>
    import FormEtiquetaTelefonica from './_FormEtiquetaTelefonica';

    export default {
        methods: {
            saveData(event) {
                showPreloader();
                console.log('save data', event);
                let vm = this;
                axios.post(`${appApiUrl}/etiquetaTelefonica`, event)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'sisfe.etiquetaTelefonica'
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
            FormEtiquetaTelefonica
        }
    }

</script>
