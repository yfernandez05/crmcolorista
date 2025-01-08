<template>
    <form-button-message  cardTitle="Editar Boton" :buttonmessage="buttonmessage" @saveData="saveData">
    </form-button-message>
</template>

<script>
  import FormButtonMessage from './_FormButtonMessage.vue';


export default {
     data() {
            return {
                buttonmessage: {}
            }
        },
        created() {
            this.buttonmessage.idbutton = this.$route.params.id;

            if (isNaN(this.buttonmessage.idbutton)) {
                this.$router.push({
                    name: 'spa.buttonmessage'
                });
            }

            this.obtenerbotonesmessage(this.buttonmessage.idbutton);
        },
        methods: {
            obtenerbotonesmessage(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/buttonmessage/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningúna boton con el código ${id}`, appName);

                            vm.$router.push({
                                name: 'spa.buttonmessage'
                            });
                        }
                        vm.buttonmessage = response.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);

                        vm.$router.push({
                            name: 'spa.buttonmessage'
                        });
                        console.log(error);
                    })
            },
            saveData(event) {
                showPreloader();
               
                let vm = this;
                axios.put(`${appApiUrl}/buttonmessage/${this.buttonmessage.idbutton}`, event)
                    .then(function (response) {
                        hidePreloader();
         
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.buttonmessage'
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
        
        mounted() {

        },
        components: {
            FormButtonMessage
        }
}
</script>
