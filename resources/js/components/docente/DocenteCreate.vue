<template>
    <form-docente cardTitle="Registrar cursos para el docente" @saveData="saveData">
    </form-docente>
</template>

<script>
import _FormDocente from './_FormDocente.vue';
    import FormDocente from './_FormDocente';

    export default {
        methods: {
            saveData(event) {
                showPreloader();
                console.log('save data', event);
                let vm = this;
                axios.post(`${appApiUrl}/docente`, event)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.docente'
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
            FormDocente
        }
    }

</script>
