<template>
    <modal :showModal="showModal" @closeModal="closeModal" modalSize="modal-lg" headerBgClass="bg-primary text-white">
        <template v-slot:modal-header-title>
            Detalle de participante
        </template>
        <template v-slot:modal-body-main>
            <div class="form-row">
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Nombres</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.nombres"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Apellido Paterno</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.apellidopaterno"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Apellido Materno</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.apellidomaterno"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Email</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.email"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Telefono</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.telefono"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Dni</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.dni"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Fecha Registro</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.fecha"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Carrera</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.carrera"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Sede</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.colegio"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Año Egreso</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.anioegreso"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Campaña</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.campania.nombrecampania"></span>
                </div>
                <div class="table-responsive" v-if="atenciones">
                    <table id="productos" class="table table-sm table-hover table-striped table-bordered mb-2">
                        <thead class="thead-dark">
                            <tr>
                                <th>Cod</th>
                                <th>F. Atencion</th>
                                <th>Est. Atencion</th>
                                <th>Comentario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="aten in atenciones" :key="aten.idatencion">
                                <td v-text="aten.idatencion"></td>
                                <td v-text="aten.fecha"></td>
                                <td><span class="badge badge-pill badge-lg" v-bind:style="{background: aten.tipoatencion.backgroundColor, color:aten.tipoatencion.textColor}" v-text="aten.tipoatencion.tipoatencion"></span></td>
                                <td v-text="aten.comentario"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </modal>
</template>

<script>
import Modal from './../../utils/Modal';

export default {
    data(){
        return {
            showModal:false,
            cliente:{
                campania:{},
                distrito:{}
            },
            atenciones:[],
        }
    },
    methods: {
        closeModal(event){
            this.showModal = false;
            this.cliente = {
                evento:{},
                campania:{},
                distrito:{}
            };
            this.atenciones = [];
        },
        showDetail(data){
            this.cliente = data;
            this.obtenerAtenciones(this.cliente.idcliente);
        },
        obtenerAtenciones(id){
            let vm = this;
            vm.showModal = true;
            showPreloader();

            axios.get(`${appApiUrl}/atencion/detail/${id}`)
                .then(function (response) {
                    hidePreloader();
                    if (response.data == null || response.data == '') {
                        warningMessage(`No se encontró ningúna atencion con el código ${vm.cliente.idcliente}`, appName);
                    }
                    vm.atenciones = response.data;
                    vm.showModal = true;
                })
                .catch(function (error) {
                    hidePreloader();
                    errorMessage(appErrorMessage, appName);
                    console.log(error);
                })
        }
    },
    components:{
        Modal
    }
}
    

</script>