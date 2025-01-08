<template>
    <input type="text" class="form-control" readonly />
</template>
<script>
    export default {
        props: {

        },
        mounted() {
            let vm = this
            $(vm.$el).datepicker({
                autoclose: true,
                todayHighlight: true,
                // enableOnReadonly: true,
                clearBtn: true,
                format: 'dd-mm-yyyy',
            }).on('changeDate', function (e) {
                //console.log(e);
                vm.$emit('input', e.format());
            });
        }
    }

</script>
