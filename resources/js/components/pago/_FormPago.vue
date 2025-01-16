<template>
    <main-content columnClass="col-12">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('matricula_id')}">
                    <label>Matricula <small class="text-danger">(*)</small></label>
                    <select2 :options="matriculas" v-model="pago.matricula_id" :selectValue="pago.matricula_id"
                        placeholder="Seleccione una matricula" keyProperty="id" textProperty="detalle">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('matricula_id')"
                        v-text="showError('matricula_id').errorDetail"></small>
                </div>

                <!-- <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('concepto_id')}">
                    <label>Concepto <small class="text-danger">(*)</small></label>
                    <select2 :options="conceptopagos" v-model="pago.concepto_id" :selectValue="pago.concepto_id"
                        placeholder="Seleccione un concepto" keyProperty="id" textProperty="nombre">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('concepto_id')"
                        v-text="showError('concepto_id').errorDetail"></small>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('monto')}">
                    <label>Monto </label>
                    <vue-numeric class="form-control" ref="monto" 
                        @keypress.native.enter.prevent="doSaveData"
                        thousand-separator="," v-model="pago.monto" v-bind:precision="2" currency="S/">
                    </vue-numeric>

                    <small class="form-control-feedback" v-if="errorExists('monto')" v-text="showError('monto').errorDetail"></small>
                </div> -->

                <div class="form-group col-12 col-sm-6 col-md-8" :class="{'has-danger':errorExists('detalle')}">
                    <label>Detalle<small class="text-danger">(*)</small></label>
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
                                <td v-text="formatNumber(detalle.subtotal)"></td>
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
import VueNumeric from 'vue-numeric';
import accounting from 'accounting';

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
                    detalles: [] // Array para los detalles del pago
                };
            }
        }
    },
    data() {
        return {
            conceptopagos: [],
            matriculas: [],
            errors: []
        };
    },
    methods: {
        doSaveData() {
            /* if (this.validateFields().length > 0) {
                return;
            } */

            let pagoData = {
                detalle: this.pago.detalle,
                subtotal: this.pago.subtotal,
                matricula_id: this.pago.matricula_id,
                detalles: this.pago.detalles
            };

            this.$emit('saveData', pagoData);
        },
        validateFields() {
            this.errors = [];
            if (!this.pago.detalle) {
                this.setError('detalle', 'El campo detalle es obligatorio');
            }
            if (!this.pago.matricula_id) {
                this.setError('matricula_id', 'El campo matricula es obligatorio');
            }
            if (!this.pago.concepto_id) {
                this.setError('concepto_id', 'El campo concepto es obligatorio');
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
            detalle.subtotal = this.calcularSubtotal(detalle);

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

            let subtotal = precio - descuento;
            this.pago.subtotal = subtotal.toFixed(2); 
            return this.pago.subtotal;
        },
        listarMatriculas() {
            axios.get(`${appApiUrl}/matricula/select`)
                .then(response => {
                    this.matriculas = response.data;
                })
                .catch(error => {
                    console.error(error);
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
            // Sumar los subtotales de todos los detalles
            return this.pago.detalles.reduce((sum, d) => sum + parseFloat(d.subtotal), 0);
        },
        total() {
            // El total ahora es simplemente la suma de los subtotales
            return this.subtotal.toFixed(2);
        }
    },
    mounted() {
        this.listarMatriculas();
        this.listarConceptos();
    },
    components: {
        MainContent,
        Select2,
        VueNumeric
    }
};
</script>