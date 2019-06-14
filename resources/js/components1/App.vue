<template>
<div>
    <div v-for="role in user.roles" :key="role.id">
        <div v-if="role.name === 'Rider'">
            <DrDashboard></DrDashboard>
        </div>
        <div v-else-if="role.name === 'Client'">
            <ClDashboard></ClDashboard>
        </div>
        <div v-else>
            <Dashboard :user="user"></Dashboard>
        </div>
    </div>
</div>
</template>

<script>
import Dashboard from "./Dashboard.vue";
import DrDashboard from "./customers/Dashboard.vue";
import ClDashboard from "./drivers/Dashboard.vue";
export default {
    props: ["user"],
    components: {
        Dashboard,
        DrDashboard,
        ClDashboard
    }
};
</script>

<style>
</style>
