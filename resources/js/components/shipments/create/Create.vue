<template>
<v-layout row justify-center>
    <v-dialog v-model="adddialog" persistent>
        <v-card>
            <v-card-title>
                Add Shipment
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-container grid-list-md>
                <v-card-text>
                    <div v-for="role in user.roles" :key="role.id" v-if="role.name === 'Client'">
                        <Client :Allcustomer="Allcustomers" :user="user" :role="role" :AllBranches="AllBranches" :AllDrivers="AllDrivers"></Client>
                    </div>
                    <div v-else>
                        <AddShipment :Allcustomer="Allcustomers" :user="user" :role="role" :AllBranches="AllBranches" :AllDrivers="AllDrivers"></AddShipment>
                    </div>
                </v-card-text>
                <v-card-actions>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import VueBarcode from "vue-barcode";
    
import AddShipment from './AddShipment'
import Client from './Client'
export default {
    props: ["adddialog", "user", 'role', 'Allcustomers', 'AllDrivers', 'AllBranches'],
    components: {
        Client,
        AddShipment
    },
    data() {
        return {}
    },
    methods: {
        
        close() {
            this.$emit("closeRequest");
        },
    },

};
</script>
