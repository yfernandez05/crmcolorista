<template>
    <form-tipoAtencion  cardTitle="Editar Tipo de Atencion" :tipoatencion="tipoatencion" @saveData="saveData">
    </form-tipoAtencion>
</template>

<script>
  import FormTipoAtencion from './_FormTipoAtencion';


export default {
     data() {
            return {
                tipoatencion: {}
            }
        },
        created() {
            this.tipoatencion.idtipoatencion = this.$route.params.id;

            if (isNaN(this.tipoatencion.idtipoatencion)) {
                this.$router.push({
                    name: 'spa.tipoAtencion'
                });
            }

            this.obtenerTipoAtencion(this.tipoatencion.idtipoatencion);
        },
        methods: {
            obtenerTipoAtencion(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/tipoAtencion/${id}/edit`)
                    .then(function (response) {
                        hidePreloader();
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningúna Tipo de Atencion con el código ${id}`, appName);

                            vm.$router.push({
                                name: 'spa.tipoAtencion'
                            });
                        }
                        vm.tipoatencion = response.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);

                        vm.$router.push({
                            name: 'spa.tipoAtencion'
                        });
                        console.log(error);
                    })
            },
            saveData(event) {
                showPreloader();
               
                let vm = this;
                axios.put(`${appApiUrl}/tipoAtencion/${this.tipoatencion.idtipoatencion}`, event)
                    .then(function (response) {
                        hidePreloader();
         
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.tipoAtencion'
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
            FormTipoAtencion
        }
}
</script>
