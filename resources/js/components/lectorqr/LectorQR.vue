<template>
    <div>
   
    <!-- Contenedor principal -->
    <main-content style="padding: 0px;">
        <!-- titulo -->
        <template v-slot:card-header-title>
            LECTOR QR
        </template>
        <!-- Acciones header -->
        <template v-slot:card-header-actions>

        </template>
        <template v-slot:card-body-main>
            <div class="card border rounded shadow-sm">
                <div class="card-body p-0">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-8 p-3" v-if="!cameraOn">
                            <label class="col-12 d-none d-md-block"> </label>
                            <h5 class="card-title mb-0">Escanea el QR para validar las asistencias </h5>
                        </div>
                        <div class="form-group col-12 col-md-4 p-2" v-if="!cameraOn">
                            <div class="row">                                
                                <div class="form-group col-12 pt-2 mb-0">
                                    <button @click="toggleScanner(true)"  class="btn btn-primary badge-pill display-2 px-4 shadow-sm">
                                        <i class="fas fa-qrcode"></i>
                                        <span class="hide-menu">ESCANEAR QR</span>
                                    </button>
                                </div>
                            </div>                     
                        </div>
                        <div class="form-group col-12" v-show="cameraOn">  
                            <div class="qr-scanner-container">
                                <div id="video-container">
                                    <div class="form-group col-12 p-1 cont-btn-actions-qr" v-if="cameraOn">
                                        <button @click="toggleScanner(false)"  class="btn btn-secondary badge-pill display-2 px-4 shadow-sm btn-off">
                                            <i class="fas fa-eye-slash"></i>
                                            <span class="hide-menu off-text-btn">APAGAR</span>
                                        </button>
                                        <button @click="toggleFlash" class="btn badge-pill display-2 px-4 shadow-sm btn-flash" :class="flashOn ? 'btn-warning' : 'btn-secondary'">
                                            <i class="fas fa-lightbulb"></i>
                                            <span class="hide-menu flash-text-btn">{{ flashOn ? 'ON' : 'OFF' }}</span>
                                        </button>

                                    </div>
                                    <!-- Elemento de video para mostrar la transmisión de la cámara -->
                                    <video ref="videoElement" id="qr-video" width="100%" height="500"></video>
                                </div>
                                <!-- Mostrar el área de resaltado (highlight) -->
                                <div class="scan-region-highlight"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            

            <!-- Fomrulario busqueda -->
            <div class="form-row" v-show="authenticatedUser.idrol <= 2 || authenticatedUser.idrol == 6 || authenticatedUser.idrol == 8">
                <hr class="col-12 text-secondary">
                <div class="form-group col-12" v-if="!cameraOn">
                    <label class="col-12 d-none d-md-block"> </label>
                    <strong v-if="authenticatedUser.idrol == 8">¿Desea registrarlo manualmente?</strong><strong v-else>¿Desea registrar la asistencia manualmente?</strong> Por favor, busque al asistente ingresando su correo electrónico, teléfono o nombres y apellidos:
                </div>
                <div class="form-group col-12 col-sm-8">
                    <v-select class="select-vue-customers"
                        v-model="selectedClient"
                        :filterable="false"
                        :options="clientes"
                        :searchable="true"
                        label="email"
                        :loading="loading"
                        @search="onSearch"
                        @input="onSelectClient"
                        placeholder="Escriba al menos 3 caracteres del correo, telefono ó nombres y apellidos.">
                        <template #option="data">
                            <div class="my-1">
                                <div class="d-flex no-block text-truncate">
                                    <h5 class="font-weight-bolder mb-0"><strong>{{ data.nombres }} {{ data.apellidopaterno }} {{ data.apellidomaterno }}</strong></h5    >
                                </div>
                                <div class="d-flex no-block text-truncate">
                                    <span>Correo: </span><span class="font-weight-bolder pl-1 pr-3"
                                        v-text="data.email"></span>
                                </div>
                                <div class="d-flex no-block text-truncate">
                                    <span>Telefono: </span><span class="font-weight-bolder pl-1 pr-3"
                                        v-text="data.telefono"></span>
                                </div>
                            </div>
                        </template>
                        <template #no-options>
                            <span>No se encontraron opciones</span>
                        </template>
                    </v-select>
                </div> 
                <div class="form-group col-12 col-sm-4" v-if="selectedClient" >
                    <div v-if="!selectedClient.isassistance || authenticatedUser.idrol == 8" >
                        <button class="btn btn-md btn-info waves-effect badge-pill px-4 shadow-sm" @click="markAttendance()">
                            <i class="far fa-check-circle"></i>
                            <span v-if="authenticatedUser.idrol == 8" class="">
                                Registrar
                            </span>
                            <span v-else class="">
                                Registrar Asistencia
                            </span>
                        </button>
                    </div>
                </div>
                <div class="form-group col-12 mb-0" v-if="selectedClient">
                    <div class="col-sm-12 p-0">
                        <div class="form-row info-cont-qr-search p-2">
                            <div class="card col-12 col-md-4  cont-details-info">
                                <div class="card-body py-0 py-md-3">
                                    <label class="h6 title-search-qr mb-1 text-info-dark">Nombres:</label>
                                    <p class="card-text small" v-text="selectedClient.nombres"></p>
                                </div>
                            </div>
                            <div class="card col-12 col-md-4  cont-details-info">
                                <div class="card-body py-0 py-md-3">
                                    <label class="h6 title-search-qr mb-1 text-info-dark">Apellidos:</label>
                                    <p class="card-text small" v-text="selectedClient.apellidopaterno + ' '+ selectedClient.apellidomaterno"></p>
                                </div>
                            </div>
                            <div class="card col-12 col-md-4 cont-details-info">
                                <div class="card-body py-0 py-md-3">
                                    <label class="h6 title-search-qr mb-1 text-info-dark">Email:</label>
                                    <p class="card-text text-info small" v-text="selectedClient.email"></p>
                                </div>
                            </div>
                            <div class="card col-12 col-md-4 cont-details-info">
                                <div class="card-body py-0 py-md-3">
                                    <label class="h6 title-search-qr mb-1 text-info-dark">telefono:</label>
                                    <p class="card-text text-info small" v-text="selectedClient.telefono"></p>
                                </div>
                            </div>
                            <div class="card col-12 col-md-4 cont-details-info">
                                <div class="card-body py-0 py-md-3">
                                    <label class="h6 title-search-qr mb-1 text-info-dark">Asistencia:</label><br>
                                    <span class="badge badge-pill py-1 px-3" :class="selectedClient.isassistance ? 'badge-success':'badge-warning'" v-text="selectedClient.asistencianame"></span>
                                </div> 
                            </div>
                            <div v-show="selectedClient.evento_cliente && selectedClient.evento_cliente.length" class="card col-12 cont-details-info">
                                <div class="d-flex flex-wrap">
                                    <div v-for="(evento, index) in selectedClient.evento_cliente" :key="index" class="evento-item p-2">
                                        <h6 class="title-search-qr mb-1 text-info-dark" v-text="evento.evento.nombreevento"></h6>
                                        <p class="card-text text-dark small" v-text="evento.fechaasistencia"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3" v-if="!selectedClient.isassistance">
                            <p class="font-italic small text-muted mt-1 text-center">
                                El asistente no ha registrado su asistencia. Haga clic en 'Registrar Asistencia'.
                            </p>
                        </div>
                    </div>
                </div>                 
            </div>

        </template>
    </main-content>
</div>
</template>

<script>
import MainContent from './../../utils/MainContent';
import PaginationLinks from './../../utils/PaginationLinks';
import RowActions from './../../utils/RowActions';
import QrScanner from 'qr-scanner';
import vSelect from 'vue-select';
import debounce from 'lodash/debounce';

export default {
    data() {
        return {
            qrResult: null,
            scanner: null,
            highlightStyle: "example-style-1",
            cameraOn: false,
            flashOn: false,
            requestCompleted: false,
            flashSupported: false,
            users: {},

            selectedClient: null,
            clientes: [],
            loading: false,
        };
    },
    methods: {

        toggleScanner(turnOn) {
            if (turnOn) {
                this.startScanner();
            } else {
                this.stopScanner();
            }
        },

        setupScanner(){
            
            const videoElement = this.$refs.videoElement;

            this.scanner = new QrScanner(videoElement, result => {
                console.log('Código QR escaneado:', result);
                this.qrResult = result;
                if (this.qrResult) {
                    this.turnOffFlash();
                    this.stopScanner();
                    this.sendQRResult();
                    playSoundScanner();
                }
            },{
                highlightScanRegion: true,
                highlightCodeOutline: true
            });

            this.scanner.start();
            this.cameraOn = true;
            console.log('SCANER START 1');
        },

        startScanner() {
            if (!this.scanner) {
                this.setupScanner();
            } else {
                this.scanner.start();
                this.cameraOn = true;
            }            
        },

        stopScanner() {
            if (this.scanner) {
                this.scanner.stop();
                this.cameraOn = false;
                this.flashOn = false;
            }
            this.cameraOn = false;
            console.log('Apagado', this.scanner);
        },
       
        toggleFlash() {
            if (this.flashOn) {
                this.turnOffFlash();
            } else {
                this.turnOnFlash();
            }
        },

        async turnOnFlash() {
            this.checkFlashSupport();
            if (!this.flashSupported) {
                console.warn('Flash no compatible con este dispositivo');
                alert('Error al encender el flash');
                return;
            }

            try {
                const track = this.scanner.$video.srcObject.getVideoTracks()[0];
                await track.applyConstraints({
                    advanced: [{ torch: true }]
                });
                this.flashOn = true;
            } catch (error) {
                console.error('Error al encender el flash:', error);
                alert('Error al encender el flash');
            }
        },

        async turnOffFlash() {
            if (!this.flashSupported) {
                return;
            }

            try {
                const track = this.scanner.$video.srcObject.getVideoTracks()[0];
                await track.applyConstraints({
                        advanced: [{ torch: false }]
                });
                this.flashOn = false;
            } catch (error) {
                console.error('Error al apagar el flash:', error);
            }
        },

        async checkFlashSupport() {
            const track = this.scanner.$video.srcObject.getVideoTracks()[0];
            const capabilities = track.getCapabilities();
            this.flashSupported = !!capabilities.torch;
        },

        sendQRResult() {
            if (!this.qrResult) {
                console.warn('Error al leer el qr intentelo nuevamente');
                warningMessage('Error al leer el qr intentelo nuevamente');
                return;
            }
            
            let vm = this;
            axios.post(`${appApiUrl}/validateqr`, { qrCode: this.qrResult.data })
            .then(function (response) {
                hidePreloader();
                let result = response.data;
                let messageResult = 'EL QR ES VALIDO';
                if(result.message && result.message.trim() !== '') messageResult = result.message;

                console.log(result);

                let infodatahtml = '';
                if(result.dataaditional && result.dataaditional.config){
                    const config = result.dataaditional.config;
                    const textColor = config.textColor || 'white';
                    const borderColor = config.border || '2px solid gray';
                    infodatahtml = `<h4 class="name-qr-response anio-qr-response" style="
                                            text-transform: uppercase;
                                            background-color: ${config.color};
                                            padding: 10px;
                                            margin-top: 1em;
                                            color: ${textColor};
                                            border: ${borderColor};
                                            box-shadow: ${config.color === 'white' ? '0px 0px 5px rgba(0, 0, 0, 0.2)' : 'none'}; ">
                                            ${config.description}
                                        </h4>`;
                }                

                if (result.status) {
                    swalAlertSuccessQR(`Asistente: <br>
                    <h3 class="name-qr-response mt-1 mb-0 text-primary">${result.dataaditional.nombre}</h3>
                    <p class="email-qr-response mb-1">${result.dataaditional.correo}</p> ${infodatahtml}` , messageResult, result.dataaditional.colorClass)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            vm.startScanner();
                        }
                    });
                    vm.users = result.dataaditional;
                }  else if (result.warning){
                    swalAlertWarningQR(result.message, appName)
                    .then(function(optionSelected){
                        if(optionSelected.value){
                            vm.startScanner();
                        }
                    });
                } else{
                    errorMessage(result.message, appName);
                    vm.startScanner();
                }
            })
            .catch(function (error) {
                hidePreloader();
                errorMessage(appErrorMessage, appName);
                console.log(error);
            });
        },

        onSearch: debounce(function (search) {
            if (search.length < 3) {
                return;
            }
            this.loading = true;
            this.fetchClients(search);
        }, 500),

        fetchClients(search) {
            axios.get(`${appApiUrl}/cliente/selectsearch`, { params: { search } })
                .then(response => {
                    this.clientes = response.data;
                    this.loading = false;
                })
                .catch(error => {
                console.error(error);
                this.loading = false;
            });
        },
        onSelectClient(client) {
            this.selectedClient = client;
        },
        markAttendance() {
            console.log(this.selectedClient);
            if (!this.selectedClient) {
                console.warn('No selecciono un asistente');
                warningMessage('Debe Seleccionar un asistente de los resultados para registrar su asistencia.');
                return;
            }
            
            let vm = this;
            let clientData = {
                idcliente : this.selectedClient.idcliente,
                idcampania : this.selectedClient.idcampania,
                email : this.selectedClient.email,
            };

            axios.post(`${appApiUrl}/markattendanceentry`, clientData)
            .then(function (response) {

                let result = response.data;
                console.log(result);

                if (result.status) {                    
                    vm.selectedClient =  result.dataaditional;
                    vm.replaceClientInArray(vm.selectedClient);
                    successMessage(result.message, appName);
                } else if(result.warning) {
                    warningMessage(result.message, appName);
                }else{
                    errorMessage(result.message, appName);
                }
            })
            .catch(function (error) {
                errorMessage(appErrorMessage, appName);
                console.log(error);
            });
        },

        replaceClientInArray(updatedClient) {
            let index = this.clientes.findIndex(c => c.idcliente === updatedClient.idcliente);
            if (index !== -1) {            
                this.clientes.splice(index, 1, updatedClient);
            }
        }
    },

    beforeDestroy() {
        // Detener el escáner y limpiar recursos cuando el componente se destruye
        if (this.scanner) {
            this.scanner.stop();
            this.scanner = null; 
            this.cameraOn = false;
            this.flashOn= false;
        }        
    },
    components:{
        MainContent,
        PaginationLinks,
        RowActions,
        vSelect,
    }
}
</script>

<style scoped>
  .qr-scanner-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    /* height: 100vh; */
  }
  
  
  #video-container.example-style-2 {
    position: relative;
    width: max-content;
    height: max-content;
    overflow: hidden;
  }
  #video-container.example-style-2 .scan-region-highlight {
    border-radius: 30px;
    outline: rgba(0, 0, 0, .25) solid 50vmax;
  }
  #video-container.example-style-2 .scan-region-highlight-svg {
    display: none;
  }
  #video-container.example-style-2 .code-outline-highlight {
    stroke: rgba(255, 255, 255, .5) !important;
    stroke-width: 15 !important;
    stroke-dasharray: none !important;
  }

  .cont-btn-actions-qr{
    width: max-content;
    position: absolute;
    z-index: 5;
    right: 1em;
    border-radius: 12px;
    top: 0px;
  }

  .btn-off,.btn-flash{
    height: 65px;
    width: 65px;
    padding: 8px !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px #ffffff solid;
    background-color: #e2e6e9b6;
    margin-bottom: 8px;
  }
  .btn-off .off-text-btn , .btn-flash .flash-text-btn{
    font-size: 0.7em;
    font-weight: 600;
    margin-top: 2px;
  }
  .title-search-qr{
    font-weight: 600;
  }

  .info-cont-qr-search{
    background-color: #f8f8fa;
    border-radius: 1em;
    border: 1px solid #f8f9fa;
  }
  .info-cont-qr-search .card{
    background-color: transparent !important;
    margin-bottom: 10px;
  }
  .text-info-dark{
    color: #242424;
  }

  .cont-details-info .card-body{
    padding: 5px 0.8rem !important;
  }
  .evento-item {
    flex: 1 1 30%; /* Ajusta el tamaño según tus necesidades */
    margin: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
}


  </style>
  <style>
 .select-vue-customers .vs__dropdown-toggle .vs__selected-options .vs__search{
    line-height: 2 !important;
  }
  </style>