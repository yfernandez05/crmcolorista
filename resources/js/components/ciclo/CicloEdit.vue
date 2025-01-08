<template>
    <form-ciclo cardTitle="Editar Ciclo" :ciclo="ciclo" @saveData="saveData">
    </form-ciclo>
</template>


<script>
    import FormCiclo from './_FormCiclo';

    export default {
        data() {
            return {
                ciclo: {}
            }
        },
        created() {
            this.ciclo.id = this.$route.params.id;

            if (isNaN(this.ciclo.id)) {
                this.backToList();
            }

            this.obtenerCliclo(this.ciclo.id);
        },
        methods: {
            obtenerCliclo(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/ciclo/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún ciclo con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.ciclo = response.data;

                        if(!vm.ciclo.isactive){
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
                axios.put(`${appApiUrl}/ciclo/${this.ciclo.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.ciclo'
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
                    name: 'spa.ciclo'
                });
            },
        },
        components: {
            FormCiclo
        }
    }

</script>
