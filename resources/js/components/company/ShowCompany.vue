<template>
<v-layout row justify-center>
    <v-dialog v-model="openShowRequest" persistent max-width="700px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">{{list.name}}' Details</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <div class="col-md-12 col-sm-12">
                        <ul class="list-group">
                            <li class="list-group-item row active">
                                <label class="col-md-5 col-lg-5"><b>Name</b></label> {{ list.name }}
                            </li>

                            <li class="list-group-item row">
                                <label class="col-md-5 col-lg-5"><b>Email</b></label> {{ list.email }}
                            </li>
                            <li class="list-group-item row">
                                <label class="col-md-5 col-lg-5"><b>Phone Number</b></label> {{ list.phone }}
                            </li>
                            <li class="list-group-item row">
                                <label class="col-md-5 col-lg-5"><b>Address</b></label> {{ list.address }}
                            </li>
                            <li class="list-group-item row">
                                <label class="col-md-5 col-lg-5"><b>Country</b></label> {{ list.country }}
                            </li>
                            <li class="list-group-item row">
                                <label class="col-md-5 col-lg-5"><b>City</b></label> {{ list.city }}
                            </li>
                            <li class="list-group-item row">
                                <label class="col-md-5 col-lg-5"><b>Branch</b></label> {{ list.branch }}
                            </li>
                            <li class="list-group-item row">
                                <label class="col-md-5 col-lg-5"><b>Zip Code</b></label> {{ list.zipcode }}
                            </li>
                            <li class="list-group-item row">
                                <label class="col-md-5 col-lg-5"><b>Created On</b></label> {{ list.created_at }}
                            </li>
                        </ul>
                    </div>
                </v-container>
            </v-card-text>
            <v-btn flat @click="close">Close</v-btn>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['openShowRequest'],
    data() {
        return {
            list: {}
        }
    },
    methods: {
        close() {
            this.$emit('closeRequest')
        },
    },
    mounted() {

    }
}
</script>
