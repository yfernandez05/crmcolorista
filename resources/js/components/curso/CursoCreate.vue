<template>
    <form-curso cardTitle="Registrar Curso" @saveData="saveData">
    </form-curso>
</template>

<script>
    import FormCurso from './_FormCurso';

    export default {
        methods: {
            saveData(event) {
                showPreloader();
                console.log('save data', event);
                let vm = this;
                axios.post(`${appApiUrl}/curso`, event)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.curso'
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
            FormCurso
        }
    }

</script>
