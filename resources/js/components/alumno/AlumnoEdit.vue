<template>
    <form-alumno cardTitle="Editar alumno" :alumno="alumno" @saveData="saveData">
    </form-alumno>
</template>


<script>
    import FormAlumno from './_FormAlumno';
    import moment,{ now } from 'moment';

    export default {
        data() {
            return {
                alumno: {}
            }
        },
        created() {
            this.alumno.id = this.$route.params.id;

            if (isNaN(this.alumno.id)) {
                this.backToList();
            }

            this.obtenerAlumno(this.alumno.id);
        },
        methods: {
            obtenerAlumno(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/alumno/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún alumno con el código ${id}`, appName);
                            this.backToList();
                        }
                        vm.alumno = response.data;
                        vm.alumno.fecha_nac = vm.formatDate(vm.alumno.fecha_nac, 'DD-MM-YYYY');
                        vm.alumno.fecha_inscripcion = vm.formatDate(vm.alumno.fecha_inscripcion, 'DD-MM-YYYY');

                        if(!vm.alumno.isactive){
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
                axios.put(`${appApiUrl}/alumno/${this.alumno.id}`, event)
                    .then(function (response) {
                        hidePreloader();
                        //console.log(response);
                        let result = response.data;

                        if (result.status) {
                            successMessage(result.message, appName);

                            vm.$router.push({
                                name: 'spa.alumno'
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
                    name: 'spa.alumno'
                });
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
        },
        components: {
            FormAlumno
        }
    }

</script>
