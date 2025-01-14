<template>
    <form-inscripcion cardTitle="Editar Inscripción" :inscripcion="inscripcion" @saveData="saveData">
    </form-inscripcion>
</template>


<script>
    import FormInscripcion from './_FormInscripcion';
    import moment,{ now } from 'moment';

    export default {
        data() {
            return {
                inscripcion: {}
            }
        },
        created() {
            this.inscripcion.id = this.$route.params.id;

            if (isNaN(this.inscripcion.id)) {
                this.backToList();
            }

            this.obtenerinscripcion(this.inscripcion.id);
        },
        methods: {
            obtenerinscripcion(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/inscripcion/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningúna inscripcion con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.inscripcion = response.data;
                        vm.inscripcion.fecha = vm.formatDate(vm.inscripcion.fecha, 'DD-MM-YYYY');

                        if(!vm.inscripcion.isactive){
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
                axios.put(`${appApiUrl}/inscripcion/${this.inscripcion.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.inscripcion'
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
                    name: 'spa.inscripcion'
                });
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
        },
        components: {
            FormInscripcion
        }
    }

</script>
