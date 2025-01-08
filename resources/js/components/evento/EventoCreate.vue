<template>
    <form-evento cardTitle="Registrar Stand" @saveData="saveData">
    </form-evento>
</template>

<script>
//importamos componentes y librerias
import FormEvento from './_FormEvento';

export default {
    //metodos
    methods: {
        saveData(event) {
            showPreloader();
            //console.log('save data', event);
            let vm = this;
            axios.post(`${appApiUrl}/evento`, event)
            .then(function (response) {
                hidePreloader();
                let result = response.data;

                if (result.status) {
                    successMessage(result.message, appName);
                    vm.$router.push({
                        name: 'spa.evento'
                    });
                } else{
                    errorMessage(result.message, appName);
                }

            })
            .catch(function (error) {
                hidePreloader();
                errorMessage(appErrorMessage, appName);
                console.log(error);
            })
        },
    },

    //iniciamos los componentes
    components: {
        FormEvento
    }
}
</script>