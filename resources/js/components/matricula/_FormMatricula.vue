<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('alumno_id')}">
                    <label>Alumno <small class="text-danger">(*)</small></label>
                    <select2 :options="alumnos" v-model="matricula.alumno_id" :selectValue="matricula.alumno_id"
                        placeholder="Seleccione un alumno" keyProperty="id" textProperty="nombrecompleto">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('alumno_id')"
                        v-text="showError('alumno_id').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('carrera_id')}">
                    <label>Carrera <small class="text-danger">(*)</small></label>
                    <select2 :options="carreras" v-model="matricula.carrera_id" :selectValue="matricula.carrera_id"
                        placeholder="Seleccione una carrera" keyProperty="id" textProperty="nombre" @input="obtenerAulasPorCarrera">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('carrera_id')"
                        v-text="showError('carrera_id').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('detalle_aula_id')}">
                    <label>Aula </label>
                    <select2 :options="aulas" v-model="matricula.detalle_aula_id" :selectValue="matricula.detalle_aula_id"
                        placeholder="Seleccione una aula" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('carrera_id')"
                        v-text="showError('carrera_id').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('ciclo_id')}">
                    <label>Modulo <small class="text-danger">(*)</small></label>
                    <select2 :options="ciclos" v-model="matricula.ciclo_id" :selectValue="matricula.ciclo_id"
                        placeholder="Seleccione un Modulo" keyProperty="id" textProperty="nombre" @input="onCicloChange">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('ciclo_id')"
                        v-text="showError('ciclo_id').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('turno_id')}">
                    <label>Turno <small class="text-danger">(*)</small></label>
                    <select2 :options="turnos" v-model="matricula.turno_id" :selectValue="matricula.turno_id"
                        placeholder="Seleccione un turno" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('turno_id')"
                        v-text="showError('turno_id').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('detalle')}">
                    <label>Detalle <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="matriculaDetalle" @keyup.enter="doSaveData" disabled/>
                    <small class="form-control-feedback" v-if="errorExists('detalle')" v-text="showError('detalle').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fecha')}">
                    <label>Fecha</label>
                    <v-date-picker v-model="matricula.fecha"
                        format="DD-MM-YYYY"
                        value-type="format"
                        placeholder="Seccione una fecha">
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fecha')" v-text="showError('fecha').errorDetail"></small>
                </div>
            </div>

            <hr class="mt-2">
            <div class="row mt-2">
                    <div class="form-group col-12">
                        <table class="table table-sm table-hover table-striped table-bordered mb-2">
                        <thead class="thead-dark">
                            <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Duración</th>
                            <th>Precio</th>
                            <th>Fecha de Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in detalles" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.nombre }}</td>
                            <td>1 Mes</td>
                            <td>{{ item.preciomes }}</td>
                            <td>
                                    <v-date-picker v-model="item.fechapago"
                                        format="DD-MM-YYYY"
                                        value-type="format"
                                        placeholder="Seleccione una fecha">
                                    </v-date-picker>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
            </div>
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.matricula'}" class="btn waves-effect waves-light btn-info mr-2">
                <i class="fas fa-reply"></i> <span class="button-text">Atrás</span>
            </router-link>

            <div>
                <button type="button" class="btn btn-success waves-effect waves-light" @click="doSaveData">
                    <i class="fa fa-save"></i>
                    Guardar
                </button>
                <button type="reset" class="btn waves-effect waves-light btn-outline-secondary ml-2">
                    <i class="fa fa-window-close"></i> <span class="button-text">Cancelar</span>
                </button>
            </div>
        </template>

    </main-content>
</template>

<script>
    import MainContent from './../../utils/MainContent';
    import Select2 from './../../utils/Select2';
    import VDatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/locale/es';
    import moment,{ now } from 'moment';

    export default {
        props: {
            cardTitle: {
                default: 'Matricula'
            },
            matricula: {
                type: Object,
                default() {
                    return {
                        detalle: '',
                        fecha:'',
                        alumno_id: '',
                        carrera_id: '',
                        detalle_aula_id: '',
                        ciclo_id: '',
                       // periodo_id: '',
                       // condicion_id: '',
                        turno_id: '',
                        detalles: [] // Asegúrate de que detalles esté definido aquí
                    }
                }
            }
        },
        data(){
            return {
                carreras: [],
                aulas: [],
                alumnos: [],
                ciclos: [],
                //periodos: [],
               // condiciones: [],
                turnos: [],
                errors: [],
                selectedCiclo: null,
                detalles: [], // Estructura de datos para los detalles
                currentYear : new Date().getFullYear(),
                currentMonth : new Date().getMonth(),
                today: moment().format('DD-MM-YYYY')
            }
        },
        computed: {
            tableRows() {
                if (this.selectedCiclo) {
                    return Array.from({ length: this.selectedCiclo.duracion }, (_, index) => ({
                        ...this.selectedCiclo,
                        index: index + 1
                    }));
                }
                return [];
            },
            matriculaDetalle: {
                get() {
                    //const alumno = this.alumnos.find(a => a.id === alumno_id);
                    const alumnoId = parseInt(this.matricula.alumno_id, 10);
                    const alumno = this.alumnos.find(alum => alum.id === alumnoId);
                    console.log('Selected Alumno:', alumno);

                    const carreraId = parseInt(this.matricula.carrera_id, 10);
                    const carrera = this.carreras.find(carre => carre.id === carreraId);
                    console.log('Selected Carrera:', carrera);

                    const alumnoNombre = alumno ? alumno.nombrecompleto : '';
                    const carreraNombre = carrera ? carrera.nombre : '';
                    return `${alumnoNombre} - ${carreraNombre} - ${this.matricula.fecha}`;
                },
                set(newValue) {
                    const parts = newValue.split(' - ');
                    const alumnoNombre = parts[0] || '';
                    const carreraNombre = parts[1] || '';
                    const fecha = parts[2] || '';

                    const alumno = this.alumnos.find(a => a.nombrecompleto === alumnoNombre);
                    const carrera = this.carreras.find(c => c.nombre === carreraNombre);

                    this.matricula.alumno_id = alumno ? alumno.id : '';
                    this.matricula.carrera_id = carrera ? carrera.id : '';
                    this.matricula.fecha = fecha;
                }
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let matriculaData = {
                    detalle: this.matriculaDetalle,
                    fecha: this.matricula.fecha,
                    alumno_id: this.matricula.alumno_id,
                    carrera_id: this.matricula.carrera_id,
                    detalle_aula_id: this.matricula.detalle_aula_id,
                    ciclo_id: this.matricula.ciclo_id,
                   // periodo_id: this.matricula.periodo_id,
                   // condicion_id: this.matricula.condicion_id,
                    turno_id: this.matricula.turno_id,
                    detalles: this.detalles // Enviar los detalles
                }

                this.$emit('saveData', matriculaData);
            },

            validateFields() {
                this.errors = [];

                if (!this.matriculaDetalle) {
                    this.setError('detalle', 'El campo detalle es obligatorio');
                }
                if (!this.matricula.fecha) {
                    this.setError('fecha', 'El campo fecha es obligatorio');
                }
                if (!this.matricula.alumno_id) {
                    this.setError('alumno_id', 'El campo alumno es obligatorio');
                }
                if (!this.matricula.carrera_id) {
                    this.setError('carrera_id', 'El campo carrera es obligatorio');
                }
                if (!this.matricula.ciclo_id) {
                    this.setError('ciclo_id', 'El campo ciclo es obligatorio');
                }

                if (!this.matricula.turno_id) {
                    this.setError('turno_id', 'El campo turno es obligatorio');
                }

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
            listarAlumnos() {
                let vm = this;
                axios.get(`${appApiUrl}/alumno/select`)
                    .then(function (response) {
                        vm.alumnos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listarCarreras() {
                let vm = this;
                axios.get(`${appApiUrl}/carrera/select`)
                    .then(function (response) {
                        vm.carreras = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            obtenerAulasPorCarrera() {
                this.aulas = []; 
                console.log('si paso');

                let vm = this;
                axios.get(`${appApiUrl}/aula/select`, { params: { carrera_id: this.matricula.carrera_id } })
                    .then(function (response) {
                        vm.aulas = response.data.map(detalle => ({
                            id: detalle.id, // ID del detalle del aula
                            nombre: detalle.nombre_aula // Nombre del aula
                        }));
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            listarCiclo() {
                let vm = this;
                axios.get(`${appApiUrl}/ciclo/select`)
                    .then(function (response) {
                        vm.ciclos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },

            listarTurno() {
                let vm = this;
                axios.get(`${appApiUrl}/turno/select`)
                    .then(function (response) {
                        vm.turnos = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            onCicloChange() {
                console.log('matricula.ciclo_id:', this.matricula.ciclo_id);
                console.log('Ciclos:', this.ciclos);
                if (this.ciclos && this.ciclos.length > 0) {
                    const cicloId = parseInt(this.matricula.ciclo_id, 10);
                    const ciclo = this.ciclos.find(ciclo => ciclo.id === cicloId);
                    if (ciclo) {
                        this.selectedCiclo = ciclo;
                        console.log('Selected Ciclo:', this.selectedCiclo);
                        this.updateDetalles();
                    } else {
                        console.log('No se encontró un ciclo con el id:', this.matricula.ciclo_id);
                        console.log('Ciclos disponibles:', this.ciclos.map(c => c.id));
                    }
                } else {
                    console.log('Ciclos no están definidos o están vacíos.');
                }
            },
            updateDetalles() {

                console.log('updateDetalles called');
                if (this.selectedCiclo) {
                    console.log('selectedCiclo:', this.selectedCiclo);
                    const nuevosDetalles = Array.from({ length: this.selectedCiclo.duracion }, (_, index) => ({
                        nombre: `${this.selectedCiclo.nombre} pago ${index + 1}`,
                        duracion: this.selectedCiclo.duracion,
                        preciomes: this.selectedCiclo.preciomes,
                        fechapago: this.today,
                        index: index + 1
                    }));
                    this.detalles = nuevosDetalles;
                    console.log('detalles updated:', this.detalles);
                }
            },

        },
        mounted(){
            this.listarAlumnos();
            this.listarCarreras();
            this.listarCiclo();
            this.listarTurno();
            this.matricula.fecha = this.formatDate(new Date(),'DD-MM-YYYY');

            if (this.matricula.detalles && this.matricula.detalles.length > 0) {
                this.detalles = this.matricula.detalles.map(detalle => ({
                    ...detalle,
                    fechapago: moment(detalle.fechapago, 'YYYY-MM-DD').format('DD-MM-YYYY')
                }));
            }


        },
        watch: {
            matricula: {
                handler(newVal) {
                    if (newVal.detalles && newVal.detalles.length > 0) {
                        this.detalles = newVal.detalles.map(detalle => ({
                            ...detalle,
                            fechapago: moment(detalle.fechapago, 'YYYY-MM-DD').format('DD-MM-YYYY')
                        }));
                    }
                },
                deep: true
            },
            'matricula.ciclo_id': function(newVal) {
                this.onCicloChange();
            },

            'matricula.carrera_id': {
                handler(newVal, oldVal) {
                    // Solo ejecutar si carrera_id tiene un valor inicial (modo edición) y no es un cambio manual del usuario
                    if (newVal && !oldVal) {
                        this.obtenerAulasPorCarrera();
                    }
                },
                immediate: true // Ejecutar inmediatamente cuando el componente se monta
            },

        },
        components: {
            MainContent,
            Select2,
            VDatePicker
        }
    }

</script>
