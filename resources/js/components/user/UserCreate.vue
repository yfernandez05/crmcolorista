<template>
    <form-user cardTitle="Registrar Usuario" @saveData="saveData">

    </form-user>
</template>

<script>
import FormUser from './_FormUser';

export default {
    methods: {
            saveData(event) {
                showPreloader();
                //console.log('save data', event);
                let vm = this;
                axios.post(`${appApiUrl}/user`, event)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.user'
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
            FormUser
        }
}
</script>