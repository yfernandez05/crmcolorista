<template>
    <div class="card" :class="columnClass">
        <div class="card-header car-group-container" >
            <slot name="title-card-header"></slot> <!-- Titulo del card -->
            <div class="card-actions" :class="isactive">
                <a class="" data-action="collapse"><i class="ti-minus"></i></a>
                <!-- <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a> -->
                <!-- <a class="btn-close" data-action="close"><i class="ti-close"></i></a> -->
            </div>
        </div>
        <div class="card-body colorcardchart collapse show" style="">
            <div class="form-row" style="justify-content: center;">
                <slot name="form-card-body"></slot> <!-- Chart cuerpo -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isactive: {
            default: 'd-block'
        },
        columnClass: {
            default: 'col-12'
        },
    }
}
</script>

<style scoped>
 .card-actions a{
     color: #77DD77;
     font-size: 1.15em;
     font-weight: bold;
 }

</style>