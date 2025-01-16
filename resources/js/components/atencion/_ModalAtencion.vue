<template>
    <modal :showModal="showModal" @closeModal="closeModal" modalSize="modal-lg" headerBgClass="bg-success text-white">
        <template v-slot:modal-header-title>
            Detalles de Atencion
        </template>
        <template v-slot:modal-body-main>
            <div class="form-row">
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-6">
                    <label class="mb-0">Nombres </label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="prospecto.nombre"></span>
                </div>

                <div class="form-group form-group-sm col-12 col-sm-6 col-md-6 col-xl-6">
                    <label class="mb-0">Teléfono</label>
                    <div class="input-group">
                        <span class="form-control form-control-sm d-block text-truncate text-muted" v-text="prospecto.telefono"></span>
                        <ContactButtonyf :phone="prospecto.telefono" :showWhatsapp="true"/>
                        <!-- <a v-if="isvalidtele" @click="telefonocall(prospecto.telefono)" title="Llamar" class="btn btn-sm waves-effect waves-light btn btn-info text-truncate ml-1 px-2 col-4">
                        <i class="fas fa-phone-alt"></i>&nbsp;<span>LLamar</span>
                        </a> -->
                    </div>
                    </div>

                    <div class="form-group form-group-sm col-8">
                    <label class="mb-0">Email</label>
                    <div class="input-group">
                        <span class="form-control form-control-sm d-block text-truncate text-muted" v-text="prospecto.correo"></span>
                        <ContactButtonyf :email="prospecto.correo" :showEmail="true"/>
                    </div>
                </div>


            </div>

            <div class="table-responsive">
                <table id="productos" class="table table-sm table-hover table-striped table-bordered mb-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cod</th>
                            <th>F. Atencion</th>
                            <th>Etiqueta General</th>
                            <th>Etiqueta Telefonica</th>
                            <th>Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="aten in atenciones" :key="aten.idatencion">
                            <td v-text="aten.idatencion"></td>
                            <td v-text="aten.fecha"></td>

                            <td><span class="badge badge-pill py-1 px-3"
                                v-bind:style="{background:aten.tipoatencion.backgroundColor, color:aten.tipoatencion.textColor}"
                                v-text="aten.tipoatencion.tipoatencion"></span>
                            </td>

                            <td><span class="badge badge-pill py-1 px-3"
                                v-bind:style="{background:aten.etiquetatelefonica.backgroundColor, color:aten.etiquetatelefonica.textColor}"
                                v-text="aten.etiquetatelefonica.etiquetatele"></span>
                            </td>

                            <td v-text="aten.comentario"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </modal>
</template>

<script>
import Modal from './../../utils/Modal';
import Select2 from './../../utils/Select2';
import ContactButtonyf from './../../utils/ContactButtonyf';

export default {
    data(){
        return {
            showModal:false,
            prospecto:{

            },
            atenciones:[],
            users:[],
            isvalidcel:false,
            isvalidtele:false,
            isvalidemail:false,
        }
    },
    methods: {
        closeModal(event){
            this.showModal = false;
            isvalidcel:false,
            //isvalidtele:false,
            this.prospecto = {
            };
            this.atenciones = [];
        },
        showDetail(data){
            this.prospecto = data;
            this.obtenerAtenciones(this.prospecto.id);
        },
        obtenerAtenciones(id){
            showPreloader();

              //validation cel
            //let celinitial = this.prospecto.telefono;

            // if(celinitial != null){
            //     celinitial.substr(0,1) == 10 || celinitial.substr(0,1) == 13 ? this.isvalidcel = true : this.isvalidcel = false;
            // }
            this.isvalidcel = true;

            let celinicial= this.prospecto.telefono
            this.isvalidtele = true

            let emailinicial= this.prospecto.correo
            this.isvalidemail = true


            let vm = this;
            axios.get(`${appApiUrl}/atencion/detail/${id}`)
                .then(function (response) {
                    hidePreloader();
                    if (response.data == null || response.data == '') {
                        warningMessage(`No se encontró ningúna atencion con el código ${vm.prospecto.id}`, appName);
                    }
                    vm.atenciones = response.data;
                    vm.showModal = true;
                })
                .catch(function (error) {
                    hidePreloader();
                    errorMessage(appErrorMessage, appName);
                    console.log(error);
                })
        },
        clearspaceandprefi(telefono){

            let celvalidate = telefono.replace('51','');
                window.open('https://wa.me/51'+celvalidate+'/', '_blank');

            console.log(celvalidate);
        },
        telefonocall(telefono){


            let celvalidate = telefono.replace('51','');
            window.open('tel:00'+'51'+celvalidate);

        },
        emailcontactar(correo){

            let emailvalido = correo;
            console.log(emailvalido);

            if(emailvalido)
                window.open('mailto:'+emailvalido);


        },
        listarUser() {
                let vm = this;
                axios.get(`${appApiUrl}/user/userasesor`)
                    .then(function (response) {

                        hidePreloader();
                        vm.users = response.data;

                    })
                    .catch(function (error) {
                        hidePreloader();
                        console.log(error);
                    });
        },

    },
    mounted() {
            this.listarUser();
    },
    components:{
        Modal,
        Select2,
        ContactButtonyf
    }
}


</script>
