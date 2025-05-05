<template>
    <main-content columnClass="col-12 col-xl-10">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('nombre')}">
                    <label>Nombre <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="ciclo.nombre" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('nombre')" v-text="showError('nombre').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('duracion')}">
                    <label>Duracion meses <small class="text-danger">(*)</small></label>
                    <input type="number" class="form-control" v-model="ciclo.duracion" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('duracion')" v-text="showError('duracion').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('preciomes')}">
                    <label>Precio por Mes <small class="text-danger">(*)</small></label>
                    <input type="text" class="form-control" v-model="ciclo.preciomes" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('preciomes')" v-text="showError('preciomes').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('descripcion')}">
                    <label>Descripción</label>
                    <input type="text" class="form-control" v-model="ciclo.descripcion" @keyup.enter ="doSaveData"/>
                    <small class="form-control-feedback" v-if="errorExists('descripcion')" v-text="showError('descripcion').errorDetail"></small>
                </div>
            </div>
            <hr class="mt-2">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3 col-xl-2 d-none d-md-block">
                    <h3 class="card-title mb-1">Carrera</h3>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('carrera_id')}">
                    <label>Carrera<small class="text-danger">(*)</small></label>
                    <select2 :options="carreras" v-model="ciclo.carrera_id" :selectValue="ciclo.carrera_id"
                        placeholder="Seleccione una carrera" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('carrera_id')"
                        v-text="showError('carrera_id').errorDetail"></small>
                </div>
                <div class="form-group mb-1 col-sm-3 col-md-2 align-items-end justify-content-end">
                    <label class="mb-0">&nbsp;&nbsp;</label><br>
                    <button type="submit" @click.prevent="agregarDetalle()"
                        class="btn waves-effect waves-light btn-primary text-truncate px-2">
                        <i class="far fa-hand-point-up"></i> <span>Añadir Carrera</span></button>
                </div>
            </div>
            <div class="row mt-2">
                <!-- Tabla de Detalles -->
                <div class="form-group col-12">
                    <table class="table table-sm table-hover table-striped table-bordered mb-2">
                        <thead class="thead-dark">
                            <tr>
                                <th>Carrera</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(detalle, index) in ciclo.detalles" :key="index">
                                <td v-text="detalle.nombre"></td>
                                <td>
                                    <button class="btn btn-danger" @click="eliminarDetalle(index)">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.ciclo'}" class="btn waves-effect waves-light btn-info mr-2">
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

    export default {
        props: {
            cardTitle: {
                default: 'Ciclo'
            },
            ciclo: {
                type: Object,
                default() {
                    return {
                        nombre: '',
                        descripcion: '',
                        duracion: 0,
                        preciomes: 0.00,
                        carrera_id: '',
                        detalles: [],
                    }
                }
            }
        },
        data(){
            return {
                carreras:[],
                errors:[]
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let rolData = {
                    nombre: this.ciclo.nombre,
                    descripcion: this.ciclo.descripcion,
                    duracion: this.ciclo.duracion,
                    preciomes: this.ciclo.preciomes,
                    detalles: this.ciclo.detalles,
                }

                this.$emit('saveData', rolData);
            },

            agregarDetalle() {
                
                if (!this.ciclo.carrera_id) {
                    this.setError('carrera_id', 'Debe una selecionar una carrera.');
                    return;
                }

                const carreraSeleccionada = this.carreras.find(carrera => carrera.id === Number(this.ciclo.carrera_id));
                console.log(carreraSeleccionada);

                let detalle = {
                    carrera_id: this.ciclo.carrera_id,
                    nombre: carreraSeleccionada.nombre,
                };

                this.ciclo.detalles.push(detalle);

                this.ciclo.carrera_id = '';
            },

            validateFields() {
                this.errors = [];

                if (!this.ciclo.nombre) {
                    this.setError('nombre', 'El campo nombre es obligatorio');
                }

                if (!this.ciclo.duracion) {
                    this.setError('duracion', 'El campo duracion es obligatorio');
                }

                if (!this.ciclo.preciomes) {
                    this.setError('preciomes', 'El campo precio por mes es obligatorio');
                }

                return this.errors;
            },
            eliminarDetalle(index) {
                this.ciclo.detalles.splice(index, 1);
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
            async listarCarreras() {
                let vm = this;
                axios.get(`${appApiUrl}/carrera/select`)
                    .then(function (response) {
                        vm.carreras = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            actualizarDetalles() {
                this.ciclo.detalles.forEach(detalle => {
                    const carrera = this.carreras.find(carrera => Number(carrera.id) === Number(detalle.carrera_id));
                    console.log(`Buscando carrera con id: ${detalle.carrera_id}, encontrado:`, carrera);
                    this.$set(detalle, 'nombre', carrera ? carrera.nombre : 'Desconocido');
                });
            }

        },
        watch: {
            /* 'ciclo.detalles': {
                handler() {
                    if (this.carreras.length > 0) {
                        // Solo actualizar si las carreras están disponibles
                        this.actualizarDetalles();
                    } else {
                        console.log('Carreras aún no cargadas');
                    }
                },
                deep: true
            }, */
            'carreras': {
                handler() {
                    if (this.carreras.length > 0) {
                        this.actualizarDetalles();
                    }
                },
                immediate: true
            },
        },
        mounted(){
            this.listarCarreras();
        },
        components: {
            MainContent,
            Select2,
        }
    }

</script>
