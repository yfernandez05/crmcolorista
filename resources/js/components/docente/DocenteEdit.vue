<template>
    <form-docente cardTitle="Modificar cursos asignados" :docente="docente" @saveData="saveData">
    </form-docente>
</template>


<script>
    import FormDocente from './_FormDocente';

    export default {
        data() {
            return {
                docente: {}
            }
        },
        created() {
            this.docente.id = this.$route.params.id;

            if (isNaN(this.docente.id)) {
                this.backToList();
            }

            this.obtenerdocente(this.docente.id);
        },
        methods: {
            obtenerdocente(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/docente/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontraron cursos para el docente con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.docente = response.data;

                        if(!vm.docente.isactive){
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

                let vm = this;
                axios.put(`${appApiUrl}/docente/${this.docente.id}`, event)
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
            },
            backToList(){
                this.$router.push({
                    name: 'spa.docente'
                });
            },
        },
        components: {
            FormDocente
        }
    }

</script>
