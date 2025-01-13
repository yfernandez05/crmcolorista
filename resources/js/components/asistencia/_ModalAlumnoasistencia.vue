<template>
    <modal :showModal="showModal" @closeModal="closeModal" modalSize="modal-lg" headerBgClass="bg-success text-white">
        <template v-slot:modal-header-title>
            Asistencia de alumno #{{alumno.id}}
        </template>
        <template v-slot:modal-body-main>
            <div class="form-row">
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Nombre</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="alumno.nombre"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">apellido</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="alumno.apellido"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">fecha_nac</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="alumno.fecha_nac"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">correo</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="alumno.correo"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Curso</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="alumno.curso"></span>
                </div>


            </div>

            <div class="table-responsive">
                <table id="productos" class="table table-sm table-hover table-striped table-bordered mb-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cod</th>
                            <th>F. Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asiste in asistencias" :key="asiste.id">
                            <td v-text="asiste.id"></td>
                            <td v-text="asiste.fechaasis"></td>
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
            alumno:{
                id:0,
                nombre:'',
            },
            alumnos:[],
            asistencias:[],
        }
    },

    methods:{
        closeModal(event){
            this.showModal = false;
            this.alumno = {
                id:0,
                nombre:'',
            };
            this.alumnos = [];
            this.asistencias = [];
        },
        showDetail(data){
            console.log(data);
            this.alumno = data;
            this.obtenerasistencias(this.alumno.id);
            this.showModal = true;
        },
        obtenerasistencias(data) {
            showPreloader();
            let vm = this;
            axios.get(`${appApiUrl}/asistencias/alumno/${vm.alumno.id}`)
                .then(response => {
                    hidePreloader();
                    if (!response.data.asistencias || response.data.asistencias.length === 0) {
                        warningMessage(`No se encontró ninguna asistencia para el alumno ${this.alumno.nombre}`, appName);
                    } else {
                        this.asistencias = response.data.asistencias;
                    }
                })
                .catch(error => {
                console.error(error);
                });
        },
    },

    components:{
        Modal
    }
}
</script>
