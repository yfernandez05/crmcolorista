<template>
    <form-aula cardTitle="Editar Aula" :aula="aula" @saveData="saveData">
    </form-aula>
</template>


<script>
    import FormAula from './_FormAula.vue';

    export default {
        data() {
            return {
                aula: {}
            }
        },
        created() {
            this.aula.id = this.$route.params.id;

            if (isNaN(this.aula.id)) {
                this.backToList();
            }

            this.obtenerAula(this.aula.id);
        },
        methods: {
            obtenerAula(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/aula/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún aula con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.aula = response.data;

                        if(!vm.aula.isactive){
                            warningMessage(appCannotDeleteMessage, appName);
                            vm.backToList();
                        }
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        vm.backToList();
                        console.log(error);
                    })
            },
            saveData(event) {
                showPreloader();
                //console.log('save data', event);

                let vm = this;
                axios.put(`${appApiUrl}/aula/${this.aula.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.aula'
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
            backToList(){
                this.$router.push({
                    name: 'spa.aula'
                });
            },
        },
        components: {
            FormAula
        }
    }

</script>
