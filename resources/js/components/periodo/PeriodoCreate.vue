<template>
    <form-periodo cardTitle="Registrar Periodo" @saveData="saveData">
    </form-periodo>
</template>

<script>
    import FormPeriodo from './_FormPeriodo';

    export default {
        methods: {
            saveData(event) {
                showPreloader();
                console.log('save data', event);
                let vm = this;
                axios.post(`${appApiUrl}/periodo`, event)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.periodo'
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
            FormPeriodo
        }
    }

</script>
