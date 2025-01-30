<template>
    <modal :showModal="showModal" @closeModal="closeModal">
        <template v-slot:modal-header-title>
            Importar Participantes desde Excel
        </template>
        <template v-slot:modal-body-main>
            <div class="form-row">
                <!-- <div class="form-group col-12 col-sm-6 col-xl-6">
                    <label class="mb-1">Campaña</label>
                    <select2 :options="campania" @input="buscarEvento()" v-model="cliente.idcampania"
                        :selectValue="cliente.idcampania" placeholder="Seleccione una campaña"
                        keyProperty="idcampania" textProperty="nombrecampania">
                    </select2>
                </div> -->
                <!-- <div class="form-group col-12 col-sm-6 col-xl-6">
                    <label class="mb-1">Evento</label>
                    <select2 :options="eventos" v-model="cliente.idevento"
                        :selectValue="cliente.idevento" placeholder="Seleccione un Evento"
                        keyProperty="idevento" textProperty="nombreevento" id="selectevento">
                    </select2>
                </div> -->
                <div class="form-group col-12">
                    <label>Archivo Excel</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" ref="fileexcel" @change="handleFileUpload"
                                accept=".XLSX, .CSV" id="fileexcel">
                            <label class="custom-file-label" for="fileexcel" v-text="fileName"></label>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-success" type="button"
                                @click="importarExcel()">
                                    <i class="fa fa-save"></i>
                                    Guardar
                                </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </modal>
</template>
<script>
    import Modal from './../../utils/Modal';
    import Select2 from './../../utils/Select2';

    export default {
        data() {
            return {
                showModal: false,
                fileName: '',
                cliente:{
                    idcampania:0,
                    idevento:0,
                },
                campania:[],
                eventos:[],
            }
        },
        methods: {
            closeModal(event) {
                this.showModal = false;
                this.fileName = '';
                this.cliente={
                    idcampania:0,
                    idevento:0,
                };
            },
            openModal() {
                this.showModal = true;
                this.fileName = '';
            },
            handleFileUpload(event) {
                if (event.target.files[0]) {
                    this.fileName = event.target.files[0].name;
                }else
                    this.fileName = '';
            },
            importarExcel() {                
                let vm = this;

                if (this.$refs.fileexcel.files[0]) {
                    
                        let formData = new FormData();
                        formData.append('file', this.$refs.fileexcel.files[0]);

                        axios.post(`${appApiUrl}/import`, formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function (response) {
                            hidePreloader();
                            let result = response.data;

                            if (result.status) {
                                successMessage(result.message, appName);
                                vm.showModal = false;
                                vm.fileName = '';
                                vm.$parent.listarImportaciones(); 
                            } else
                                warningMessage(result.message, appName);
                        })
                        .catch(function (error) {
                            hidePreloader();
                            errorMessage(appErrorMessage, appName);
                            console.log(error);
                        })
                }else{
                    warningMessage('Seleccione un archivo Excel', appName);
                }
            },

            /* buscarEvento(){
                this.cliente.idevento = 0; 
                this.eventos = [];

                let vm = this;
                axios.get(`${appApiUrl}/evento/getevento`, {params: {idcampania:this.cliente.idcampania}})
                .then(function (response) {                    
                    vm.eventos = response.data;
                    
                })
                .catch(function (error) {
                    errorMessage(appErrorMessage, appName);
                    console.log(error);
                })
            },

            listarCampania() {
                let vm = this;
                axios.get(`${appApiUrl}/campania/select`)
                    .then(function (response) {
                        vm.campania = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            }, */
        },
        mounted() {
        },
        components: {
            Modal,
            Select2
        }
    }

</script>
<style scoped>
.custom-file-label::after{
    content: "Seleccionar archivo";
}
</style>
