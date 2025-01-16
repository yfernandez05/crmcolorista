<template>
    <form-etiquetaTelefonica  cardTitle="Editar Etiqueta Telefonica" :etiquetatelefonica="etiquetatelefonica" @saveData="saveData">
    </form-etiquetaTelefonica>
</template>

<script>
  import FormEtiquetaTelefonica from './_FormEtiquetaTelefonica';


export default {
     data() {
            return {
                etiquetatelefonica: {}
            }
        },
        created() {
            this.etiquetatelefonica.idetiquetatele = this.$route.params.id;

            if (isNaN(this.etiquetatelefonica.idetiquetatele)) {
                this.$router.push({
                    name: 'sisfe.etiquetaTelefonica'
                });
            }

            this.obtenerEtiquetaTelefonica(this.etiquetatelefonica.idetiquetatele);
        },
        methods: {
            obtenerEtiquetaTelefonica(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/etiquetaTelefonica/${id}/edit`)
                    .then(function (response) {
                        hidePreloader();
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningúna Etiqueta Telefonica con el código ${id}`, appName);

                            vm.$router.push({
                                name: 'sisfe.etiquetaTelefonica'
                            });
                        }
                        vm.etiquetatelefonica = response.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);

                        vm.$router.push({
                            name: 'sisfe.etiquetaTelefonica'
                        });
                        console.log(error);
                    })
            },
            saveData(event) {
                showPreloader();

                let vm = this;
                axios.put(`${appApiUrl}/etiquetaTelefonica/${this.etiquetatelefonica.idetiquetatele}`, event)
                    .then(function (response) {
                        hidePreloader();

                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'sisfe.etiquetaTelefonica'
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
            FormEtiquetaTelefonica
        }
}
</script>
