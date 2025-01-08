<template>
    <select class="form-control custom-select" v-model="selectValue" style="width: 100%;">
        <option v-for="(item, index) in options" :key="index" :value="getKeyProperty(item)"
            v-text="getTextProperty(item)">
        </option>
    </select>
</template>
<script>
    export default {
        //props: ['options', 'value'],
        props: {
            options: {
                type: Array,
                default: function () {
                    return [];
                }
            },
            keyProperty: [String, Number],
            textProperty: [String, Number],
            placeholder: {
                default: 'Seleccione una opción'
            },
            selectValue: {
                default: ''
            }
        },
        data() {
            return {
                //valorInitial : this.selectValue,
            }
        },
        methods: {
            getKeyProperty(option) {
                let valueFinal = '';
                if (typeof this.keyProperty === 'number')
                    this.keyProperty = this.keyProperty.toString()

                if (option.hasOwnProperty(this.keyProperty))
                    valueFinal = option[this.keyProperty];
                else {
                    let values = Object.values(option);

                    if (values.length) valueFinal = values[0];
                }

                return valueFinal;
            },
            getTextProperty(option) {
                let valueFinal = '';

                if (typeof this.textProperty === 'number')
                    this.textProperty = this.textProperty.toString()

                if (option.hasOwnProperty(this.textProperty))
                    valueFinal = option[this.textProperty];
                else {
                    let values = Object.values(option);

                    if (values.length) valueFinal = values[0];
                }

                return valueFinal;
            }
        },
        watch:{
            selectValue: function (newSelectValue, oldSelectValue) {
                //console.log('new: '+newSelectValue,'old: '+oldSelectValue);
                if(!newSelectValue.length)
                    $(this.$el).val('').trigger('change');
            }
        },
        mounted() {
            let vm = this
            $(vm.$el).select2({
                    placeholder: vm.placeholder,
                    allowClear: true
                }).on('select2:select', function (e) {
                    vm.$emit('input', e.params.data.id);
                }).on("select2:unselect", function (e) {
                    vm.$emit('input', '');
                }).next('.select2')
                .find('.select2-selection')
                .one('focus', select2Focus)
                .on('blur', function () {
                    $(this).one('focus', select2Focus)
                });

            function select2Focus() {
                $(this).closest('.select2').prev('select').select2('open');
            };
        },
        destroyed() {
            $(this.$el).off().select2('destroy')
        }
    }

</script>
