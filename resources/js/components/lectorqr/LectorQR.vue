<template>
    <div>
   
    <!-- Contenedor principal -->
    <main-content style="padding: 0px;">
        <!-- titulo -->
        <template v-slot:card-header-title>
            LECTOR ASISTENCIA QR
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
            <div class="form-row" v-show="authenticatedUser.rol_id == 1">
                <hr class="col-12 text-secondary">
                <div class="form-group col-12" v-if="!cameraOn">
                    <label class="col-12 d-none d-md-block"> </label>
                    <strong>¿Desea registrar la asistencia manualmente?</strong> Por favor, busque al asistente ingresando su dni, nombres y apellidos:
                </div>
                <div class="form-group col-12 col-sm-8">
                    <v-select class="select-vue-customers"
                        v-model="selectedAlumno"
                        :filterable="false"
                        :options="alumnos"
                        :searchable="true"
                        label="nombrecompleto"
                        :loading="loading"
                        @search="onSearch"
                        @input="onSelectClient"
                        placeholder="Escriba al menos 3 caracteres del dni, nombre ó apellidos.">
                        <template #option="data">
                            <div class="my-1">
                                <div class="d-flex no-block text-truncate">
                                    <h5 class="font-weight-bolder mb-0"><strong>{{ data.nombre }} {{ data.apellido }}</strong></h5    >
                                </div>
                                <div class="d-flex no-block text-truncate">
                                    <span>DNI: </span><span class="font-weight-bolder pl-1 pr-3"
                                        v-text="data.dni"></span>
                                </div>
                                <div class="d-flex no-block text-truncate">
                                    <span>Correo: </span><span class="font-weight-bolder pl-1 pr-3"
                                        v-text="data.correo"></span>
                                </div>
                            </div>
                        </template>
                        <template #no-options>
                            <span>No se encontraron opciones</span>
                        </template>
                    </v-select>
                </div> 
                <div class="form-group col-12 col-sm-4" v-if="selectedAlumno" >
                    <div v-if="!selectedAlumno.isassistance || authenticatedUser.idrol == 1" >
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
                <div class="form-group col-12 mb-0" v-if="selectedAlumno">
                    <div class="col-sm-12 p-0">
                        <div class="form-row info-cont-qr-search p-2">
                            <div class="card col-12 col-md-4  cont-details-info">
                                <div class="card-body py-0 py-md-3">
                                    <label class="h6 title-search-qr mb-1 text-info-dark">Nombres:</label>
                                    <p class="card-text small" v-text="selectedAlumno.nombre"></p>
                                </div>
                            </div>
                            <div class="card col-12 col-md-4  cont-details-info">
                                <div class="card-body py-0 py-md-3">
                                    <label class="h6 title-search-qr mb-1 text-info-dark">Apellidos:</label>
                                    <p class="card-text small" v-text="selectedAlumno.apellido"></p>
                                </div>
                            </div>
                            <div class="card col-12 col-md-4 cont-details-info">
                                <div class="card-body py-0 py-md-3">
                                    <label class="h6 title-search-qr mb-1 text-info-dark">DNI:</label>
                                    <p class="card-text text-info small" v-text="selectedAlumno.dni"></p>
                                </div>
                            </div>
                            <div class="card col-12 col-md-4 cont-details-info">
                                <div class="card-body py-0 py-md-3">
                                    <label class="h6 title-search-qr mb-1 text-info-dark">Correo:</label>
                                    <p class="card-text text-info small" v-text="selectedAlumno.correo"></p>
                                </div>
                            </div>
                            <div class="card col-12 col-md-4 cont-details-info">
                                <div class="card-body py-0 py-md-3">
                                    <label class="h6 title-search-qr mb-1 text-info-dark">Asistencia:</label><br>
                                    <span class="badge badge-pill py-1 px-3" :class="selectedAlumno.isassistance ? 'badge-success':'badge-warning'" v-text="selectedAlumno.asistencianame"></span>
                                </div> 
                            </div>
                            <div v-show="selectedAlumno.matriculas && selectedAlumno.matriculas.length" class="card col-12 cont-details-info">
                                <h5 class="mt-3 col title-search-qr mb-1 text-info-dark text-dark">PAGOS:</h5>
                                <div class="d-flex flex-wrap">
                                    <div v-for="(matri, index) in selectedAlumno.matriculas" :key="index" class="evento-item p-2">
                                        <h6 class="title-search-qr mb-1 text-info-dark text-primary">{{ matri.nombre_carrera }} - {{ matri.nombre_ciclo }}</h6>
                                        <div v-if="matri.detalles && matri.detalles.length > 0" class="table-responsive mt-3">
                                            <table class="table table-sm table-striped table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Detalle</th>
                                                        <th>Fecha de Pago</th>
                                                        <th>Estado de Pago</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(detalle) in matri.detalles" :key="detalle.detalle_id">
                                                        <td>{{ detalle.detalle_nombre || 'N/A' }}</td>
                                                        <td>{{ detalle.detalle_fechapago || 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge badge-pill py-1 px-3" :class="getPagoStatusClass(detalle)">{{ getPagoStatusText(detalle) }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- Mensaje si no hay detalles -->
                                        <div v-else>
                                            <p class="text-center">No hay detalles de pago disponibles.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3" v-if="!selectedAlumno.isassistance">
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
import moment, { now, relativeTimeThreshold } from 'moment';

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

            selectedAlumno: null,
            alumnos: [],
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

            this.isProcessing = false;

            this.scanner = new QrScanner(videoElement, result => {
                console.log('Código QR escaneado:', result);

                if (this.isProcessing) {
                    console.log('Lectura bloqueada. Procesando petición anterior.');
                    return;
                }

                this.qrResult = result;
                if (this.qrResult) {
                    this.isProcessing = true;
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
                this.isProcessing = false;
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

                let matriculaTables = '';
                if (result.dataaditional && result.dataaditional.matricula) {
                    result.dataaditional.matricula.sort((a, b) => b.id - a.id);

                    result.dataaditional.matricula.forEach((mat) => {
                        let detallesHtml = '';
                        if (mat.detalles && mat.detalles.length > 0) {
                            mat.detalles.forEach(detalle => {

                                let estadoPago = '';
                                let estadoColor = '';

                                if (detalle.pagado) {
                                    estadoPago = 'Sí Pago';
                                    estadoColor = 'badge-success';
                                } else if (moment(detalle.fechapago).isBefore(moment())) {
                                    estadoPago = 'No Pago';
                                    estadoColor = 'badge-danger';
                                } else {
                                    estadoPago = 'Por Pagar';
                                    estadoColor = 'badge-secondary';
                                }

                                detallesHtml += `
                                    <tr>
                                        <td>${detalle.nombre || 'N/A'}</td>
                                        <td>${detalle.fechapago || 'N/A'}</td>
                                        <td style="align-content: center;"><span class="badge badge-pill py-1 px-3 ${estadoColor}">${estadoPago}</span></td>
                                    </tr>`;
                            });
                        } else {
                            detallesHtml = `<tr><td colspan="3" class="text-center">No hay detalles disponibles</td></tr>`;
                        }

                        matriculaTables += `
                            <h5 class="mt-3 font-weight-bold text-info text-info">${mat.carrera.nombre} - ${mat.ciclo.nombre}</h5>
                            <div class="table-responsive" style="font-size: 14px;">
                                <table class="table table-sm table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Detalle</th>
                                            <th>Fecha Pago</th>
                                            <th>Pagado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${detallesHtml}
                                    </tbody>
                                </table>
                            </div>`;
                    });
                }

                if (result.status) {
                    swalAlertSuccessQR(`Asistente: <br>
                    <h3 class="name-qr-response my-0 text-primary">${result.dataaditional.nombrecompleto}</h3>
                    <p class="email-qr-response mb-1">DNI: ${result.dataaditional.dni}</p> ${infodatahtml}
                    <div style="max-height: 33vh; overflow: overlay;">${matriculaTables}</div>` , messageResult, result.dataaditional.colorClass)
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
            })
            .finally(function () {
                    vm.isProcessing = false;
                    //vm.startScanner();
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
            axios.get(`${appApiUrl}/asistencias/selectsearch`, { params: { search } })
                .then(response => {
                    this.alumnos = response.data;
                    this.loading = false;
                })
                .catch(error => {
                console.error(error);
                this.loading = false;
            });
        },
        onSelectClient(client) {
            this.selectedAlumno = client;
        },
        markAttendance() {
            console.log(this.selectedAlumno);

            if (!this.selectedAlumno) {
                console.warn('No selecciono un alumno');
                warningMessage('Debe Seleccionar un alumno de los resultados para registrar su asistencia.');
                return;
            }

            const fechaAsistencia = moment().format('YYYY-MM-DD HH:mm:ss');
            const fechaAsistenciaFormateada = moment().format('YYYY-MM-DD');
            
            let vm = this;
            let alumnoData = {
                alumno_id : this.selectedAlumno.id,
                fecha_asistencia : fechaAsistencia,
                fecha : fechaAsistenciaFormateada,
            };

            axios.post(`${appApiUrl}/asistencias`, alumnoData)
            .then(function (response) {

                let result = response.data;
                console.log(result);

                if (result.status) {
                    let updatedFields = {
                        alumno_id: alumnoData.alumno_id,
                        asistencianame: "Asistencia Marcada",
                        isassistance: 1
                    };

                    // Actualizar solo los campos específicos de `selectedAlumno`
                    Object.assign(vm.selectedAlumno, updatedFields);
                    vm.replaceClientInArray(updatedFields);
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

        replaceClientInArray(updatedFields) {
            let index = this.alumnos.findIndex(a => a.id === updatedFields.alumno_id);
            if (index !== -1) {
                // Actualiza solo los campos necesarios
                Object.assign(this.alumnos[index], updatedFields);
            }
        },

        getPagoStatusClass(detalle) {
            if (detalle.detalle_pagado === 1) {
                return 'bg-success'; // Pagado
            } else if (detalle.detalle_pagado === 0 && moment(detalle.detalle_fechapago).isBefore(moment(), 'day')) {
                return 'bg-danger'; // No Pago
            } else {
                return 'bg-secondary'; // Por Pagar
            }
        },
        getPagoStatusText(detalle) {
            if (detalle.detalle_pagado === 1) {
                return 'Sí Pago';
            } else if (moment(detalle.detalle_fechapago).isBefore(moment(), 'day')) {
                return 'No Pago';
            } else {
                return 'Por Pagar';
            }
        },

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