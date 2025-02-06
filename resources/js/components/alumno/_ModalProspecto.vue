<template>
    <modal :showModal="showModal" @closeModal="closeModal" modalSize="modal-lg" headerBgClass="bg-success text-white">
        <template v-slot:modal-header-title>
            Buscar Prospecto
        </template>
        <template v-slot:modal-body-main>
            <div class="form-row">

                <div class="form-group col-12">
                    <v-select class="v-select-lg" placeholder="Buscar Prospectos" ref="prospecto" v-model="prospecto"
                        @input="onInputProspecto" label="nombre" :options="prospectos" @search="onSearchProspecto"
                        :filterable="false">
                        <template slot="no-options">
                            No se han encontrado resultados
                        </template>
                        <template slot="option" slot-scope="option">
                            <div class="my-1 d-flex justify-content-between align-items-center" @click="selectProspecto(option)">
                                <div>
                                    <h5 class="font-weight-bold mb-1 d-block" v-text="option.nombre"></h5>
                                    <div class="d-flex no-block text-truncate">
                                        <span>Apellido: </span><span class="font-weight-bolder pl-1 pr-3" v-text="option.apellido"></span>
                                    </div>
                                    <div class="d-flex no-block text-truncate">
                                        <span>Teléfono: </span><span class="font-weight-bolder pl-1 pr-3" v-text="option.telefono"></span>
                                    </div>
                                </div>
                                <button @click="selectProspecto(option)" class="btn btn-success btn-sm ml-2">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                            <hr class="mt-1" style="margin-bottom: -4px;">
                        </template>
                    </v-select>
                </div>

            </div>
        </template>
    </modal>
</template>

<script>
import Modal from './../../utils/Modal';
import vSelect from 'vue-select';
import _ from 'lodash';

export default {
    data(){
        return {
            showModal: false,
            prospectos: [],
            prospectoSeleccionado: {
                id: 0,
                nombre: '',
                apellido: '',
                correo: '',
                fecha_nac: ''
            },
            prospecto: null,
        }
    },

    methods:{
        closeModal(event){
            this.showModal = false;
        },
        showDetail(data){
            //console.log(data);
            this.showModal = true;
        },
        onSearchProspecto(search, loading) {
                console.log(search);
                if (search.length > 2) {
                    loading(true);
                    this.searchProspecto(loading, search, this);
                } else {
                    this.prospectos = [];
                }
        },
        searchProspecto: _.debounce((loading, search, vm) => {
                axios.get(`${appApiUrl}/prospecto/select?filter=${escape(search)}`)
                    .then(function (response) {
                       // console.log(response);
                        loading(false);
                        vm.prospectos = response.data;
                    })
                    .catch(function (error) {
                        loading(false);
                        console.log(error);
                    });
        }, 350),
        onInputProspecto() {
                if (this.prospecto) {
                    this.prospectoSeleccionado = this.prospecto;
                } else {
                    this.prospectoSeleccionado = {};
                    this.$nextTick(() => this.$refs.prospecto.$refs.search.focus());
                }
        },
        selectProspecto(option) {
            this.prospectoSeleccionado = option;
            this.$emit('prospectoSeleccionado', this.prospectoSeleccionado);
            this.closeModal();
        }
    },

    components:{
        Modal,
        vSelect,
    }
}
</script>

<style scoped>
.v-select .dropdown-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.v-select .dropdown-menu {
    width: 100%;
}

.v-select .dropdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.v-select .dropdown-item button {
    margin-left: 10px;
}
</style>
