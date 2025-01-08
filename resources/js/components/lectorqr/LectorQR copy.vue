<template>
    <div class="qr-scanner-container">
      <h1>Scan from WebCam:</h1>
      <div id="video-container">
        <video ref="videoElement" id="qr-video"></video>
      </div>
      <div>
        <label>
          Highlight Style
          <select id="scan-region-highlight-style-select" @change="changeHighlightStyle">
            <option value="default-style">Default style</option>
            <option value="example-style-1">Example custom style 1</option>
            <option value="example-style-2">Example custom style 2</option>
          </select>
        </label>
      </div>
      <b>Detected QR code:</b>
      <span id="cam-qr-result">{{ qrResult || 'None' }}</span>
      <br>
      <button @click="startScanner">Start</button>
      <button @click="stopScanner">Stop</button>
    </div>
  </template>
  
  <script>
  import QrScanner from 'qr-scanner';
  
  export default {
    data() {
      return {
        qrResult: null,
        scanner: null,
        /* highlightStyle: "default-style" */
      };
    },
    mounted() {
      this.setupScanner();
    },
    methods: {
      setupScanner() {
        const videoElement = this.$refs.videoElement;
  
        this.scanner = new QrScanner(videoElement, result => {
          console.log('Código QR escaneado:', result);
          this.qrResult = result.data; // Guardar el resultado del escaneo
        }, {
          onDecodeError: error => {
            console.error('Error al decodificar el código QR:', error);
            this.qrResult = 'Error al decodificar el código QR';
          },
          highlightScanRegion: true,
          highlightCodeOutline: true
        });
  
        this.scanner.start();
      },
      startScanner() {
        if (this.scanner) {
          this.scanner.start();
        } else {
          this.setupScanner();
        }
      },
      stopScanner() {
        if (this.scanner) {
          this.scanner.stop();
        }
      },
      changeHighlightStyle(event) {
        this.highlightStyle = event.target.value;
        const videoContainer = document.getElementById('video-container');
        videoContainer.className = this.highlightStyle;
        if (this.scanner) {
          this.scanner._updateOverlay(); // Reajustar el área de resaltado
        }
      }
    },
    beforeDestroy() {
      if (this.scanner) {
        this.scanner.stop();
      }
    }
  };
  </script>
  
  <style scoped>
  .qr-scanner-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
  }
  
  /* Estilos de resaltado personalizados */
  #video-container.example-style-1 .scan-region-highlight-svg,
  #video-container.example-style-1 .code-outline-highlight {
    stroke: #64a2f3 !important;
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
  </style>
  