<template>
    <form-rol cardTitle="Registrar Rol" @saveData="saveData">

    </form-rol>
</template>



<script>
    import FormRol from './_FormRol';
    
    export default {
        methods: {
            saveData(event) {
                showPreloader();
                console.log('save data', event);
                let vm = this;
                axios.post(`${appApiUrl}/rol`, event)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.rol'
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
            mounted() {
                console.log('Registrar rol')
            },

        },
        components: {
            FormRol
        }
    }

</script>
