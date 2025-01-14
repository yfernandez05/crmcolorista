<template>
    <form-seguimiento cardTitle="Editar seguimiento" :seguimiento="seguimiento" @saveData="saveData">
    </form-seguimiento>
</template>


<script>
    import FormSeguimiento from './_FormSeguimiento';
    import moment,{ now } from 'moment';

    export default {
        data() {
            return {
                seguimiento: {}
            }
        },
        created() {
            this.seguimiento.id = this.$route.params.id;

            if (isNaN(this.seguimiento.id)) {
                this.backToList();
            }

            this.obtenerseguimiento(this.seguimiento.id);
        },
        methods: {
            obtenerseguimiento(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/seguimiento/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún seguimiento con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.seguimiento = response.data;
                        //vm.seguimiento.fecha_nac = vm.formatDate(vm.seguimiento.fecha_nac, 'DD-MM-YYYY');

                        if(!vm.seguimiento.isactive){
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
                axios.put(`${appApiUrl}/seguimiento/${this.seguimiento.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.seguimiento'
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
                    name: 'spa.seguimiento'
                });
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
        },
        components: {
            FormSeguimiento
        }
    }

</script>
