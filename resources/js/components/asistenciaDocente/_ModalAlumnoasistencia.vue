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
                    <label class="mb-0">F. Nacimiento</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="alumno.fechanacimiento"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">DNI</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="alumno.dni"></span>
                </div>

            </div>

            <div class="table-responsive">
                <table id="productos" class="table table-sm table-hover table-striped table-bordered mb-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cod</th>
                            <th>F. Asistencia</th>
                            <th v-if="authenticatedUser.rol_id <= 2">Docente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asiste in asistencias" :key="asiste.id">
                            <td v-text="asiste.id"></td>
                            <td v-text="asiste.fechaasis"></td>
                            <td v-if="authenticatedUser.rol_id <= 2" v-text="asiste.user?.nombrecompleto"></td>
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
        showDetail(data, idcurso){
            console.log(data, idcurso);
            this.alumno = data;
            this.obtenerasistencias(this.alumno, idcurso);
            this.showModal = true;
        },
        obtenerasistencias(data,idcurso) {
            showPreloader();

            let datasearch = {
                alumno_id : data.id,
                curso_id : idcurso,

            }
            let vm = this;
            axios.get(`${appApiUrl}/asistenciadocente/alumno`, { params: datasearch })
            .then(response => {
                console.log(response.data);
                    hidePreloader();
                    if (!response.data || response.data.length === 0) {
                        warningMessage(`No se encontró ninguna asistencia para el alumno ${data.nombre}`, appName);
                    } else {
                        vm.asistencias = response.data;
                        console.log(vm.asistencias)
                    }
                })
                .catch(error => {
                    hidePreloader();
                    console.error(error);
                });
        },
    },

    components:{
        Modal
    }
}
</script>
