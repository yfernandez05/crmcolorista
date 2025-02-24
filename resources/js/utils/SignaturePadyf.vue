<template>
  <div>
    <div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="signatureModalLabel">Registrar Firma para:  <span class="font-weight-bold">{{ localIsasignedSedeEntrega.detalle }}</span>
            </h5>
            <button type="button" class="close" @click="cerrarModal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="canvas-container">
              <canvas ref="canvas" class="signature-pad"></canvas>
            </div>
            <button @click="clearCanvas" class="btn btn-secondary mt-2">Limpiar</button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal">Cerrar</button>
            <button type="button" class="btn btn-primary" @click="emitirFirma">Guardar Firma</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    isasignedsedeentrega: {
      type: String,
      default: '' // Por defecto, vacío
    }
  },
  data() {
    return {
      isDrawing: false,
      context: null,
      lastX: null,
      lastY: null,
      sededata: '',
      selectedSede: '',      
      sedes: [],
      isChangingSede: false,
      localIsasignedSedeEntrega: this.isasignedsedeentrega, // Usar una propiedad de datos
    };
  },
  mounted() {
    this.sedes = [
      { id: 1, nombre: 'Campus Villa' },
      { id: 2, nombre: 'Campus Norte' },
      { id: 3, nombre: 'Campus Ate' },
      { id: 4, nombre: 'Campus Aramburu' },
    ];
    /* this.setupCanvas(); */
  },
  methods: {
    setupCanvas() {
      const canvas = this.$refs.canvas;
      this.context = canvas.getContext('2d');
      canvas.width = 800; // Ancho fijo
      canvas.height = 400; // Alto fijo

      this.context.fillStyle = '#fff';
      this.context.fillRect(0, 0, canvas.width, canvas.height);
      this.context.strokeStyle = '#000'; // Color de la firma
      this.context.lineWidth = 6; // Grosor de la línea
      this.context.lineJoin = 'round'; // Esquinas redondeadas
      this.context.lineCap = 'round'; // Extremos redondeados

      // Eventos de mouse
      canvas.addEventListener('mousedown', this.startDrawing);
      canvas.addEventListener('mouseup', this.stopDrawing);
      canvas.addEventListener('mouseleave', this.stopDrawing);
      canvas.addEventListener('mousemove', this.draw);

      // Eventos táctiles
      canvas.addEventListener('touchstart', this.startDrawing);
      canvas.addEventListener('touchend', this.stopDrawing);
      canvas.addEventListener('touchcancel', this.stopDrawing);
      canvas.addEventListener('touchmove', this.draw);
    },
    abrirModal(isasignedsedeentrega) {
      this.localIsasignedSedeEntrega = isasignedsedeentrega; // Asegurarte de asignar el valor antes
      this.$nextTick(() => {
        this.setupCanvas(); // Forzar la inicialización del canvas
      });
      $('#signatureModal').modal('show');
    },
    enableSedeChange() {
      this.isChangingSede = true; // Mostrar el select para cambiar sede
    },
    disabledSedeChange() {
      this.isChangingSede = false; // Mostrar el select para cambiar sede
    },
    startDrawing(event) {
      this.isDrawing = true;
      const [x, y] = this.getMousePosition(event);
      this.lastX = x;
      this.lastY = y;
      this.context.beginPath();
      this.context.moveTo(x, y);
    },
    stopDrawing() {
      this.isDrawing = false;
      this.context.closePath();
    },
    draw(event) {
      if (!this.isDrawing) return;
      event.preventDefault(); // Evitar el comportamiento predeterminado (scrolling, etc.)
      const [x, y] = this.getMousePosition(event);
      // Aquí podrías ajustar la tasa de actualización si quieres que el trazo sea más suave
      this.context.lineTo(x, y); // Dibuja una línea hacia la nueva posición del mouse/touch
      this.context.stroke();
      this.lastX = x;
      this.lastY = y;
    },
    smoothLineTo(x, y) {
      const cp1x = (this.lastX + x) / 2; // Control point 1 X
      const cp1y = (this.lastY + y) / 2; // Control point 1 Y
      this.context.beginPath();
      this.context.moveTo(this.lastX, this.lastY); // Moverse al último punto
      this.context.bezierCurveTo(this.lastX, this.lastY, cp1x, cp1y); // Curva Bezier para suavizado
      this.context.stroke();
      this.lastX = x;
      this.lastY = y;
    },
    clearCanvas() {
      this.context.clearRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
      this.context.fillStyle = '#fff';
      this.context.fillRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
    },
    emitirFirma() {
      const firmaDataUrl = this.$refs.canvas.toDataURL('image/png');
      this.$emit('signatureAdded', firmaDataUrl); // Emitir la firma como Data URL
      this.cerrarModal(); // Cierra el modal
    },
    cerrarModal() {
      $('#signatureModal').modal('hide'); // Cerrar modal usando jQuery
      this.clearCanvas();
      this.selectedSede = '';
      this.disabledSedeChange();
    },
    getMousePosition(event) {
      const rect = this.$refs.canvas.getBoundingClientRect();
      const scaleX = this.$refs.canvas.width / rect.width; // Relación de escala en el eje X
      const scaleY = this.$refs.canvas.height / rect.height; // Relación de escala en el eje Y
      // Manejar eventos táctiles y de mouse
      const clientX = event.touches ? event.touches[0].clientX : event.clientX;
      const clientY = event.touches ? event.touches[0].clientY : event.clientY;
      const x = (clientX - rect.left) * scaleX; // Posición ajustada del cursor
      const y = (clientY - rect.top) * scaleY; // Posición ajustada del cursor
      return [x, y];
    },
  },
};
</script>
<style>
.canvas-container {
  position: relative;
  width: 100%;
  height: 0;
  padding-bottom: 50%; /* Relación de aspecto 800x400 */
  overflow: hidden;
}

.signature-pad {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 1px solid #ccc; /* Estilo del borde del canvas */
}
</style>
