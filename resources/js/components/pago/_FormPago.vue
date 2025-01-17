<template>
    <main-content columnClass="col-12">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">

                <div class="form-group col-12 col-sm-8" :class="{'has-danger':errorExists('matricula_id')}">
                    <label>Matricula <small class="text-danger">(*)</small></label>
                    <v-select class="select-vue-customers"
                        v-model="pago.matricula"
                        :filterable="false"
                        :options="matriculas"
                        :searchable="true"
                        label="detalle"
                        :loading="loading"
                        @search="onSearch"
                        @input="onSelectMatricula"
                        placeholder="Escriba al menos 3 caracteres del Nombre, Apellido, DNI.">
                        <template #option="data">
                            <div class="my-1">
                                <div class="d-flex no-block text-truncate">
                                    <h5 class="font-weight-bolder mb-0"><strong>{{ data.alumno.nombre }} {{ data.alumno.apellido }} - {{ data.detalle }}</strong></h5>
                                </div>
                                <div class="d-flex no-block text-truncate">
                                    <span>DNI: </span><span class="font-weight-bolder pl-1 pr-3"
                                        v-text="data.alumno.dni"></span>
                                    <span>CARRERA: </span><span class="font-weight-bolder pl-1 pr-3"
                                        v-text="data.carrera.nombre"></span>
                                </div>
                                <div class="d-flex no-block text-truncate">
                                    <span>CICLO: </span><span class="font-weight-bolder pl-1 pr-3"
                                        v-text="data.ciclo.nombre"></span>
                                </div>
                            </div>
                        </template>
                        <template #no-options>
                            <span>No se encontraron opciones</span>
                        </template>
                    </v-select>
                    <small class="form-control-feedback" v-if="errorExists('matricula_id')"
                        v-text="showError('matricula_id').errorDetail"></small>
                </div>                

                <div class="form-group col-12 col-sm-6 col-md-8" :class="{'has-danger':errorExists('detalle')}">
                    <label>Detalle <small class="text-danger">(*)</small></label>
                    <textarea class="form-control mt-0" v-model="pago.detalle" @:keyup.enter="doSaveData" rows="2"></textarea>
                    <small class="form-control-feedback" v-if="errorExists('detalle')" v-text="showError('detalle').errorDetail"></small>
                </div>
            </div>
            <hr class="mt-2">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3 col-xl-4 d-none d-md-block">
                    <h3 class="card-title mb-1">Concepto de pago</h3>
                </div>
                <div class="form-group col-12 col-md-9 col-lg-9 col-xl-8" :class="{'has-danger':errorExists('concepto_id')}">
                    <label>Concepto <small class="text-danger">(*)</small></label>
                    <select2 :options="conceptopagos" v-model="pago.concepto_id" :selectValue="pago.concepto_id"
                        placeholder="Seleccione un concepto" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('concepto_id')"
                        v-text="showError('concepto_id').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('precio_unitario')}">
                    <label>Precio <small class="text-danger">(*)</small></label>
                    <vue-numeric class="form-control" v-model="pago.precio_unitario" v-bind:precision="2" 
                        currency="S/">
                    </vue-numeric>
                    <small class="form-control-feedback" v-if="errorExists('precio_unitario')"
                        v-text="showError('precio_unitario').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('descuento')}">
                    <label>Descuento </label>
                    <vue-numeric class="form-control" v-model="pago.descuento" v-bind:precision="2" currency="S/">
                    </vue-numeric>
                    <small class="form-control-feedback" v-if="errorExists('descuento')"
                        v-text="showError('descuento').errorDetail"></small>
                </div>
                <div class="form-group mb-1 col-sm-3 col-md-2 align-items-end justify-content-end">
                    <label class="mb-0">&nbsp;&nbsp;</label><br>
                    <button type="submit" @click.prevent="agregarDetalle()"
                        class="btn waves-effect waves-light btn-primary text-truncate px-2">
                        <i class="far fa-hand-point-up"></i> <span>Añadir Concepto</span></button>
                </div>

            </div>
            <div class="row mt-2">
                <!-- Tabla de Detalles -->
                <div class="form-group col-12">
                    <table class="table table-sm table-hover table-striped table-bordered mb-2">
                        <thead class="thead-dark">
                            <tr>
                                <th>Concepto</th>
                                <th>Precio Unitario</th>
                                <!-- <th>Cantidad</th> -->
                                <th>Descuento</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(detalle, index) in pago.detalles" :key="index">
                                <td v-text="detalle.conceptopago.nombre"></td>
                                <td v-text="formatNumber(detalle.precio_unitario)"></td>
                                <td v-text="formatNumber(detalle.descuento)"></td>
                                <td v-text="formatNumber(detalle.importe)"></td>
                                <td>
                                    <button class="btn btn-danger" @click="eliminarDetalle(index)">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-12">
                    <div class="d-flex no-block flex-column align-items-end">
                        <div class="d-flex no-block form-group mb-1 align-items-center">
                            <label class="mb-0">Subtotal:</label>
                            <span class="d-block form-control form-control-sm ml-2 text-right font-weight-bold text-nowrap"
                                style="width: 170px;" v-text="formatNumber(subtotal)">
                            </span>
                        </div>
                        <div class="d-flex no-block form-group mb-1 align-items-center">
                            <label class="mb-0">Total:</label>
                            <span class="d-block form-control form-control-sm ml-2 text-right font-weight-bold text-nowrap"
                                style="width: 170px;" v-text="formatNumber(total)">
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mt-2">    
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.pago'}" class="btn waves-effect waves-light btn-info mr-2">
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
import vSelect from 'vue-select';
import VueNumeric from 'vue-numeric';
import accounting from 'accounting';
import debounce from 'lodash/debounce';

export default {
    props: {
        cardTitle: {
            default: 'Pago'
        },
        pago: {
            type: Object,
            default() {
                return {
                    detalle: '',
                    subtotal: '',
                    concepto_id: '',
                    matricula_id: '',
                    detalles: [], // Array para los detalles del pago
                    matricula: []
                };
            }
        }
    },
    data() {
        return {
            conceptopagos: [],
            matriculas: [],
            errors: [],
            selectedMatricula: null,
            loading: false,
        };
    },
    methods: {
        doSaveData() {
            if (this.validateFields().length > 0) {
                return;
            }

            let pagoData = {
                detalle: this.pago.detalle,
                subtotal: this.subtotal,
                matricula_id: this.pago.matricula.id,
                //matricula_id: this.pago.matricula_id,
                detalles: this.pago.detalles
            };

            this.$emit('saveData', pagoData);
        },
        validateFields() {
            this.errors = [];
            if (!this.pago.matricula || Object.keys(this.pago.matricula).length === 0) {
                this.setError('matricula_id', 'El campo matrícula es obligatorio');
            }
            if (!this.pago.detalle) {
                this.setError('detalle', 'El campo detalle es obligatorio');
            }
            if (!Array.isArray(this.pago.detalles) || this.pago.detalles.length === 0) {
                this.setError('concepto_id', 'Debe seleccionar un concepto y establecer un precio.');
            }

            return this.errors;
        },
        setError(keyModel, errorDetail) {
            this.errors.push({
                keyModel: keyModel,
                errorDetail: errorDetail
            });
        },
        errorExists(keyModel) {
            return this.errors.some(err => err.keyModel === keyModel);
        },
        showError(keyModel) {
            return this.errors.find(err => err.keyModel === keyModel);
        },
        agregarDetalle() {
            if (!this.pago.concepto_id || !this.pago.precio_unitario) {
                this.setError('concepto_id', 'Debe seleccionar un concepto y establecer un precio.');
                return;
            }
            console.log(this.pago.concepto_id);
            /* const concepto = this.conceptopagos.find(c => c.id == 4);
            if (!concepto) {
                this.setError('concepto_id', 'El concepto seleccionado no es válido.');
                return;
            } */


            let detalle = {
                concepto_id: this.pago.concepto_id,
                conceptopago: this.conceptopagos.find(c => c.id === Number(this.pago.concepto_id)),
                precio_unitario: parseFloat(this.pago.precio_unitario || 0),
                descuento: parseFloat(this.pago.descuento || 0),
                cantidad: 1, // Ajusta según tu lógica si tienes cantidades
            };

            // Calcular el subtotal para el detalle
            detalle.importe = this.calcularSubtotal(detalle);

            // Agregar el detalle a la lista
            this.pago.detalles.push(detalle);

            // Limpiar los campos de detalle después de añadirlos
            this.pago.precio_unitario = '';
            this.pago.descuento = '';
            this.pago.concepto_id = '';
        },
        eliminarDetalle(index) {
            this.pago.detalles.splice(index, 1);
        },
        calcularSubtotal(detalle) {
                // Calcula el subtotal con el descuento aplicado
            let precio = parseFloat(detalle.precio_unitario || 0);
            let descuento = parseFloat(detalle.descuento || 0);

            // Asegurarse de que el descuento no sea mayor al precio
            if (descuento > precio) {
                descuento = precio;
            }

            return precio - descuento;
        },

        onSearch: debounce(function (search) {
            if (search.length < 3) {
                return;
            }
            this.loading = true;
            this.listarMatriculas(search);
        }, 500),

        onSelectMatricula(matricula) {
            this.pago.matricula = matricula;
        },

        listarMatriculas(search) {
            axios.get(`${appApiUrl}/matricula/selectsearch`, { params: { search } })
                .then(response => {
                    this.matriculas = response.data;
                    this.loading = false;
                })
                .catch(error => {
                    console.error(error);
                    this.loading = false;
                });
        },
        listarConceptos() {
            axios.get(`${appApiUrl}/conceptopago/select`)
                .then(response => {
                    this.conceptopagos = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        formatNumber(value) {
            return accounting.format(value, 2);
        },
        unformatNumber(value) {
            return accounting.unformat(value);
        }
    },
    computed: {
        subtotal() {
            if (!Array.isArray(this.pago.detalles)) {
                return 0;
            }
            return this.pago.detalles.reduce((sum, d) => sum + parseFloat(d.importe || 0), 0);
        },
        total() {
            return this.subtotal; // Ajusta según tu lógica si hay otros cálculos
        }
    },
    mounted() {
        //this.listarMatriculas();
        this.listarConceptos();
    },
    components: {
        MainContent,
        Select2,
        vSelect,
        VueNumeric
    }
};
</script>