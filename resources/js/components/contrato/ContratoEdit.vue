<template>
    <form-contrato cardTitle="Editar contrato" :contrato="contrato" @saveData="saveData">
    </form-contrato>
</template>


<script>
    import FormContrato from './_FormContrato';

    export default {
        data() {
            return {
                contrato: {}
            }
        },
        created() {
            this.contrato.id = this.$route.params.id;

            if (isNaN(this.contrato.id)) {
                this.backToList();
            }

            this.obtenerContrato(this.contrato.id);
        },
        methods: {
            obtenerContrato(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/contrato/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún contrato con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.contrato = response.data;
                        /* vm.contrato.fecharegistro = vm.formatDate(vm.contrato.fecharegistro, 'DD-MM-YYYY');
                        vm.contrato.fecha_limitepago = vm.formatDate(vm.contrato.fecha_limitepago, 'DD-MM-YYYY'); */
                        /* vm.contrato.fecha_inscripcion = vm.formatDate(vm.contrato.fecha_inscripcion, 'DD-MM-YYYY'); */

                        if(!vm.contrato.isactive){
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
                axios.put(`${appApiUrl}/contrato/${this.contrato.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.contrato'
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
                    name: 'spa.contrato'
                });
            },
        },
        components: {
            FormContrato
        }
    }

</script>
