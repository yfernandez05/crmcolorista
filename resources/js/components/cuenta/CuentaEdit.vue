<template>
    <form-cuenta cardTitle="Editar cuenta" :cuenta="cuenta" @saveData="saveData">
    </form-cuenta>
</template>


<script>
    import FormCuenta from './_FormCuenta';
    import moment,{ now } from 'moment';

    export default {
        data() {
            return {
                cuenta: {}
            }
        },
        created() {
            this.cuenta.idcuenta = this.$route.params.id;

            if (isNaN(this.cuenta.idcuenta)) {
                this.backToList();
            }

            this.obtenerCuenta(this.cuenta.idcuenta);
        },
        methods: {
            obtenerCuenta(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/cuenta/${id}/edit`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningúna cuenta con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.cuenta = response.data;
                        vm.cuenta.fechainicio = vm.formatDate(vm.cuenta.fechainicio, 'DD-MM-YYYY');

                        if(!vm.cuenta.isactive){
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
                axios.put(`${appApiUrl}/cuenta/${this.cuenta.idcuenta}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.cuenta'
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
                    name: 'spa.cuenta'
                });
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
        },
        components: {
            FormCuenta
        }
    }

</script>
