<template>
  <div class="wrapper">
    <header>{{ title }}</header>
    <form @click="clickInput">
      <input ref="fileInput" class="file-input" type="file" name="file" :accept="acceptedTypes" multiple hidden @change="handleFileChange">
      <i class="fas fa-cloud-upload-alt"></i>
      <p>{{ uploadText }}</p>
    </form>
    <section class="progress-area">
      <ul class="p-0">
        <li v-for="(file, index) in files" :key="index" class="row">
          <div class="content upload">
            <i class="fas fa-file-alt"></i>
            <div class="details">
              <span class="name">{{ file.name }}</span>
              <span class="size">{{ formatSize(file.size) }}</span>
            </div>
          </div>
          <i class="fas fa-times icon-close c-pointer text-danger" @click="removeFile(index)"></i>
        </li>
      </ul>
    </section>
    <section class="uploaded-area"></section>
  </div>
</template>

<script>
export default {
  props: {
    value: Array, // Propopiedad contener archivos seleccionados
    title: {
      type: String,
      default: 'Cargar Archivos'
    },
    acceptedTypes: {
      type: String,
      default: ".pdf,.jpg,.jpeg,.png,.doc,.docx,.xml"
    },
    uploadText: {
      type: String,
      default: "Selecciona un archivo"
    },
    maxSizeMb: {
      type: Number,
      default: 1
    },
    allowedExtensions: {
      type: Array,
      default: () => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xml']
    },
    errorMessage: {
      type: String,
      default: 'Formato de archivo no válido'
    }
  },
  data() {
    return {
      files: []
    };

  },
  watch: {
    value: {
      immediate: true,
      handler(newVal) {
        // Actualiza los archivos cuando cambia la propiedad value
        this.files = newVal;
      }
    },
    files: {
      deep: true,
      handler(newVal) {
        // Emitir el evento input cada vez que los archivos cambien
        this.$emit('input', newVal);
      }
    }
  },
  methods: {
    clickInput() {
      this.$refs.fileInput.click();
    },
    handleFileChange(event) {
      const files = event.target.files;
      for (let i = 0; i < files.length; i++) {
        const file = files[i];

        // Validar la extensión del archivo
        const extension = file.name.split('.').pop().toLowerCase();
        if (!this.allowedExtensions.includes(extension)) {
          alert(this.errorMessage);
          return;
        }

        // Validar el tamaño del archivo
        const maxSizeBytes = this.maxSizeMb * 1024 * 1024;
        if (file.size > maxSizeBytes) {
          alert('El tamaño del archivo no debe superar ' + this.maxSizeMb + 'MB');
          return;
        }

        // Agregar el archivo si pasa las validaciones
        this.files.push(file);
      }

      // Emitir el evento input cada vez que los archivos cambien
      this.$emit('input', this.files);
    },
    formatSize(size) {
      if (size < 1024) {
        return size + ' B';
      } else if (size < 1024 * 1024) {
        return (size / 1024).toFixed(2) + ' KB';
      } else {
        return (size / (1024 * 1024)).toFixed(2) + ' MB';
      }
    },
    removeFile(index) {
      this.files.splice(index, 1);
    },
  },
};
</script>



<style scoped>
.c-pointer{
    cursor: pointer;
}
::selection{
  color: #fff;
  background: #6990F2;
}
.wrapper{
  width: 100%;
  background: #fff;
  border-radius: 5px;
  padding: 30px;
  /*box-shadow: 7px 7px 12px rgba(0,0,0,0.05);*/
}
.wrapper header{
  color: #343a40;
  font-size: 20px;
  font-weight: 600;
  text-align: center;
}
.wrapper form{
  height: 100px;
  display: flex;
  cursor: pointer;
  margin: 15px 0 30px 0;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  border-radius: 5px;
  border: 2px dashed #3509d3;
}
form :where(i, p){
  color: #3509d3;
}
form i{
  font-size: 35px;
}
form p{
  margin-top: 0;
  margin-bottom: 0;
  font-size: 16px;
  text-align: center;
}

section .row{
    margin-bottom: 10px;
    background: #f8fbe0d6;
    list-style: none;
    padding: 15px 20px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: nowrap;
}
section .row i{
  color: #3509d3;
  font-size: 30px;
  margin-right: 0.5em;
}
section .row .icon-close{
  font-size: 20px;
  margin-right: 0.5em;
}
section .details span{
  font-size: 14px;
}
section .details span.size{
  font-size: 11px;
  color: gray;
}
.progress-area .row .content{
    display: flex;
    justify-content: flex-start;
    flex-wrap: nowrap;
}
.progress-area .details{
    display: flex;
    margin-bottom: 7px;
    justify-content: space-between;
    flex-wrap: wrap;
    flex-direction: column;
}
.progress-area .content .progress-bar{
  height: 6px;
  width: 100%;
  margin-bottom: 4px;
  background: #fff;
  border-radius: 30px;
}
.content .progress-bar .progress{
  height: 100%;
  width: 0%;
  background: #6990F2;
  border-radius: inherit;
}
.uploaded-area{
  max-height: 232px;
  overflow-y: scroll;
}
.uploaded-area.onprogress{
  max-height: 150px;
}
.uploaded-area::-webkit-scrollbar{
  width: 0px;
}
.uploaded-area .row .content{
  display: flex;
  align-items: center;
}
.uploaded-area .row .details{
  display: flex;
  margin-left: 15px;
  flex-direction: column;
}
.uploaded-area .row .details .size{
  color: #404040;
  font-size: 11px;
}
.uploaded-area i.fa-check{
  font-size: 16px;
}
  </style>
