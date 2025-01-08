<template>
    <form-condicion cardTitle="Editar condicion" :condicion="condicion" @saveData="saveData">
    </form-condicion>
</template>


<script>
    import FormCondicion from './_FormCondicion';

    export default {
        data() {
            return {
                condicion: {}
            }
        },
        created() {
            this.condicion.id = this.$route.params.id;

            if (isNaN(this.condicion.id)) {
                this.backToList();
            }

            this.obtenerCondicion(this.condicion.id);
        },
        methods: {
            obtenerCondicion(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/condicion/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningúna condicion con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.condicion = response.data;

                        if(!vm.condicion.isactive){
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
                axios.put(`${appApiUrl}/condicion/${this.condicion.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.condicion'
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
                    name: 'spa.condicion'
                });
            },
        },
        components: {
            FormCondicion
        }
    }

</script>
