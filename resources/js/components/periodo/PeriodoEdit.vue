<template>
    <form-periodo cardTitle="Editar Periodo" :periodo="periodo" @saveData="saveData">
    </form-periodo>
</template>


<script>
    import FormPeriodo from './_FormPeriodo.vue';

    export default {
        data() {
            return {
                periodo: {}
            }
        },
        created() {
            this.periodo.id = this.$route.params.id;

            if (isNaN(this.periodo.id)) {
                this.backToList();
            }

            this.obtenerPeriodo(this.periodo.id);
        },
        methods: {
            obtenerPeriodo(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/periodo/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún periodo con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.periodo = response.data;

                        if(!vm.periodo.isactive){
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
                axios.put(`${appApiUrl}/periodo/${this.periodo.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
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
            },
            backToList(){
                this.$router.push({
                    name: 'spa.periodo'
                });
            },
        },
        components: {
            FormPeriodo
        }
    }

</script>
