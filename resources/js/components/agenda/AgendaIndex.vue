<template>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="modal" data-target="#modalagenda"
            aria-haspopup="true" aria-expanded="false"> <i class="fas fa-bell"></i>
            <div v-show="activeagenda" class="notify animated infinite pulse delay-2s"> <span class="heartbit"></span> <span class="point"></span> </div>
        </a>
    <!-- Modal -->
<div class="modal fade" id="modalagenda" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div v-text="activeagenda ? 'Recordatorios' : 'Sin Recordatorios'" class="drop-title"></div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
            <ul class="listadoalert">

                <li>
                    <div v-for="ag in agendas" :key="ag.id" class="message-center" >

                        <a href="javascript:void(0)" class="listado">
                            <div class="btn btn-success btn-circle"><i class="ti-calendar"></i>
                            </div>
                            <button title="Eliminar Agenda" @click="limpiaragendas(ag.ultimaatencion.idatencion)" class="btndrop"><i class="fas fa-times"></i></button>
                            <button title="Atencion" @click="buscarAtencion(ag)" class="btnuser"> <i class="fas fa-user-edit"></i></button>
                            <div class="mail-contnet">
                                <h5 class="time">Id Prospecto: <span v-text="ag.id"></span></h5>
                                <span v-text="ag.nombre" class="txtcliente"></span>
                                <h6 class="txtprograma">Fecha Programada:</h6>
                                <span v-text="ag.ultimaatencion.fechaagendada" class="time"></span>


                            </div>
                        </a>
                    </div>
                </li>

            </ul>
        </div>
      </div>

    </div>
  </div>
</div>

    </li>

</template>

<script>
import dayjs from 'dayjs';
var minMax = require('dayjs/plugin/minMax')
dayjs.extend(minMax)

import {bus} from '../../app'
import Modal from './../../utils/Modal';

  export default {
        data() {
            return{
                showModal:false,
                agendas: [],
                today:new Date(),
                Nfechas:[],
                datainterval:false,
                activeagenda:false,
                cliente:{

                }
            }
        },

        methods: {
            listarAgenda() {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/agenda`)
                    .then(function (response) {

                        hidePreloader();
                        vm.activeinterval(response.data.data);

                    })
                    .catch(function (error) {
                        hidePreloader();
                        console.log(error);
                    });
            },

            activeinterval(data){
                this.today = dayjs(new Date()).format('YYYY-MM-DD HH:mm:ss');
                let fechavalid = data.filter(element => element.ultimaatencion.fechaagenda <= this.today  ||  element.ultimaatencion.fechaagenda >= this.today);
                let fechas = [];
                let vm = this;

                if(fechavalid.length){
                    //this.activeagenda = true;

                    this.Nfechas = [];
                    this.Nfechas = data;

                    //setInterval(this.fechasagendas(), 1000);
                    this.datainterval = setInterval(function(){
                        vm.fechasagendas(vm.Nfechas);
                         console.log('contar');
                    } , 1000);
                }
                //console.log(fechas,'interval');


            },

            fechasagendas(data){

                this.today = dayjs(new Date()).format('YYYY-MM-DD HH:mm:ss');
                let fechas = data.filter(element => element.ultimaatencion.fechaagenda <= this.today);

                if(fechas.length){

                    this.activeagenda = true;
                    this.agendas = [];
                    this.agendas = fechas;


                    //$("#modalagenda").modal("show");

                   // $('#exampleModalCenter').modal('hide');
                }

            },


            buscarAtencion(param){

                let urlexcel = `${intranetBaseUrl}/atencion/atender/${param.id}`;
                location.href=urlexcel;
            },

            limpiaragendas(data){
            let vm = this;
            swalAlertConfirm(`¿Seguro que quiere eliminar la agenda programada <b>${data}</b>?`, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){

                            showPreloader();
                            axios.delete(`${appApiUrl}/agenda/${data}`)
                                .then(function (response) {
                                    //hidePreloader();
                                    let result = response.data


                                    if (result.status) {
                                        successMessage(result.message, appName);
                                        vm.listarAgenda();
                                    } else
                                        errorMessage(result.message, appName);
                                })
                                .catch(function (error) {
                                    hidePreloader();
                                    errorMessage(appErrorMessage, appName);
                                    console.log(error);
                                });
                        }
                    });

        },

        },


        created(){
          this.listarAgenda();
          const vm = this;
            bus.$on('actualizaragendas',()=>{
                vm.listarAgenda();
            })
        },

        components:{
            dayjs
        }

    }
</script>

<style scoped>
    .navbar-dark .mailbox .message-center {
        height: auto;
    }
    .btndrop{
        background: red;
    border: none;
    border-radius: 20px;
    font-size: 20px;
    padding: 1px 10px 1px;
    color: white;
    position: absolute;
    left: 88%;
    }
    span.txtcliente {
    color: black;
    font-weight: 500;
    font-size: 16px;
}
.time{
    font-size: 16px !important;
    color: #00c292;
}
span.time:hover {
    color: #00c292;
}

h6.txtprograma {
    font-size: 16px;
    color: #3509d3;
    padding-bottom: 0px;
    margin: 0;
}
button.btnuser {
    background: #3509d3;
    border: none;
    border-radius: 9px;
    font-size: 20px;
    padding: 5px 11px 5px;
    color: white;
    position: absolute;
    left: 77%;
}
.listadoalert{
    list-style: none;
    margin: 0;
    padding: 0;
}
.listado{
    display: flex;
    align-items: center;
    padding: 10px;
    border-top: 1px solid #e9ecef;
}
.btn.btn-success.btn-circle {
    margin-right: 16px;
}
.modal-header{
    color: white;
    font-size: 16px;
    background-color: #3509d3 !important;
}
.modal-body {
    padding: 0rem 1rem 1rem 1rem;
}
</style>
