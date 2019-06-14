<template>
    <div>
        <TzSprint v-for="country in AllCountries" :key="country.id" v-if="user.country_id == country.id"></TzSprint>
        <PrintSpdf v-for="country in AllCountries" :key="country.id" v-if="user.country_id == country.id"></PrintSpdf>
    </div>
</template>

<script>
import PrintSpdf from './PrintSpdf.vue';
import TzSprint from './TzSprint';
export default {
    components: {
        TzSprint, PrintSpdf 
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
