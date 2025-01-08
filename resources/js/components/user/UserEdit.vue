<template>
    <form-user cardTitle="Editar Usuario" :user="user" @saveData="saveData" :showPasswordField="false">
    </form-user>
</template>

<script>
    import FormUser from './_FormUser';

    export default {
        data() {
            return {
                user: {}
            }
        },
        created() {
            this.user.id = this.$route.params.id;

            if (isNaN(this.user.id)) {
                this.$router.push({
                    name: 'spa.user'
                });
            }

            this.obtenerUser(this.user.id);
        },
        methods: {
            obtenerUser(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/user/${id}/edit`)
                    .then(function (response) {
                        hidePreloader();

                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún User con el código ${id}`, appName);

                            vm.$router.push({
                                name: 'spa.user'
                            });
                        }
                        vm.user = response.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);

                        vm.$router.push({
                            name: 'spa.user'
                        });
                        console.log(error);
                    })
            },
            saveData(event) {
                showPreloader();


                let vm = this;
                axios.put(`${appApiUrl}/user/${this.user.id}`, event)
                    .then(function (response) {
                        hidePreloader();

                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.user'
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
        components: {
            FormUser
        }
    }

</script>


