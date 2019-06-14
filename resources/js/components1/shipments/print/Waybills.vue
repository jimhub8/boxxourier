<template>
    <div>
        {{ user.country_name }}
        <Tzprint v-if="user.country_name === 'Tanzania'"></Tzprint>
        <myPrintPdf v-if="user.country_name === 'Kenya'"></myPrintPdf>
        <Ugprint v-if="user.country_name === 'Uganda'"></Ugprint>
        <Rwprint v-if="user.country_name === 'Uganda'"></Rwprint>
    </div>
</template>

<script>
import myPrintPdf from './PrintPdf.vue';
import Tzprint from './Tzprint';
import Ugprint from './Ugprint';
import Rwprint from './Rwprint';
export default {
    components: {
        Tzprint, myPrintPdf, Ugprint, Rwprint
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
