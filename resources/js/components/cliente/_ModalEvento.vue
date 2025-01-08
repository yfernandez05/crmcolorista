<template>
    <modal :showModal="showModal" @closeModal="closeModal" modalSize="modal-lg" headerBgClass="bg-success text-white">
        <template v-slot:modal-header-title>
            Registros en Estands - Participante #{{cliente.idcliente}}
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
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Distrito</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.distrito.nombreubigeo"></span>
                </div>
            </div>

            <div class="table-responsive">
                <table id="productos" class="table table-sm table-hover table-striped table-bordered mb-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cod</th>
                            <th>Email</th>
                            <th>Carrera</th>
                            <th>Stand</th>
<!--                             <th>F. Registro</th> -->
                            <th>Asistencia</th>
                            <th>F. Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="even in cliente.evento_cliente" :key="even.ideventocliente">
                            <td v-text="even.idcliente"></td>
                            <td v-text="even.email"></td>
                            <td v-text="even.carrera"></td>
                            <td v-text="even.evento.nombreevento" class="ui-max-w-200 text-truncate"></td>
                            <!-- <td v-text="even.fecha"></td> -->
                            <td>
                                <span class="badge badge-pill py-1 px-3"
                                    :class="even.isassistance ? 'badge-info':'badge-secondary'" v-text="even.asistencianame">
                                </span>
                            </td>
                            <td v-text="even.fechapresencia"></td>
                        </tr>
                    </tbody>
                </table>
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
                idcliente:'',
                campania:{},
                distrito:{}
            },
            eventosclientes:[],
        }
    },

    methods:{
        closeModal(event){
            this.showModal = false;
            this.cliente = {
                evento:{},
                campania:{},
                distrito:{}
            };
            this.eventosclientes = [];
        },
        showDetail(data){
            console.log(data);
            this.cliente = data;
            //this.obtenerEventos(this.cliente.idcliente);
            this.showModal = true;
        },
    },

    components:{
        Modal
    }
}
</script>
