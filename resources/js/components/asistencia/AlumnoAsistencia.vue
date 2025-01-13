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

                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Nombre</label>
                    <input type="text" class="form-control" v-model="asistencia.nombre" @keyup.enter="alumnosBuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Apellido</label>
                    <input type="text" class="form-control" v-model="asistencia.apellido" @keyup.enter="alumnosBuscar()">
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 ">
                    <label>Correo</label>
                    <input type="text" class="form-control" v-model="asistencia.correo" @keyup.enter="alumnosBuscar()">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="p-2">Acciones</th>
                            <th class="p-2">Asistencia</th>
                            <th class="p-2">Cod</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Apellido</th>
                            <th class="p-2">Correo</th>
                            <th class="p-2">Curso</th>
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
                                <span v-if="asiste.Asistencia === 'Asistencia Marcada'" class="badge badge-success">Asistencia Marcada</span>
                                <span v-else class="badge badge-secondary">Pendiente</span>
                            </td>
                            <td v-text="asiste.id"></td>
                            <td v-text="asiste.nombre"></td>
                            <td v-text="asiste.apellido"></td>
                            <td v-text="asiste.correo"></td>
                            <td v-text="asiste.curso"></td>
                            <!-- <td >
                                <span class="badge badge-pill py-1 px-3"
                                    :class="asiste.isactive ? 'badge-success':'badge-danger'"
                                     v-text="asiste.statename">
                                </span>
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
    import dayjs from 'dayjs';
    var minMax = require('dayjs/plugin/minMax')
    dayjs.extend(minMax)

    export default {
        data() {
            return {
                alumnos: [],
                pagination: {},
                filters: {},
                asistencia: {
                    nombre: '',
                    apellido: '',
                    correo: '',
                },
                asistencias: [],
            }
        },

        methods: {

            listaraAsistencias() {
                showPreloader();
                let vm = this;
                axios.get(`${appApiUrl}/asistencias`, {
                        params: this.filters
                    })
                    .then(function (response) {

                        hidePreloader();
                        vm.asistencias = response.data.data;
                        vm.pagination = response.data;
                        delete vm.pagination.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        console.log(error);
                    })
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
                        this.$refs.ModalAlumnoasistencia.showDetail(event.data);
                }
            },
            alumnosBuscar() {
                this.filters = {};
                this.filters.page = 1;

                if (this.asistencia.nombre.length)
                    this.filters.nombre = this.asistencia.nombre;
                if (this.asistencia.apellido.length)
                    this.filters.apellido = this.asistencia.apellido;
                if (this.asistencia.correo.length)
                    this.filters.correo = this.asistencia.correo;

                this.listaraAsistencias();
            },
            limpiarAlumno(){
                this.asistencia.nombre = '';
                this.asistencia.apellido = '';
                this.asistencia.correo = '';
            },
            marcarAsistencia(alumnoId) {

                const hoy = moment().format('YYYY-MM-DD'); // Formato YYYY-MM-DD
                const asistenciaHoy = this.asistencias.find(asistencia => asistencia.alumnoId === alumnoId && new Date(asistencia.fechaAsistencia).toLocaleDateString() === hoy);

                if (asistenciaHoy) {
                    const alumno = this.asistencias.find(alum => alum.id === alumnoId);
                    const nombreAlumno = alumno ? alumno.nombre : 'desconocido';
                    // Mostrar mensaje de error si ya se marcó asistencia hoy
                    warningMessage(`La asistencia para el alumno ${nombreAlumno} ya fue marcada hoy.`, 'Asistencia');
                    return;
                }

                const fechaAsistencia = moment().format('YYYY-MM-DD HH:mm:ss');
                const fechaAsistenciaFormateada = moment().format('YYYY-MM-DD');
                const nuevaAsistencia = { alumno_id: alumnoId, fecha_asistencia: fechaAsistencia , fecha: fechaAsistenciaFormateada};
                // Enviar la asistencia al backend
                showPreloader();
                axios.post(`${appApiUrl}/asistencias`, nuevaAsistencia)
                .then(response => {
                    hidePreloader();
                    let result = response.data;

                    if (result.status) {

                        //this.asistencias.push(nuevaAsistencia);
                        this.listaraAsistencias();
                        const alumno = this.asistencias.find(alum => alum.id === alumnoId);
                        const nombreAlumno = alumno ? alumno.nombre : 'desconocido';
                        console.log(`Asistencia marcada para el alumno ${nombreAlumno} en la fecha ${fechaAsistencia}`);
                        // Mostrar mensaje de éxito
                        successMessage(`Asistencia marcada para el alumno ${nombreAlumno} en la fecha ${fechaAsistencia}`, 'Asistencia');
                    } else {
                        errorMessage(result.message, 'Asistencia');
                    }
                })
                .catch(error => {
                    hidePreloader();
                    if (error.response && error.response.status === 400 && error.response.data.message === 'La asistencia para el alumno ya fue marcada hoy.') {
                        const alumno = this.asistencias.find(alum => alum.id === alumnoId);
                        const nombreAlumno = alumno ? alumno.nombre : 'desconocido';
                        warningMessage(`La asistencia para el alumno ${nombreAlumno} ya fue marcada hoy.`, 'Asistencia');
                    } else {
                        errorMessage('Error al marcar la asistencia', 'Asistencia');
                    }
                    console.error(error);
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

        },
        mounted() {

            this.listaraAsistencias();

        },
        components: {
            MainContent,
            PaginationLinks,
            RowActions,
            ModalAlumnoasistencia
        }
    }

</script>
