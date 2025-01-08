<template>
    <form-turno cardTitle="Editar turno" :turno="turno" @saveData="saveData">
    </form-turno>
</template>


<script>
    import FormTurno from './_FormTurno';

    export default {
        data() {
            return {
                turno: {}
            }
        },
        created() {
            this.turno.id = this.$route.params.id;

            if (isNaN(this.turno.id)) {
                this.backToList();
            }

            this.obtenerTurno(this.turno.id);
        },
        methods: {
            obtenerTurno(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/turno/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún turno con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.turno = response.data;

                        if(!vm.turno.isactive){
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
                axios.put(`${appApiUrl}/turno/${this.turno.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.turno'
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
                    name: 'spa.turno'
                });
            },
        },
        components: {
            FormTurno
        }
    }

</script>
