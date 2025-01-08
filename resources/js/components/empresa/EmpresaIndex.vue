<template>
    <div>
        <main-content>
            <template v-slot:card-header-title>
                Usuario
            </template>

            <template v-slot:card-body-main>
                <div class="d-flex no-block align-items-center">
                    <div>
                        <h3><i class="ti-user"></i></h3>
                        <h4 class="text-muted">BIENVENIDO AL SISTEMA</h4>
                        <h5 class="counter text-primary mb-1" v-text="authenticatedUser.name"></h5>
                        <p class="counter" v-text="authenticatedUser.email"></p>
                    </div>
                </div>
            </template>
        </main-content>

        <main-content>
            <template v-slot:card-header-title>
                Empresa
            </template>

            <template v-slot:card-body-main>
                <h3>
                    <i class="fa fa-university" aria-hidden="true"></i>
                </h3>
                <h4 class="text-muted" v-text="cuenta.razonsocial"></h4>
                <hr class="mt-2">

                <table class="h5">
                    <tr>
                        <td>Empresa</td>
                        <td>:</td>
                        <td><span class="ml-2" v-text="cuenta.empresa"></span></td>
                    </tr>
                    <tr>
                        <td>Ruc</td>
                        <td>:</td>
                        <td><span class="ml-2" v-text="cuenta.ruc"></span></td>
                    </tr>
                </table>
                
            </template>
        </main-content>
    </div>
</template>

<script>
    import MainContent from './../../utils/MainContent';
    export default {
        data() {
            return {
                cuenta: {}
            }
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
                        }
                        vm.cuenta = response.data;

                        if(!vm.cuenta.isactive){
                            warningMessage(appCannotDeleteMessage, appName);
                        }
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        console.log(error);
                    })
            },
        },
        mounted(){
            //this.obtenerCuenta(this.authenticatedUser.idcuenta);
        },
        components: {
            MainContent,
        }
    }

</script>
