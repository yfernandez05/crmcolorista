<template>
    <div class="d-flex no-block flex-nowrap align-items-center">
        <button type="button" title="Ver Detalle" @click="onRowItemActions('show',rowData)" v-if="activeShow"
            class="btn btn-sm btn-outline-info waves-effect waves-light border-0 mr-1">
            <i class="fas fa-eye fa-lg"></i>
        </button>

        <button type="button" title="Editar" @click="onRowItemActions('edit',rowData)" v-if="activeEdit"
            class="btn btn-sm btn-outline-success waves-effect waves-light border-0 mr-1">
            <i class="fas fa-marker fa-lg"></i>
        </button>

        <button type="button" title="Eliminar" @click="onRowItemActions('delete',rowData)" v-if="activeDelete"
            class="btn btn-sm btn-outline-danger waves-effect waves-light border-0 mr-1">
            <i class="fas fa-trash fa-lg"></i>
        </button>
        <slot></slot>
    </div>
</template>
<script>
    export default {

        props:{
            rowData: {
                type: Object,
                required: true
            },
            activeShow: {
                type: Boolean,
                default: false
            },
            activeDelete: {
                type: Boolean,
                default: true
            },
            activeEdit: {
                type: Boolean,
                default: true
            }
        },
        methods:{
            onRowItemActions(action, data){
                this.$emit('rowItemActions', {action:action, data:data})
            }
        }
    }

</script>
