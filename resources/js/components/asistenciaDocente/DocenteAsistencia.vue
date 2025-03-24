<template>
      <div>
    <main-content>
        <template v-slot:card-header-title>
            Alumnos Asistencia
        </template>
        <template v-slot:card-header-actions>
            <button class="btn btn-sm btn-info waves-effect waves-light" @click="alumnosBuscar()">
                <i class="fas fa-search"></i>
                <span class="d-none d-sm-inline-block">
                    Buscar
                </span>
            </button>
            <button class="btn btn-sm btn-danger waves-effect waves-light">
                <i class="fas fa-times"></i>
                <span class="d-none d-sm-inline-block" @click="limpiarAlumno()">
                    Limpiar
                </span>
            </button>
            <!-- <router-link :to="{name:'spa.alumno.registrar'}" class="btn btn-sm btn-success waves-effect waves-light">
                 <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline-block ">
                    Nuevo
                </span>
            </router-link>     -->
        </template>
        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('curso_id')}">
                    <label>Curso <small class="text-danger">(*)</small></label>
                    <select2 :options="cursos" v-model="asistencia.curso_id" :selectValue="asistencia.curso_id"
                        placeholder="Seleccione un curso" keyProperty="id" textProperty="nombre" @input="alumnosBuscar()">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('curso_id')"
                        v-text="showError('curso_id').errorDetail"></small>
               </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Nombre</label>
                    <input type="text" class="form-control" v-model="asistencia.nombre" @keyup.enter="alumnosBuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Apellido</label>
                    <input type="text" class="form-control" v-model="asistencia.apellido" @keyup.enter="alumnosBuscar()">
                </div>
                <!-- <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Correo</label>
                    <input type="text" class="form-control" v-model="asistencia.correo" @keyup.enter="alumnosBuscar()">
                </div> -->
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="p-2">Acciones</th>
                            <th class="p-2">Asistencia</th>
                            <th class="p-2">Cod</th>
                            <th class="p-2">Nombre Completo</th>
                            <!-- <th class="p-2">Curso</th> -->
                            <!-- <th class="p-2">Estado</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asiste in asistencias" :key="asiste.id">
                            <td>
                                <row-actions :rowData="asiste" @rowItemActions="rowItemActions"  :activeEdit="false"
                                :activeDelete="false" :activeShow="true">

                                <button data-toggle="tooltip" data-placement="top" title="Marcar Asistencia"  @click="marcarAsistencia(asiste.id)" class="btn btn-sm btn-outline-primary waves-effect waves-light border-0 mr-1">
                                          <i class="fas fa-user-check"></i>
                                </button>
                                </row-actions>
                            </td>
                            <td>
                                <span class="badge badge-pill py-1 px-3 m-1" :class="asiste.asistencia_hoy ? 'badge-success' : 'badge-secondary'">
                                    {{ asiste.asistencia_hoy ? 'Marcado' : 'Pendiente' }}
                                </span>
                            </td>
                            <td v-text="asiste.id"></td>
                            <td v-text="asiste.nombrecompleto"></td>
                            <!-- <td>
                                <ul>
                                    <li v-for="matricula in asiste.matriculas" :key="matricula.id">
                                        <span v-for="plan in matricula.carrera.plan_estudios" :key="plan.id">
                                            <span v-for="detalle in plan.detalles" :key="detalle.id">
                                                {{ detalle.curso.nombre }}
                                            </span>
                                        </span>
                                    </li>
                                </ul>
                            </td> -->

                        </tr>
                    </tbody>

                </table>
            </div>
            <pagination-links :pagination="pagination" @changePerPage="changePerPage">
            </pagination-links>

        </template>
    </main-content>
    <ModalAlumnoasistencia ref="ModalAlumnoasistencia"></ModalAlumnoasistencia>
</div>
</template>


<script>
    import MainContent from './../../utils/MainContent';
    import PaginationLinks from './../../utils/PaginationLinks';
    import RowActions from './../../utils/RowActions';
    import moment, { now, relativeTimeThreshold } from 'moment';
    import ModalAlumnoasistencia from './_ModalAlumnoasistencia';
    import Select2 from './../../utils/Select2';
    import dayjs from 'dayjs';
    var minMax = require('dayjs/plugin/minMax')
    dayjs.extend(minMax)

    export default {
        data() {
            return {
                cursos: [],
                errors: [],
                pagination: {},
                filters: {},
                asistencia: {
                    nombre: '',
                    apellido: '',
                    correo: '',
                    curso_id: '',
                },
                asistencias: [],
            }
        },

        methods: {

            listaraAsistencias() {
                showPreloader();
                let vm = this;
                axios.get(`${appApiUrl}/asistenciadocente`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();

                        let result = response.data;

                        if (result.warning) {
                            warningMessage(result.message, 'Asistencia');
                            
                        } else {
                            vm.asistencias = result.data;
                            vm.pagination = result;
                            delete vm.pagination.data;
                        }
                        
                    })
                    .catch(function (error) {
                        hidePreloader();
                        console.log(error);
                    })
            },

            listarcurso() {
                axios.get(`${appApiUrl}/curso/selectdocente`)
                .then(response => {
                    this.cursos = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
            },

            changePerPage(event) {
                console.log('paginacion',event);
                this.filters.page = event.page;
                this.filters.perpage = event.perpage;
                this.listaraAsistencias();
            },
            rowItemActions(event) {
                switch (event.action) {
                    case 'show':
                        this.$refs.ModalAlumnoasistencia.showDetail(event.data, this.asistencia.curso_id);
                }
            },
            alumnosBuscar() {
                this.filters = {};
                this.filters.page = 1;
                if(!this.asistencia.curso_id.length){
                    this.limpiarAlumno();
                }

                if (this.asistencia.curso_id.length)
                    this.filters.curso_id = this.asistencia.curso_id;
                if (this.asistencia.nombre.length)
                    this.filters.nombre = this.asistencia.nombre;
                if (this.asistencia.apellido.length)
                    this.filters.apellido = this.asistencia.apellido;
                if (this.asistencia.correo.length)
                    this.filters.correo = this.asistencia.correo;

                this.listaraAsistencias();
            },
            limpiarAlumno(){
                this.asistencia.curso_id = '';
                this.asistencia.nombre = '';
                this.asistencia.apellido = '';
                this.asistencia.correo = '';
                this.asistencias = [];
            },
            marcarAsistencia(alumnoId) {
                if(this.authenticatedUser.rol_id <= 2){
                    return warningMessage('Solo el docente puede marcar la asistencia del alumno', 'Asistencia');
                }
                const hoy = moment().format('YYYY-MM-DD');

                // Verificar si la asistencia ya está marcada hoy
                let asistenciaAlumno = this.asistencias.find(asistencia => asistencia.id === alumnoId);

                if (asistenciaAlumno && asistenciaAlumno.asistencia_hoy) {
                    const nombreAlumno = asistenciaAlumno.nombrecompleto || 'Desconocido';
                    warningMessage(`La asistencia para el alumno ${nombreAlumno} ya fue marcada hoy.`, 'Asistencia');
                    return;
                }


                // Preparar la asistencia a enviar
                const nuevaAsistencia = {
                    alumno_id: alumnoId,
                    curso_id: this.asistencia.curso_id,
                };

                showPreloader();

                let vm = this;
                // Enviar al backend
                axios.post(`${appApiUrl}/asistenciadocente`, nuevaAsistencia)
                    .then(({ data }) => {
                        hidePreloader();

                        if (data.status) {
                            // ACTUALIZAR ESTADO LOCALMENTE
                            let asistenciaAlumno = this.asistencias.find(asistencia => asistencia.id === alumnoId);
                            if (asistenciaAlumno) {
                                asistenciaAlumno.asistencia_hoy = true;
                            }

                            const nombreAlumno = asistenciaAlumno ? asistenciaAlumno.nombrecompleto : 'Desconocido';
                            successMessage(`Asistencia marcada para el alumno ${nombreAlumno} en la fecha ${hoy}`, 'Asistencia');
                        } else if(data.warning) {
                            warningMessage(data.message, 'Asistencia');
                        }else{
                            errorMessage(data.message, 'Asistencia');
                        }
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        console.log(error);
                    });
            },


            formatDate(value, fmt = 'D MMM YYYY') {
                return (value == null) ?
                    '' :
                    moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            selectToday(emit) {
                emit([new Date(), new Date()]);
            },

            validateFields() {
                this.errors = [];

                /* if (!this.planestudio.costo) {
                    this.setError('costo', 'El campo costo es obligatorio');
                }
                if (!this.planestudio.duracion_meses) {
                    this.setError('duracion_meses', 'El campo duracion es obligatorio');
                }
                if (!this.planestudio.carrera_id) {
                    this.setError('carrera_id', 'El campo carrera es obligatorio');
                }
                if (!this.planestudio.ciclo_id) {
                    this.setError('ciclo_id', 'El campo ciclo es obligatorio');
                } */

                // if (!this.planestudio.detalle) {
                // this.setError('detalle', 'El campo detalle es obligatorio');
                // }
                // if (!Array.isArray(this.planestudio.detalles)) {
                // this.setError('curso_id', 'Debe seleccionar un Curso.');
                // }
                return this.errors;
            },
            setError(keyModel, errorDetail) {
                this.errors.push({
                    keyModel: keyModel,
                    errorDetail: errorDetail
                });
            },
            errorExists(keyModel){
                return this.errors.filter(err => err.keyModel === keyModel).length;
            },
            showError(keyModel){
                return this.errors.find(err => err.keyModel === keyModel);
            },

        },
        mounted() {
            this.listarcurso();
            this.listaraAsistencias();

        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            ModalAlumnoasistencia,
            Select2
        }
    }

</script>
