<template>
    <div>
        <myPrintPdf></myPrintPdf>
    </div>
</template>

<script>
import myPrintPdf from './print/PrintPdf';
export default {
    components: {
        myPrintPdf,
    },
    props: ['user'],
data() {
    return {
        AllCountries: [],
    }
},
}
</script>

<style>

</style>
