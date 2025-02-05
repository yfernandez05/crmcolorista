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
                        ref="matriculaSelect"
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
                                    <h5 class="font-weight-bolder mb-0"><strong>{{ data.detalle }}</strong></h5>
                                </div>
                                <div class="d-flex no-block text-truncate">
                                    <span>Nombre: </span><span class="font-weight-bolder pl-1 pr-3">{{ data.alumno.nombre }} {{ data.alumno.apellido }}</span>
                                    <span>DNI: </span><span class="font-weight-bolder pl-1 pr-3"
                                        v-text="data.alumno.dni"></span>
                                </div>
                                <div class="d-flex no-block text-truncate">
                                    <span>CARRERA: </span><span class="font-weight-bolder pl-1 pr-3"
                                        v-text="data.carrera.nombre"></span>
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
                <div class="form-row col-12">
                    <div class="form-group mb-1 col-sm-8 col-md-8 col-lg-4">
                    <label class="mb-0">Nombre</label>
                    <input type="text" class="form-control form-control-sm"
                        v-model="selectedAlumnoNombre">
                    </div>
                    <div class="form-group mb-1 col-sm-8 col-md-8 col-lg-4">
                    <label class="mb-0">Apellido</label>
                    <input type="text" class="form-control form-control-sm"
                        v-model="selectedAlumnoApellido">
                    </div>
                    <div class="form-group mb-1 col-sm-8 col-md-8 col-lg-4">
                        <label class="mb-0">DNI</label>
                        <input type="text" class="form-control form-control-sm"
                            v-model="selectedAlumnoDni">
                    </div>
                    <div class="form-group mb-1 col-sm-8 col-md-8 col-lg-4">
                        <label class="mb-0">Carrera</label>
                        <input type="text" class="form-control form-control-sm"
                            v-model="selectedAlumnoCarrera">
                    </div>
                    <div class="form-group mb-1 col-sm-8 col-md-8 col-lg-4">
                        <label class="mb-0">Ciclo</label>
                        <input type="text" class="form-control form-control-sm"
                            v-model="selectedAlumnoCiclo">
                    </div>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-8" :class="{'has-danger':errorExists('detalle')}">
                    <label>Detalle </label>
                    <textarea style="opacity: 1;" class="form-control mt-0 bg-white"  :disabled="true" v-model="pago.detalle" @:keyup.enter="doSaveData" rows="2"></textarea>
                    <small class="form-control-feedback" v-if="errorExists('detalle')" v-text="showError('detalle').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('codcomprobante')}">
                    <label>Comprobante <small class="text-danger">(*)</small></label>
                    <select2 :options="tipocomprobantes" v-model="pago.codcomprobante" :selectValue="pago.codcomprobante"
                        placeholder="Seleccione un comprobante" keyProperty="codcomprobante" textProperty="nombrecomprobante">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('codcomprobante')"
                        v-text="showError('codcomprobante').errorDetail"></small>
                </div>
            </div>
            <hr class="mt-2">
            <div class="row" v-if="!isEditing">
                <div class="col-12 col-md-3 col-lg-3 col-xl-4 d-none d-md-block">
                    <h3 class="card-title mb-1">Concepto de pago:</h3>
                </div>
                <div class="form-group col-12 col-md-9 col-lg-9 col-xl-4" :class="{'has-danger':errorExists('concepto_id')}">
                    <label>Concepto <small class="text-danger">(*)</small></label>
                    <select2 :options="conceptopagos" v-model="pago.concepto_id" :selectValue="pago.concepto_id"
                        placeholder="Seleccione un concepto" keyProperty="id" textProperty="nombre" @input="selectConcepto">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('concepto_id')"
                        v-text="showError('concepto_id').errorDetail"></small>
                </div>
                <div v-if="pago.concepto_id >= 3" class="form-group col-12 col-md-9 col-lg-9 col-xl-4" :class="{'has-danger':errorExists('id_detalle_matricula')}">
                    <label>Pensiones </label>
                    <select2
                        :options="detallesConEstado"
                        v-model="pago.id_detalle_matricula"
                        placeholder="Seleccione una pension"
                        keyProperty="id"
                        textProperty="nombre"
                        :disabled="isDisabled"
                        @input="updatePrecioUnitario">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('id_detalle_matricula')" v-text="showError('id_detalle_matricula').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('precio_unitario')}">
                    <label>Precio <small class="text-danger">(*)</small></label>
                    <div class="d-flex align-items-center">
                        <vue-numeric class="form-control"  v-model="pago.precio_unitario" v-bind:precision="2" currency="S/" :disabled="pago.concepto_id && pago.concepto_id != 1">
                        </vue-numeric>
                        <div v-if="pago.concepto_id < 2" class="form-check ml-2 col-4">
                            <input class="form-check-input" type="checkbox" id="costoCero" v-model="costoCero" @change="toggleCostoCero">
                            <label class="form-check-label" for="costoCero">
                                Costo 0
                            </label>
                        </div>

                    </div>
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
                                <th>Nº Mensualidad</th>
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
                                <td v-text="detalle.nombre_numero_mensualidad"></td>
                                <td v-text="formatNumber(detalle.precio_unitario)"></td>
                                <td v-text="formatNumber(detalle.descuento)"></td>
                                <td v-text="formatNumber(detalle.importe)"></td>
                                <td>
                                    <button class="btn btn-danger" @click="eliminarDetalle(index, detalle.id_detalle_matricula)">Eliminar</button>
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
                    detalle: '-',
                    subtotal: '',
                    concepto_id: '',
                    matricula_id: '',
                    detalles: [], // Array para los detalles del pago
                    matricula: [],
                    precio_unitario: 100,
                    ids_detalles_matricula : [],
                };
            }
        },
        isEditing: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            conceptopagos: [],
            matriculas: [],
            errors: [],
            selectedMatricula: null,
            loading: false,
            costoCero: false,
            tipocomprobantes: [],
            tipocomprobante: null,
            tipocomprobanteSeleccionado: {
            },
            isDisabled: false,
            idsDetalleMatriculaDelete: [],
        };
    },
    methods: {
        toggleCostoCero() {
            if (this.costoCero) {
                this.pago.precio_unitario = 0;
            }else{
                this.pago.precio_unitario = 100;
            }
        },
        doSaveData() {
            if (this.validateFields().length > 0) {
                return;
            }

            let pagoData = {
                detalle: this.pago.detalle,
                subtotal: this.subtotal,
                matricula_id: this.pago.matricula.id,
                ids_detalles_matricula: this.pago.ids_detalles_matricula,
                codcomprobante: this.pago.codcomprobante,
                serie: this.pago.serie,
                detalles: this.pago.detalles,
                idsDetalleMatriculaDelete: this.idsDetalleMatriculaDelete.slice()
            };

            this.$emit('saveData', pagoData);
            console.log('pagoData', pagoData);
        },
        validateFields() {
            this.errors = [];
            if (!this.pago.matricula || Object.keys(this.pago.matricula).length === 0) {
                this.setError('matricula_id', 'El campo matrícula es obligatorio');
            }
            /* if (!this.pago.detalle) {
                this.setError('detalle', 'El campo detalle es obligatorio');
            } */
            if (!Array.isArray(this.pago.detalles)) {
                this.setError('concepto_id', 'Debe seleccionar un concepto.');
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
            if (!this.pago.matricula || Object.keys(this.pago.matricula).length === 0) {
                this.setError('matricula_id', 'El campo matrícula es obligatorio');

                // Verifica si el ref existe antes de usarlo
                if (this.$refs.matriculaSelect) {
                    // Desplaza el input al área visible
                    this.$refs.matriculaSelect.$el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Aplica el foco
                    //this.$refs.matriculaSelect.focus();
                    this.$refs.matriculaSelect.$el.querySelector('input').focus();
                }
                return;
            }

            if (!this.pago.concepto_id) {
                this.setError('concepto_id', 'Debe seleccionar un concepto.');
                return;
            }

            if (this.pago.concepto_id >= 3 && !this.pago.id_detalle_matricula) {
                this.setError('id_detalle_matricula', 'Debe seleccionar una mensualidad.');
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
                cantidad: 1,
                nombre_numero_mensualidad: this.pago.matricula.detalles.find(dtm => dtm.id === Number(this.pago.id_detalle_matricula))?.nombre || "",
                id_detalle_matricula: this.pago.id_detalle_matricula,
            };

            // Calcular el subtotal para el detalle
            detalle.importe = this.calcularSubtotal(detalle);

            // Agregar el detalle a la lista
            this.pago.detalles.push(detalle);

            if (this.pago.id_detalle_matricula) {
                this.pago.ids_detalles_matricula.push(this.pago.id_detalle_matricula);
            }

            // Limpiar los campos de detalle después de añadirlos
            this.pago.precio_unitario = '';
            this.pago.descuento = '';
            this.pago.concepto_id = '';
            this.pago.id_detalle_matricula = '';
        },
        eliminarDetalle(index,id_detalle_matricula) {
            this.pago.detalles.splice(index, 1);
            if (this.isEditing && id_detalle_matricula) {
                this.addIdsDetalleMatriculaDelete(id_detalle_matricula);
            }
        },

        addIdsDetalleMatriculaDelete(id) {
            if (id && !this.idsDetalleMatriculaDelete.includes(id)) {
                this.idsDetalleMatriculaDelete.push(id);
                /* console.log(this.idsDetalleMatriculaDelete.slice()); */
            }
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
            this.pago.concepto_id = '';
            this.selectConcepto();
            this.pago.matricula = matricula;
            this.pago.detalles = [];
            this.costoCero = false;
            this.toggleCostoCero();
            this.pago.detalle = `PAGO - ${matricula.detalle}`;

            console.log(this.pago.matricula.detalles);
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
        },
        listarTipocoprobante() {
                let vm = this;
                axios.get(`${appApiUrl}/tipocomprobante/select`)
                    .then(function (response) {
                        vm.tipocomprobantes = response.data;
                        if (!vm.pago.codcomprobante) {
                            vm.pago.codcomprobante = 1;
                        }
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    });
            },

            updatePrecioUnitario() {
                console.log(this.pago.id_detalle_matricula);
                // Solo actualiza si `pago.id_detalle_matricula` tiene valor
                if (this.pago.id_detalle_matricula) {
                    console.log(this.pago.id_detalle_matricula);
                    const detalleSeleccionado = this.pago.matricula?.detalles?.find(
                        (dtm) => dtm.id === Number(this.pago.id_detalle_matricula)
                    );

                    // Asigna el precio del detalle seleccionado al campo `precio_unitario`
                    this.pago.precio_unitario = detalleSeleccionado?.preciomes || 0;
                }
            },

            sumaModulosPrecioDetalleMatricula(){
                if (this.pago.matricula?.detalles?.length > 0) {
                    const total = this.pago.matricula.detalles.reduce((suma, detalle) => {
                        const precio = Number(detalle.preciomes) || 0;
                        return suma + precio;
                    }, 0);

                    this.pago.precio_unitario = total;
                    console.log('Total calculado:', total);
                }
            },

            selectConcepto(){
                console.log(this.pago.concepto_id);

                if(this.pago.concepto_id == 1 || !this.pago.concepto_id.length){
                    this.pago.id_detalle_matricula = '';
                    this.pago.precio_unitario = 100;
                }
                if(this.pago.concepto_id == 3 || !this.pago.concepto_id.length){
                    this.costoCero = false;
                    this.toggleCostoCero();
                }
                if(this.pago.concepto_id == 2){
                    this.pago.id_detalle_matricula = '';
                    this.sumaModulosPrecioDetalleMatricula();
                }
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
        },
        selectedAlumnoNombre() {
            return this.pago.matricula && this.pago.matricula.alumno ? this.pago.matricula.alumno.nombre : '';
        },
        selectedAlumnoApellido() {
            return this.pago.matricula && this.pago.matricula.alumno ? this.pago.matricula.alumno.apellido : '';
        },
        selectedAlumnoDni() {
            return this.pago.matricula && this.pago.matricula.alumno ? this.pago.matricula.alumno.dni : '';
        },
        selectedAlumnoCarrera() {
            return this.pago.matricula && this.pago.matricula.carrera ? this.pago.matricula.carrera.nombre : '';
        },
        selectedAlumnoCiclo(){
            return this.pago.matricula && this.pago.matricula.ciclo ? this.pago.matricula.ciclo.nombre : '';
        },
        /* selectedDetalleMatricula(){
            return this.pago.matricula && Array.isArray(this.pago.matricula.detalles) && this.pago.matricula.detalles.length > 0 ? this.pago.matricula.detalles : [];
        }, */
        detallesConEstado() {
            return this.pago.matricula.detalles.map(detalle => {
        return {
            ...detalle,
            disabled: detalle.pagado == 1 && detalle.fecha_confirmacion_pago !== null
        };
    });
        },


    },
    /* watch: {
        'pago.matricula': {
            handler(newVal) {
                // Opcional: Reinicia el concepto si la matrícula cambia
                console.log('Cambio en matrícula:', newVal);
                this.pago.concepto_id = '';
            },
            deep: true,
        },
    }, */
    mounted() {
        //this.listarMatriculas();
        this.listarConceptos();
        this.listarTipocoprobante();
    },
    components: {
        MainContent,
        Select2,
        vSelect,
        VueNumeric
    }
};
</script>

<style scoped>
/* Añadir estilos para deshabilitar opciones en select2 */
.select2-results__option[aria-disabled=true] {
  color: #999 !important;
  cursor: not-allowed !important;
}

</style>
