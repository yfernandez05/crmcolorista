<template>
    <modal :showModal="showModal" @closeModal="closeModal" modalSize="modal-lg" headerBgClass="bg-success text-white">
        <template v-slot:modal-header-title>
        Adjuntar Archivo #{{pago.id}}
        </template>
        <template v-slot:modal-body-main>
            <div class="form-row">

                <div class="form-group col-12">
                    <div class="wrapper" v-if="pago.files && pago.files.length > 0">
                        <header>Archivos subidos</header>
                        <section class="progress-area mt-2">
                            <ul class="p-0">
                                <li v-for="(fileup) in pago.files" :key="fileup.file_id" class="row">
                                <div class="content upload">
                                    <i class="fas fa-file-alt"></i>
                                    <div class="details">
                                        <span class="name mb-2"><a target="_blank" :href="fileup.url_patch">{{ fileup.nombre }}</a></span>
                                        <a target="_blank" :href="fileup.url_patch" class="btn-view-file text-success">Ver</a>

                                    </div>
                                </div>
                                <i class="fas fa-times icon-close c-pointer text-danger" @click="removeFile(fileup.file_id)"></i>
                                </li>
                            </ul>
                        </section>
                    </div>

                    <file-uploader v-model="selectedFiles"
                                    :title="'Cargar Archivo'"
                                    :accepted-types="'.pdf,.jpg,.jpeg,.png,.doc,.docx,.xml'"
                                    :upload-text="'Selecciona una Archivo'"
                                    :max-size-mb="1"
                                    :allowed-extensions="['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xml']"
                                    :error-message="'Se permiten archivos PDF, imágenes (JPG, JPEG, PNG) y documentos (DOC, DOCX, XML).'">
                    </file-uploader>
                    <hr class="mt-1 mb-2" />
                    <div>
                        <button type="button" class="btn btn-success waves-effect waves-light" @click="actualizar()">
                        <i class="fa fa-save"></i>
                            Guardar
                        </button>
                    </div>

                </div>

            </div>
        </template>
    </modal>
</template>

<script>
import Modal from './../../utils/Modal';
import vSelect from 'vue-select';
import _ from 'lodash';
import FileUploader from './../../utils/FileUploaderYf.vue';
import {bus} from '../../app';

export default {
    data(){
        return {
            showModal: false,
            pago:{
            },
            pagos:[],
            selectedFiles: [],
            fileIdsRemove:[],

        }
    },

    methods:{
        closeModal(event){

            this.showModal = false;
            this.pagos = [];
        },
        showDetail(data){
            console.log(data);
            this.pago = data;
            this.selectedFiles=[],
            this.fileIdsRemove=[],
            this.showModal = true;

        },
        removeFile(id) {
                console.log("Eliminando archivo con id:", this.fileIdsRemove);
                this.pago.files = this.pago.files.filter(file => {
                    if (file.file_id === id) {
                        this.fileIdsRemove.push(id);
                        return false;
                    }
                    return true;
                });
        },

        actualizar(){
            console.log('Archivos seleccionados:', this.selectedFiles);

            let formData = new FormData();

            for (let key in this.pago) {
                formData.append(key, this.pago[key]);
            }

            const removedFileIdsJson = JSON.stringify(this.fileIdsRemove);
            formData.append('fileIdsRemove', removedFileIdsJson);

            for (let file of this.selectedFiles) {
                formData.append('selectedFiles[]', file);
            }

            axios.post(`${appApiUrl}/pago/${this.pago.id}/adjunto/update`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                let result = response.data;

                if (result.status) {
                    successMessage('Achivo guardado', appName);
                    bus.actualizarpagos();
                    this.closeModal();
                } else {
                    errorMessage(result.message, appName);
                }
            })
            .catch(error => {
                errorMessage(appErrorMessage, appName);
                console.log(error);
            });
        }
    },

    components:{
        Modal,
        vSelect,
        FileUploader
    }
}
</script>

<style scoped>
.v-select .dropdown-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.v-select .dropdown-menu {
    width: 100%;
}

.v-select .dropdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.v-select .dropdown-item button {
    margin-left: 10px;
}
.c-pointer{
    cursor: pointer;
}
.wrapper {
    width: 100%;
    background: #fff;
    border-radius: 5px;
    padding: 30px;
    /* box-shadow: 7px 7px 12px rgba(0,0,0,0.05); */
}
.wrapper header{
    color: #343a40;
    font-size: 20px;
    font-weight: 600;
    text-align: center;
}
section .row {
    list-style: none;
    padding: 15px 20px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: nowrap;
    width: 100%;
    -webkit-text-decoration: none;
    text-decoration: none;
    color: inherit;
    display: flex;
    border-radius: 10px;
    box-shadow: 0 1px 12px 0 rgba(81, 107, 118, .15);
    background: #fff;
    margin-bottom: 1rem;
}
.progress-area .row .content {
    display: flex;
    justify-content: flex-start;
    flex-wrap: nowrap;
}
section .row i {
    color: #3509d3;
    font-size: 30px;
    margin-right: 0.5em;
}
.progress-area .details {
    display: flex;
    margin-bottom: 7px;
    justify-content: space-between;
    flex-wrap: wrap;
    flex-direction: column;
}
.progress-area .details a{
    color: #242424b6;
}
section .row .icon-close {
    font-size: 20px;
    margin-right: 0.5em;
}
.btn-view-file{
    color: #3509d3 !important;
    border: 2px solid #adbf2126;
    padding: 1px 20px;
    text-align: center;
    width: max-content;
    border-radius: 0.5em;
    font-weight: 600;
    background-color: #adbf2126;
    text-transform: uppercase;
}
</style>
