<template>
    <div>
        <main-content>
            <template v-slot:card-header-title>
                Dashboard
            </template>

            <template v-slot:card-body-main>
                <h1>Titulo Dashboard</h1>
            </template>
        </main-content>
    </div>
</template>

<script>
    import MainContent from './../../utils/MainContent';
    export default {
        components: {
            MainContent,
        }
    }

</script>
