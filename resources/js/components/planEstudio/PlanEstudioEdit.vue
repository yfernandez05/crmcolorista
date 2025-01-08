<template>
    <form-plan-estudio cardTitle="Editar Plan de Estudio" :planestudio="planestudio" @saveData="saveData">
    </form-plan-estudio>
</template>


<script>
    import FormPlanEstudio from './_FormPlanEstudio';

    export default {
        data() {
            return {
                planestudio: {}
            }
        },
        created() {
            this.planestudio.id = this.$route.params.id;

            if (isNaN(this.planestudio.id)) {
                this.backToList();
            }

            this.obtenerPLanestudio(this.planestudio.id);
        },
        methods: {
            obtenerPLanestudio(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/planestudio/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún plan de estudio con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.planestudio = response.data;

                        if(!vm.planestudio.isactive){
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
                axios.put(`${appApiUrl}/planestudio/${this.planestudio.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.planestudio'
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
                    name: 'spa.planestudio'
                });
            },
        },
        components: {
            FormPlanEstudio
        }
    }

</script>
