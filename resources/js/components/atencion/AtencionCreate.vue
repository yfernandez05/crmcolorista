<template>
    <main-content columnClass="col-12 col-xl-11">
        <template v-slot:card-header-title>
            Registrar Atencion
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                
                <div class="form-group col-12" :class="{'has-danger':errorExists('comentario')}">
                    <label>Comentario<small class="text-danger">(*)</small></label>
                    <textarea class="form-control"  v-model="atencion.comentario" @:keyup.enter ="doSaveData" rows="3"></textarea>
                    <small class="form-control-feedback" v-if="errorExists('comentario')" v-text="showError('comentario').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('idtipoatencion')}">
                    <label>Estado Atencion <small class="text-danger">(*)</small></label>
                    <select2 :options="tipoatenciones" v-model="atencion.idtipoatencion" :selectValue="atencion.idtipoatencion"
                        placeholder="Seleccione un estado" keyProperty="idtipoatencion" textProperty="tipoatencion">
                    </select2>
                    <small class="form-control-feedback" v-if="errorExists('idtipoatencion')"
                        v-text="showError('idtipoatencion').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4" :class="{'has-danger':errorExists('fechaatencion')}">
                    <label>Fecha Atencion <small class="text-danger">(*)</small></label>
                    <v-date-picker v-model="atencion.fechaatencion"
                        format="DD-MM-YYYY"
                        value-type="format"
                        placeholder="Seccione"
                    >
                    </v-date-picker>
                    <small class="form-control-feedback" v-if="errorExists('fechaatencion')" v-text="showError('fechaatencion').errorDetail"></small>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 d-flex no-block align-items-end justify-content-end">
                    <button type="button" class="btn btn-success waves-effect waves-light" @click="saveData">
                        <i class="fa fa-save"></i>
                        Guardar
                    </button>
                </div>
            </div>
            <hr class="mt-1 mb-2">
            <div class="form-row">
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Nombres</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.nombres"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Ap. Paterno</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted" v-text="cliente.apellidopaterno"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Ap. Materno</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.apellidomaterno"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Telefono</label>
                    <div class="input-group">
                        <span class="form-control form-control-sm text-truncate text-muted"
                            v-text="cliente.telefono"></span>                       
                    </div>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Email</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.email"></span>
                </div>                
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Carrera</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.carrera"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Sede</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.colegio"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Fecha Registro</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.fecha"></span>
                </div>
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Año Egreso</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.anioegreso"></span>
                </div>
                <!-- <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0">Evento</label>
                    <span class="form-control form-control-sm d-block text-truncate text-muted"
                        v-text="cliente.evento.nombreevento"></span>
                </div> -->
                <div class="form-group form-group-sm col-12 col-sm-6 col-md-4 col-xl-3">
                    <label class="mb-0 col-12">Descargar</label>
                    <div class="input-group-append">
                        <button type="button" title="Descargar QR" @click="downloadSingleQR(cliente.email)"
                            class="btn btn-sm  waves-effect waves-light border-0 mr-1 btn-dark col-6">
                            <i class="fas fa-lg fa-qrcode"></i> QR
                        </button>
                    </div>
                </div>
                <div v-show="buttonmessages" class="form-group form-group-sm col-12 col-sm-6 col-md-8 col-xl-6" style="align-content: center;">
                    <label class="mb-0 col-12">Whatsapp enlaces</label>
                    <a v-for="button in buttonmessages" :key="button.idbutton" class="btn btn-sm mr-1 mb-1 button-notification" :style="{ backgroundColor: button.backgroundColor, color: button.textColor }" @click="openWhatsApp(button.descriptionwhatsapp)">
                        <i class="fab fa-whatsapp fa-lg"></i> <span v-text="button.name"></span>
                    </a>
                </div>
                
            </div>

            <div class="table-responsive">
                <table id="productos" class="table table-sm table-hover table-striped table-bordered mb-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cod</th>
                            <th>F. Atencion</th>
                            <th>Est. Atencion</th>
                            <th>Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="aten in atenciones" :key="aten.idatencion">
                            <td v-text="aten.idatencion"></td>
                            <td v-text="aten.fecha"></td>
                            <td><span class="badge badge-pill badge-lg" v-bind:style="{background: aten.tipoatencion.backgroundColor, color:aten.tipoatencion.textColor}" v-text="aten.tipoatencion.tipoatencion"></span></td>
                            <td v-text="aten.comentario"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr class="mt-2">
        </template>
        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.cliente'}" class="btn waves-effect waves-light btn-info mr-2">
                <i class="fas fa-reply"></i> <span class="button-text">Atrás</span>
            </router-link>

            <div></div>
        </template>
    </main-content>
</template>

<script>
    import MainContent from './../../utils/MainContent';
    import VDatePicker from 'vue2-datepicker';
    import moment,{ now } from 'moment';
    import 'vue2-datepicker/locale/es';
    import Select2 from './../../utils/Select2';

    export default {
        data(){
            return {
                errors:[],
                atencion:{
                    idtipoatencion:2,
                    fechaatencion: this.formatDate(new Date(),'DD-MM-YYYY'),
                    comentario: 'Se envió el QR mediante WhatsApp.',
                },
                tipoatenciones:[],
                cliente:{
                    campania:{  
                        evento:{nombreevento: '',}                      
                    },
                    distrito:{}
                },
                atenciones:[],
                buttonmessages:[],
            }
        },
        created() {
            this.cliente.idcliente = this.$route.params.id;

            if (isNaN(this.cliente.idcliente)) {
                this.backToList();
            }

            this.obtenerCliente(this.cliente.idcliente);
            this.obtenerAtenciones(this.cliente.idcliente);
        },
        methods: {
            validateFields() {
                this.errors = [];

                if (!this.atencion.idtipoatencion) {
                    this.setError('idtipoatencion', 'El campo Tipo de Atencion es obligatorio');
                }

                if (!this.atencion.comentario) {
                    this.setError('comentario', 'El campo Comentario es obligatorio');
                }
               
                if (!this.atencion.fechaatencion) {
                    this.setError('fechaatencion', 'El campo Fecha Ingreso es obligatorio');
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
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            listartipoAtenciones() {
                let vm = this;
                axios.get(`${appApiUrl}/tipoAtencion/select`,{
                        params: { istosave:true}
                    })
                    .then(function (response) {
                        vm.tipoatenciones = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            listarbotonesmessage() {
                let vm = this;
                axios.get(`${appApiUrl}/buttonmessage/select`)
                .then(function (response) {
                    vm.buttonmessages = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            saveData(){
                if (this.validateFields().length > 0) {
                    return;
                }

                let atencionData = {
                    comentario: this.atencion.comentario,
                    fechaatencion: this.atencion.fechaatencion,
                    idcliente: this.cliente.idcliente,
                    idtipoatencion: this.atencion.idtipoatencion,
                    
                };

                let vm = this;
                axios.post(`${appApiUrl}/atencion`, atencionData)
                    .then(function (response) {
                        hidePreloader();
                        let result = response.data;
                        if (result.status) {
                            successMessage(result.message, appName);
                            vm.obtenerAtenciones(vm.cliente.idcliente);
                        } else
                            errorMessage(result.message, appName);
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        console.log(error);
                    })
            },
            obtenerCliente(id) {
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/cliente/${id}/edit`)
                    .then(function (response) {
                        hidePreloader();
                        if (response.data == null || response.data == '') {
                            warningMessage(`No se encontró ningún cliente con el código ${id}`, appName);
                            vm.backToList();
                        }
                        vm.cliente = response.data;

                        if(!vm.cliente.isactive){
                            warningMessage('No se puede atender a un cliente elimnado', appName);
                            vm.backToList();
                        }
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        vm.backToList();
                        console.log(error);
                    })
            },
            backToList(){
                this.$router.push({
                    name: 'spa.cliente'
                });
            },
            obtenerAtenciones(id){
                showPreloader();

                let vm = this;
                axios.get(`${appApiUrl}/atencion/detail/${id}`)
                    .then(function (response) {
                        hidePreloader();
                        vm.atenciones = response.data;
                    })
                    .catch(function (error) {
                        hidePreloader();
                        errorMessage(appErrorMessage, appName);
                        console.log(error);
                    })
            },
            cleanAndValidatePhone(phone) {
                const cleanedPhone = phone.replace(/\D/g, '').match(/9\d{8}/);
                return cleanedPhone ? cleanedPhone[0] : null;
            },
            getSedeInfo(campus) {
                const direcciones = {
                    "Campus Villa": {
                        direccion: "Panamericana Sur Km. 19",
                        urlmaps: "https://maps.app.goo.gl/czb6vYFZSNF4Cz5X6"
                    },
                    "Campus Ate": {
                        direccion: "Av. Nicolás Ayllón 7208",
                        urlmaps: "https://maps.app.goo.gl/AKrMk3Nxsi5KLbYd9"
                    },
                    "Campus Norte": {
                        direccion: "Av. Alfredo Mendiola con Av. 2 de octubre (Óvalo Infantas), Los Olivos",
                        urlmaps: "https://maps.app.goo.gl/8nc7huF9WNmudWF28"
                    },
                    "Campus Aramburu": {
                        direccion: "Av. República de Panamá 3944, Surquillo",
                        urlmaps: "https://maps.app.goo.gl/JEztrrU1C1HU7NRd7"
                    }
                };

                // Retorna la dirección y URL si existe, o valores por defecto.
                return direcciones[campus] || { direccion: "Dirección no disponible", url: "URL no disponible" };
            },
            replaceVariables(text) {
                const sedeinfo = this.getSedeInfo(this.cliente.colegio);

                const variables = {
                    '{nombres}': this.cliente.nombres || '-',
                    '{apellidopaterno}': this.cliente.apellidopaterno || '-',
                    '{apellidomaterno}': this.cliente.apellidomaterno || '-',
                    '{email}': this.cliente.email || '-',
                    '{telefono}': this.cliente.telefono || '-',
                    '{direccion}': this.cliente.direccion || '-',
                    '{sede}': `${this.cliente.colegio}` || '-',
                    '{direccionsede}': `${sedeinfo.direccion}` || '-',
                    '{urldireccionsedemaps}': `${sedeinfo.urlmaps}` || '-'
                };

                // Reemplazar las variables en el texto
                return text.replace(/\{(\w+)\}/g, (match) => variables[match] || match);

            },
            openWhatsApp(description) {
                let validPhone = this.cleanAndValidatePhone(this.cliente.telefono);
                if (validPhone) {
                    let mensaje = this.replaceVariables(description);
                    let encodedMessage = encodeURIComponent(mensaje);
                    //console.log(mensaje);
                    window.open(`https://web.whatsapp.com/send?phone=51${validPhone}&text=${encodedMessage}`, '_blank');
                } else {
                    alert('Número de teléfono no válido. Debe ser un número peruano válido con 9 dígitos y comenzar con 9.');
                }
            },
            downloadSingleQR(data) {
                console.log(data);
                showPreloader();
                let url = `${appApiUrl}/cliente/downloadsingleqr?email=${data}`;

                axios.get(url, { responseType: 'blob' })
                .then(function(response) {
                    if (response.data instanceof Blob) {
                        if (response.data.type === 'image/png') {
                            console.log('Se descargó una imagen:');
                            successMessage('QR Descargado.', appName);

                            let contentDisposition = response.headers['content-disposition'];
                            let filename = contentDisposition ? contentDisposition.split('filename=')[1].trim().replace(/"/g, '') : 'qr-code.png';
                            const url = window.URL.createObjectURL(response.data);
                            const link = document.createElement('a');
                            link.href = url;
                            link.setAttribute('download', filename);
                            document.body.appendChild(link);
                            link.click();
                            window.URL.revokeObjectURL(url);
                        } else {
                            warningMessage('No se encontró el código QR para descargar', appName);
                        }
                    } else {
                        warningMessage('No se encontró el código QR para descargar', appName);
                    }
                })
                .catch(function(error) {
                    errorMessage(appErrorMessage, appName);
                    console.log(error);
                })
                .finally(function() {
                    hidePreloader();
                });
                
            },

        },
        mounted(){
            //this.atencion.fechaatencion = this.formatDate(new Date(),'DD-MM-YYYY');
            this.listartipoAtenciones();
            this.listarbotonesmessage();
        },
        components: {
            MainContent,
            VDatePicker,
            Select2
        }
    }

</script>
