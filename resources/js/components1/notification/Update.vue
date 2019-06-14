<template>
<v-layout row justify-center>

    <v-dialog v-model="dialog" persistent width="700px">
        <v-card v-if="dialog">
            <v-card-title>
                
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-container grid-list-md>
                <div>
                    <v-tabs v-model="active" color="cyan" dark slider-color="yellow">
                        <v-tab ripple>
                            Update Shipment
                        </v-tab>
                        
                        <v-tab ripple>
                            Assign Rider
                        </v-tab>
                        <v-tab-item>
                            <UpdateShipment></UpdateShipment>
                        </v-tab-item>
                        <v-tab-item>
                            <AssignDriver></AssignDriver>
                        </v-tab-item>
                    </v-tabs>

                    <div class="text-xs-center mt-3">
                        <v-btn @click="next" color="orange" flat>next tab</v-btn>
                    </div>
                </div>
            </v-container>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import UpdateShipment from './UpdateShipment';
import AssignDriver from './AssignDriver';
export default {
    components: {
        AssignDriver,
        UpdateShipment
    },
    data() {
        return {
            active: null,
            dialog: false,
        };
    },
    methods: {
        next () {
        const active = parseInt(this.active)
        this.active = (active < 2 ? active + 1 : 0)
      },
        close() {
            this.dialog = false
        }
    },
    created() {
        eventBus.$on('updateEvent', data => {
            this.dialog = true
            this.updateitedItem = data
        })
    },
};
</script>
