<template>
    <form-curso cardTitle="Editar curso" :curso="curso" @saveData="saveData">
    </form-curso>
</template>


<script>
    import FormCurso from './_FormCurso';

    export default {
        data() {
            return {
                curso: {}
            }
        },
        created() {
            this.curso.id = this.$route.params.id;

            if (isNaN(this.curso.id)) {
                this.backToList();
            }

            this.obtenerCurso(this.curso.id);
        },
        methods: {
            obtenerCurso(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/curso/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún curso con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.curso = response.data;

                        if(!vm.curso.isactive){
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
                axios.put(`${appApiUrl}/curso/${this.curso.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
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
            },
            backToList(){
                this.$router.push({
                    name: 'spa.curso'
                });
            },
        },
        components: {
            FormCurso
        }
    }

</script>
