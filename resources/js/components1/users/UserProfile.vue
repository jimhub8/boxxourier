<template>
<v-layout row justify-center>
    <v-dialog v-model="openShowRequest" persistent max-width="1000px">
        <v-card v-if="openShowRequest">
            <v-card-title>
                <span class="headline">{{ user.name }} Profile</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <!-- <avatar :username="user.name" style="font-size: 40px;margin: auto;padding: 50px;"></avatar> -->
                <img :src="user.profile" :alt="user.name" style="width: 150px; height: 150px; border-radius: 50%; text-align:center; margin-top 70px;margin-left-100px;margin-left: 45%;">
                <ul class="list-group">
                    <li class="list-group-item active"><b>Client Details</b></li>
                    <li class="list-group-item"><label for="">Name:</label><span style="margin-right: 100px; float: right;">{{ user.name }}</span></li>
                    <li class="list-group-item"><label for="">Role:</label><span style="margin-right: 100px; float: right;" v-for="role in user.roles" :key="role.id">{{ role.name }}</span></li>
                    <li class="list-group-item"><label for="">Phone:</label><span style="margin-right: 100px; float: right;">{{ user.phone }}</span></li>
                    <li class="list-group-item"><label for="">Email:</label><span style="margin-right: 100px; float: right;">{{ user.email }}</span></li>
                    <li class="list-group-item"><label for="">City:</label><span style="margin-right: 100px; float: right;">{{ user.city }}</span></li>
                    <li class="list-group-item"><label for="">Address:</label><span style="margin-right: 100px; float: right;">{{ user.address }}</span></li>
                </ul>
                <v-container grid-list-md>
                    <div id="scrolling-techniques" class="scroll-y" style="max-height: 600px;">
                        <div v-show="loader" style="text-align: center; width: 100%; margin-top: 200px;">
                            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                        </div>
                        <div class="hide-overflow" style="position: relative;" v-if="AllShip.data.length > 0" v-show="!loader">
                            <v-toolbar dark color="primary">
                                <v-toolbar-title class="white--text">Shipments</v-toolbar-title>
                            </v-toolbar>

                            <v-layout wrap>
                                <v-flex sm6 v-for="ships in AllShip.data" :key="ships.id">
                                    <ul class="list-group">
                                        <li class="list-group-item active"><b>Shipment Details {{ ships.bar_code }}</b></li>
                                        <li class="list-group-item"><label for="">WayBill Number:</label><span style="float: right;">{{ ships.bar_code }}</span></li>
                                        <li class="list-group-item"><label for="">Booking Date:</label><span style="float: right;">{{ ships.booking_date }}</span></li>
                                        <li class="list-group-item"><label for="">From:</label><span style="float: right;">{{ ships.from_city }}</span></li>
                                        <li class="list-group-item"><label for="">To:</label><span style="float: right;">{{ ships.to_city }}</span></li>
                                        <li class="list-group-item"><label for="">Status:</label><span style="float: right;">{{ ships.status }}</span></li>
                                    </ul>
                                </v-flex>
                                <v-pagination v-model="AllShip.current_page" :length="AllShip.last_page" total-visible="7" @input="next" circle></v-pagination>

                            </v-layout>
                            <!-- <v-flex xs12>
                                <v-expansion-panel inset>
                                    <v-expansion-panel-content>
                                        <div slot="header">Permissions</div>
                                        <v-card>
                                            <v-card-text>
                                                <v-layout wrap>
                                                    <div v-for="perm in permissions" :key="perm.id">
                                                        <v-flex xs12 sm12>
                                                            <v-checkbox v-model="selected" :label="perm.name" :value="perm.name"></v-checkbox>
                                                        </v-flex>
                                                    </div>
                                                </v-layout>
                                            </v-card-text>
                                        </v-card>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-flex> -->
                        </div>
                    </div>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" flat @click.native="close">Close</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import Avatar from 'vue-avatar'
export default {
    props: ['user', 'openShowRequest'],
    components: {
        Avatar
    },
    data() {
        return {
            AllShip: {
                data: []
            },
            pagin: [],
            permissions: [],
            // nextPage: false,
            loader: false,
            // AllShip: [],
        }
    },
    methods: {
        next(page) {
            this.loader = true
            axios.post(`/getUserPro/${this.pagin.id}?page=` + this.AllShip.current_page)
                .then((response) => {
                    this.loader = false
                    this.AllShip = response.data
                })
        },
        getShipmentsCl(data) {
            this.loader = true
            axios.post(`/getUserPro/${data.id}`)
                .then((response) => {
                    this.loader = false
                    this.AllShip = response.data
                })
                .catch((error) => {
                    this.loader = false
                    this.errors = error.response.data.errors
                })
        },
        close() {
            this.$emit('closeRequest')
        }
    },
    created() {
        eventBus.$on('getShipEvent', data => {
            this.getShipmentsCl(data)
            this.pagin = data
        });
    },
    mounted() {
        axios.get('/getPermissions')
            .then((response) => {
                this.permissions = response.data
            })
            .catch((errors) => {
                this.errors = error.response.data.errors
            })
    }
}
</script>
