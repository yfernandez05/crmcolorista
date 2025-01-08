<template>
    <main-content columnClass="col-12 col-md-12">
        <template v-slot:card-header-title>
            <span v-text="cardTitle"></span>
        </template>

        <template v-slot:card-body-main>
            <div class="form-row">
                <div class="col-sm-12 col-lg-8">
                    <div class="form-group col-12 col-sm-12" :class="{'has-danger':errorExists('name')}">
                        <label>Nombre del boton <small class="text-danger">(*)</small></label>
                        <input type="text" class="form-control" placeholder="Ejemplo: Informacion general" v-model="buttonmessage.name"/>
                        <small class="form-control-feedback" v-if="errorExists('name')" v-text="showError('name').errorDetail"></small>
                    </div>
                    <div class="form-group col-12 col-sm-12" >
                        <label><small>Opciones adicionales:</small></label><br>

                        <label>Seleccione color de fondo: &nbsp;</label>
                        <v-input-colorpicker v-model="buttonmessage.backgroundColor"/> &nbsp; &nbsp;
                        <!-- <input type="text" class="form-control" v-model="estadopago.backgroundColor"/> -->

                        <label>Seleccione color de texto: &nbsp;</label>
                        <v-input-colorpicker v-model="buttonmessage.textColor"/>
                        <!-- <input type="text" class="form-control" v-model="estadopago.textColor"/> -->
                    </div>
                    <div class="form-group col-12 col-sm-12" >
                        <label><small>Vista previa:</small></label><br>
                        <button v-show="buttonmessage.name" v-bind:style="{background: buttonmessage.backgroundColor, color:buttonmessage.textColor}" class="btn btn-sm button-notification" style="transition: 'all 0.3s ease'">
                            <i class="fab fa-whatsapp fa-lg"></i> 
                            <span v-text="buttonmessage.name"></span>
                        </button>
                    </div>
                    <hr class="mt-2">
                    <div class="form-group col-12 col-sm-12">
                        <label class="mb-2">Texto del Mensaje</label><br>

                        <a class="btn btn-light btn-sm btn-icon-active-wsp d-none d-lg-inline" @click="toggleEmojiPicker">😀</a> <span class="d-none d-lg-inline">Agregar emoji</span>
                        
                        <div v-if="showEmojiPicker" class="emoji-picker-container">
                          <picker native :data="emojiIndex" @select="insertEmoji" title="Selecciona tu emoji"
                            :i18n="{ search: 'Buscar', categories: { 
                                    search: 'Resultados de la búsqueda',
                                    recent: 'Reciente',
                                    smileys: 'Emoticonos y gente',
                                    people: 'Gente',
                                    nature: 'Animales y naturaleza',
                                    foods: 'Comida y bebida',
                                    activity: 'Actividades',
                                    places: 'Viajes y lugares',
                                    objects: 'Objetos',
                                    symbols: 'Símbolos',
                                    flags: 'Banderas'
                                  } }">
                          </picker>
                        </div>

                        <div v-if="showEmojiPicker" class="emoji-picker-overlay" @click="closeEmojiPicker"></div>
                        
                        <textarea class="form-control" v-model="buttonmessage.descriptionwhatsapp" ref="messageTextarea" rows="7" placeholder="Escribe un texto que se enviara al dar clic al boton creado" style="background-color: #eeeeee;"></textarea>
                        
                        <small class="form-text text-muted">
                            Atributos disponibles del alumno:
                        </small>
                        <div class="variable-list">
                            <button v-for="vari in variables" :key="vari.variable"  class="btn btn-secondary btn-sm" @click="insertVariable(vari.variable)">
                                {{ vari.label  }}
                            </button>
                        </div>

                    </div>
                    
                </div>
                <div class="col-sm-12 col-lg-4 d-none d-lg-inline">
                    <div class="mobile-preview-yf" style="height: 466px; width: 281px; margin: 0px auto; z-index: 5; position: relative;">
                        <div id="smartphone_id" class="content"
                        style="overflow: auto; transform: translate(-62px, -152px) scale(0.578); width: 357px; margin: 0px auto; height: 770px; border-radius: 55px; background: rgb(255, 255, 255); z-index: 1; position: relative;">
                        <div class="page">
                            <div class="marvel-device nexus5">
                            <div class="screen">
                                <div class="screen-container">
                                <div class="status-bar">
                                    <div class="time ml-3" style="float: left; font-size: 16px;"><a style="color: white;">18:22</a></div>
                                    <div class="dynamic-island"
                                    style="width: 100px; height: 25px; background: black; border-radius: 50px; float: left; position: relative; top: 40%; transform: translateY(-30%); margin: 0px 0px 0px 8px; font-weight: 600; left: 52px;">
                                    </div>
                                    <div class="battery"><i class="fas fa-battery-full mr-3" style="font-size: 20px;"></i></div>
                                </div>
                                <div class="chat">
                                    <div class="chat-container">
                                    <div class="user-bar">
                                        <div class="avatar"><img
                                            src="/images/whatsappmobile/user-wstp.svg"
                                            alt="Avatar"></div>
                                        <div class="name"><span>+51 999999999</span> <span class="status">online</span></div>
                                        <div class="actions more"><i class="fas fa-ellipsis-v"></i></div>
                                        <div class="actions"><i class="fas fa-phone-alt"></i></div>
                                    </div>
                                    <div class="conversation">
                                        <div class="conversation-container">
                                        <div class="message sent">
                                            <div style="white-space: pre-line;" v-html="getPreviewText()"></div>
                                            <span class="metadata"><span class="time"></span><span class="tick"><svg  xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076">
                                                <path
                                                    d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z"
                                                    fill="#4fc3f7"></path>
                                                </svg></span></span>
                                        </div>
                                        </div>
                                        <div
                                        style="height: 80px; background: rgb(255, 255, 255); padding: 10px; position: relative; top: -20px;">
                                        <div class="conversation-compose">
                                            <div class="emoji"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" id="smiley"
                                                x="3147" y="3209">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.153 11.603c.795 0 1.44-.88 1.44-1.962s-.645-1.96-1.44-1.96c-.795 0-1.44.88-1.44 1.96s.645 1.965 1.44 1.965zM5.95 12.965c-.027-.307-.132 5.218 6.062 5.55 6.066-.25 6.066-5.55 6.066-5.55-6.078 1.416-12.13 0-12.13 0zm11.362 1.108s-.67 1.96-5.05 1.96c-3.506 0-5.39-1.165-5.608-1.96 0 0 5.912 1.055 10.658 0zM11.804 1.01C5.61 1.01.978 6.034.978 12.23s4.826 10.76 11.02 10.76S23.02 18.424 23.02 12.23c0-6.197-5.02-11.22-11.216-11.22zM12 21.355c-5.273 0-9.38-3.886-9.38-9.16 0-5.272 3.94-9.547 9.214-9.547a9.548 9.548 0 0 1 9.548 9.548c0 5.272-4.11 9.16-9.382 9.16zm3.108-9.75c.795 0 1.44-.88 1.44-1.963s-.645-1.96-1.44-1.96c-.795 0-1.44.878-1.44 1.96s.645 1.963 1.44 1.963z"
                                                fill="#7d8489"></path>
                                            </svg></div> <input name="input" placeholder="Escribe un mensaje" disabled="disabled"
                                            class="input-msg">
                                            <div class="photo"><i class="fas fa-camera"></i></div> <button class="send"
                                            style="cursor: initial;">
                                            <div class="circle"><i class="fas fa-paper-plane ml-0 mr-1"></i></div>
                                            </button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-2">
        </template>

        <template v-slot:card-body-actions>
            <router-link :to="{name: 'spa.buttonmessage'}" class="btn waves-effect waves-light btn-info mr-2">
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
    import VInputColorpicker from 'vue-native-color-picker'
    import data from 'emoji-mart-vue-fast/data/all.json'
    import { Picker, EmojiIndex } from 'emoji-mart-vue-fast'
    
    export default {
        props: {
            cardTitle: {
                default: 'Boton'
            },
            buttonmessage: {
                type: Object,
                default: function () {
                    return {
                        name: '',
                        backgroundColor: '#d3d3d3',
                        textColor: '#000',
                        descriptionwhatsapp: '',
                    }
                }
            }
        },
        data(){
            return {
              emojiIndex: new EmojiIndex(data),
              showEmojiPicker: false,
              errors:[],
              variables: [
                { label: 'Nombre', variable: '{nombres}', sample: 'Juan Rodrigo' },
                { label: 'Apellido Paterno', variable: '{apellidopaterno}', sample: 'Pérez' },
                { label: 'Apellido Materno', variable: '{apellidomaterno}', sample: 'Gómez' },
                { label: 'Correo', variable: '{email}', sample: 'juan_p_fc@gmail.com' },
                { label: 'Celular', variable: '{telefono}', sample: '912345678' },
                { label: 'Sede', variable: '{sede}', sample: 'Campus Villa' },
                { label: 'Direccion Sede', variable: '{direccionsede}', sample: 'Panamericana Sur Km. 19' },
                { label: 'Url Direccion Sede Maps', variable: '{urldireccionsedemaps}', sample: 'https://maps.app.goo.gl/R1mtT9Yw5LDjRG1BA' },
              ],
                
            }
        },
        methods: {
            doSaveData() {

                if (this.validateFields().length > 0) {
                    return;
                }

                let buttonmessageData = {
                    name: this.buttonmessage.name,
                    backgroundColor: this.buttonmessage.backgroundColor,
                    textColor: this.buttonmessage.textColor,
                    descriptionwhatsapp: this.buttonmessage.descriptionwhatsapp,
                }

                this.$emit('saveData', buttonmessageData);
            },

            validateFields() {
                this.errors = [];

                if (!this.buttonmessage.name) {
                    this.setError('name', 'El nombre del boton es obligatorio');
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

            insertVariable(variable) {
                const textarea = this.$refs.messageTextarea;
                const startPos = textarea.selectionStart;
                const endPos = textarea.selectionEnd;
                const textBefore = this.buttonmessage.descriptionwhatsapp.substring(0, startPos);
                const textAfter = this.buttonmessage.descriptionwhatsapp.substring(endPos, this.buttonmessage.descriptionwhatsapp.length);
                
                // Insertar la variable en la posición del cursor
                this.buttonmessage.descriptionwhatsapp = textBefore + variable + textAfter;
                
                // Colocar el cursor después de la variable insertada
                this.$nextTick(() => {
                    textarea.selectionStart = textarea.selectionEnd = startPos + variable.length;
                    textarea.focus();
                });
            },

            getPreviewText() {
                let previewText = this.buttonmessage.descriptionwhatsapp;              
                if(previewText){
                        this.variables.forEach(vari => {
                        const regex = new RegExp(vari.variable, 'g');
                        previewText = previewText.replace(regex, vari.sample);
                    });
                    return previewText;
                }
              
            },

            toggleEmojiPicker() {
              this.showEmojiPicker = !this.showEmojiPicker;
            },
            insertEmoji(emoji) {
                /* console.log('Emoji clicked:', emoji.native);  */
                this.buttonmessage.descriptionwhatsapp += emoji.native;
                this.showEmojiPicker = false;
            },
            closeEmojiPicker() {
                this.showEmojiPicker = false;
            }
        },

        components: {
            MainContent,
            VInputColorpicker,
            Picker
        }
    }

</script>

<style scoped>
input.icp__input {
    border: 2px solid #333a40 !important;
}
</style>