<template>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" :class="modalSize">
            <div class="modal-content" v-if="showModal">
                <div class="modal-header" :class="headerBgClass">
                    <h5 class="modal-title">
                        <slot name="modal-header-title"></slot>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot name="modal-body-main"></slot>
                    <div class="d-flex justify-content-between no-block">
                        <slot name="modal-body-actions"></slot>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    
    props: {
        modalSize: {
            default: ''
        },
        headerBgClass:{
            default: 'bg-dark text-white'
        },
        showModal:{
            default:false
        }
    },
    watch:{
        showModal:function (newSelectValue, oldSelectValue) {
            //console.log('modal',[newSelectValue,oldSelectValue]);
            if(newSelectValue)$(this.$el).modal('show');
            else $(this.$el).modal('hide');
        }
    },
    mounted(){
        let vm = this;
        $(vm.$el).on('hidden.bs.modal', function (e) {
            vm.$emit('closeModal', false);
        });
    },
    destroyed(){
        $(this.$el).modal('dispose');
    }
}
</script>