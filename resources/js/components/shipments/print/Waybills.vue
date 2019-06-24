<template>
    <div>
        {{ user.country_name }}
        <myPrintPdf></myPrintPdf>
    </div>
</template>

<script>
import myPrintPdf from './PrintPdf.vue';
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
mounted() {
    axios.get('/getCountry')
            .then((response) => {
                this.AllCountries = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
    },
}
</script>

<style>

</style>
