<template>
<v-layout row justify-center>
    <v-dialog v-model="mySCharges" persistent width="800px">
        <v-card v-if="mySCharges">
            <v-card-title>
                Charges
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-container grid-list-md>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-container fill-height>
                            <v-layout align-center>
                                <v-flex sm12>
                                    <v-flex sm12>
                                        <v-radio-group v-model="Stype" :mandatory="false">
                                            <v-radio label="OVS" value="OVS"></v-radio>
                                            <v-radio label="Distance Based" value="dist"></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                    <div v-if="Stype === 'dist'">
                                        <v-select :items="AllTowns" v-model="select" label="Select Town" single-line item-text="town_name" item-value="id" return-object persistent-hint></v-select>
                                        <div v-for="charge in select.charges" :key="charge.id">
                                            <v-layout wrap>
                                                <v-flex sm4>
                                                    <v-text-field v-model="charge.charge" color="blue darken-2" label="Charge" disabled></v-text-field>
                                                </v-flex>
                                                <v-flex sm4>
                                                    <v-text-field v-model="charge.vat" color="blue darken-2" label="VAT" disabled></v-text-field>
                                                </v-flex>
                                                <v-flex sm4>
                                                    <v-text-field v-model="charge.total" color="blue darken-2" label="Total" disabled></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </div>
                                    </div>
                                    <div v-if="Stype === 'OVS'">
                                        <v-flex sm12>
                                            <v-text-field v-model="dist" disabled color="blue darken-2" label="Distance" type="number" required></v-text-field>
                                        </v-flex>
                                        <v-layout wrap>
                                            <v-flex sm4>
                                                <v-text-field v-model="getCharge" color="blue darken-2" label="Charge" type="number" disabled></v-text-field>
                                            </v-flex>
                                            <v-flex sm4>
                                                <v-text-field v-model="getVat" color="blue darken-2" label="VAT" disabled></v-text-field>
                                            </v-flex>
                                            <v-flex sm4>
                                                <v-text-field v-model="gettotal" color="blue darken-2" label="Total" disabled></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout wrap>
                                            <v-flex sm6>
                                                <GmapAutocomplete @place_changed="setPlace" class="form-control" v-show="showMap">
                                                </GmapAutocomplete>
                                            </v-flex>
                                            <v-flex sm6>
                                                <v-card-actions>
                                                    <v-btn flat color="primary" @click="usePlace" v-show="showMap" class="col-md-6">Add</v-btn>
                                                    <v-spacer></v-spacer>
                                                    <v-btn flat color="primary" @click="mapUpsd" v-show="!showMap" class="col-md-6">Update Location</v-btn>
                                                    <!-- <v-btn flat color="primary" @click="calcCrow" :loading="loading_loc" :disabled="loading_loc" class="col-md-6" v-show="showMap">Update Location</v-btn> -->
                                                    <v-btn flat color="primary" @click="locationUpdate" :loading="loading_loc" :disabled="loading_loc" class="col-md-6" v-show="showMap">Update Location</v-btn>
                                                </v-card-actions>
                                            </v-flex>
                                        </v-layout>

                                        <GmapMap style="width: 700px; height: 450px;" :zoom="4" :center="{lat: -1.3072985, lng: 36.908417299999996}">
                                            <GmapMarker v-for="(marker, index) in markers" :key="index" :position="marker.position" />
                                            <GmapMarker v-if="this.place" label="★" :position="{
                                                lat: this.place.geometry.location.lat(),
                                                lng: this.place.geometry.location.lng(),
                                                }" />
                                        </GmapMap>
                                        <v-divider></v-divider>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-flex>
                </v-layout>
                <v-card-actions>
                    <v-btn flat @click="close">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn flat @click="update" :loading="loading" :disabled="loading" color="primary">Update</v-btn>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ["updateCharges", "mySCharges", 'markers'],
    data() {
        return {
            AllTowns: [],
            form: {
                type: "",
                distance: "",
                charges: ""
            },
            showMap: false,
            select: [],
            Stype: "OVS",
            loading: false,
            loading_loc: false,
            // markers: [],
            place: null,
            dist: "",
            snackbar: false,
            timeout: 5000,
            message: "Success",
            color: "black",
        };
    },
    methods: {
        update() {
            this.loading = true;
            axios
                .post(`/shipCharge/${this.updateCharges.id}`, {
                    form: this.form,
                    select: this.select,
                    distance: this.getCharge,
                    Stype: this.Stype
                })
                .then(response => {
                    this.loading = false;
                    // console.log(response);
                    this.$emit("alertRequest");
                    Object.assign(this.$parent.AllShipments[this.$parent.editedIndex], this.$parent.shipment)
                    this.close()
                })
                .catch(error => {
                    this.loading = false;
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    } else if (error.response.status === 401 || error.response.status === 409) {
                        eventBus.$emit('reloadRequest', error.response.statusText)
                    }
                    this.errors = error.response.data.errors;
                });
        },
        close() {
            this.$emit("closeRequest");
        },

        locationUpdate() {
            this.calcCrow()
            this.loading_loc = true;
            this.updateCharges.location = this.updateCharges.location;
            axios
                .post(`/locationUpdate/${this.updateCharges.id}`, {
                    markers: this.markers,
                    dist: this.dist,
                })
                .then(response => {
                    eventBus.$emit("distanceEvent", this.dist);

                    this.loading_loc = false;
                    // Object.assign(this.$parent.AllShipments[this.$parent.editedIndex], this.updateitedItem)
                })
                .catch(error => {
                    this.loading_loc = false;
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    } else if (error.response.status === 401 || error.response.status === 409) {
                        eventBus.$emit('reloadRequest', error.response.statusText)
                    }
                    this.errors = error.response.data.errors;
                });
        },
        setDescription(description) {
            this.description = description;
        },
        setPlace(place) {
            this.place = place;
        },
        usePlace(place) {
            if (this.place) {
                this.markers.push({
                    position: {
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng()
                    }
                });
                this.place = null;
            }
        },
        usePlace(place) {
            if (this.place) {
                this.markers.push({
                    position: {
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng()
                    }
                });
                this.place = null;
            }
        },
        getDistance() {
            this.dist = computeDistanceBetween((-1.2920659, 36.82194619999996), (-4.0434771, 39.6682065));
            return

            var dist = []
            for (let i = 0; i < this.markers.length; i++) {
                const element = this.markers[i];
                // console.log(element)
                // alert(element['position']['lat'])
                var p1 = new google.maps.LatLng(element['position']['lat'], element['position']['lng']);
                // var p2 = new google.maps.LatLng(element['position']['lat'], element['position']['lng']);
                dist.push(p1)
                // alert(p1)
                // var p2 = new google.maps.LatLng(-4.05052, 39.667169);
            }

            alert(dist)
            console.log(computeDistanceBetween(dist))

            function calcDistance(dist) {
                return this.dist = (google.maps.geometry.spherical.computeDistanceBetween(dist) / 1000).toFixed(2);
            }
        },

        mapUpsd() {
            this.markers = [];
            this.showMap = true;
        },
        calcCrow(lat1, lon1, lat2, lon2) {
            var dist = []
            for (let i = 0; i < this.markers.length; i++) {
                const element = this.markers[i];
                var p1 = element['position']['lat'];
                var l1 = element['position']['lng'];
                dist.push(p1, l1)
            }
            var lat1 = dist[0]
            var lon1 = dist[1]
            var lat2 = dist[2]
            var lon2 = dist[3]
            var R = 6371; // km
            var dLat = this.toRad(lat2 - lat1);
            var dLon = this.toRad(lon2 - lon1);
            var lat1 = this.toRad(lat1);
            var lat2 = this.toRad(lat2);

            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat1) * Math.cos(lat2);
            console.log(a);

            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            console.log(c);

            var d = R * c;
            this.dist = d
            return d;
        },

        // Converts numeric degrees to radians
        toRad(Value) {
            return Value * Math.PI / 180;
        }
    },
    computed: {
        getCharge() {
            if (this.form.distance <= 5) {
                return parseInt(200);
            } else {
                return (
                    (parseInt(this.form.distance) - parseInt(5)) * parseInt(25) +
                    parseInt(200)
                );
            }
        },
        getVat() {
            return parseInt(this.getCharge) * parseFloat(0.16);
        },
        gettotal() {
            return parseInt(this.getCharge) + parseFloat(this.getVat);
        }
    },
    mounted() {
        axios
            .get("/getTownCharge")

            .then(response => {
                this.AllTowns = response.data;

                this.loader = false;
            })

            .catch(error => {
                console.log(error);

                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if (error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                this.errors = error.response.data.errors;

                this.loader = false;
            });
    }
};
</script>
