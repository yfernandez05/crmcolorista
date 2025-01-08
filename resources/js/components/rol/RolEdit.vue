<template>
    <form-rol  cardTitle="Editar Rol" :rol="rol" @saveData="saveData">
    </form-rol>
</template>

<script>
  import FormRol from './_FormRol';


export default {
     data() {
            return {
                rol: {}
            }
        },
        created() {
            this.rol.id = this.$route.params.id;

            if (isNaN(this.rol.id)) {
                this.$router.push({
                    name: 'spa.rol'
                });
            }

            this.obtenerRol(this.rol.id);
        },
        methods: {
            obtenerRol(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/rol/${id}/edit`)
                    .then(function (response) {
                        hidePreloader();
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningúna Rol con el código ${id}`, appName);

                            vm.$router.push({
                                name: 'spa.rol'
                            });
                        }
                        vm.rol = response.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);

                        vm.$router.push({
                            name: 'spa.rol'
                        });
                        console.log(error);
                    })
            },
            saveData(event) {
                showPreloader();
               
                let vm = this;
                axios.put(`${appApiUrl}/rol/${this.rol.id}`, event)
                    .then(function (response) {
                        hidePreloader();
         
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.rol'
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
            FormRol
        }
}
</script>