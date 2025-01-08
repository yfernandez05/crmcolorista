<template>
    <div class="row">
        <div :class="columnClass">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center no-block" :class="headerBgClass">
                    <h4 class="m-b-0 text-white">
                        <slot name="card-header-title"></slot>
                    </h4>
                    <div>
                        <slot name="card-header-actions"></slot>
                    </div>
                </div>
                <div class="card-body">
                    <slot name="card-body-main"></slot>
                    <div class="d-flex justify-content-between no-block">
                        <slot name="card-body-actions"></slot>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>

    export default {
        props: {
            columnClass: {
                default: 'col-12'
            },
            headerBgClass:{
                default: 'bg-dark'
            }
        }
    }
</script>