<template>
<div>
    <v-dialog v-model="openRequest" persistent max-width="600px">
        <v-card>
            <v-card-title>
            </v-card-title>
            <v-container grid-list-md v-show="!loader">
                <v-card-text>
                    <v-layout wrap>
                        <v-content>
                            <div v-show="loader" style="text-align: center; width: 100%;">
                                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                            </div>
                            <v-container fluid fill-height v-show="!loader">
                                <v-layout justify-center align-center>
                                    <file-management :settings="props" @closeRequest="close"></file-management>
                                    <attachment-list :settings="props"></attachment-list>
                                </v-layout>
                            </v-container>
                        </v-content>
                    </v-layout>
                </v-card-text>
                <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
                    {{ message }}
                    <v-icon dark right>check_circle</v-icon>
                </v-snackbar>
            </v-container>
        </v-card>
    </v-dialog>
</div>
</template>

<script>
export default {
    props: ['openRequest'],
    components: {},
    data() {
        return {
            props: [],
            snackbar: false,
            loading: false,
            timeout: 5000,
            color: 'black',
            message: '',
            loader: false
        }
    },

    methods: {
        close() {
            this.$emit('closeRequest')
        }
    },

    mounted() {
        axios.get('/upload')
            .then((response) => {
                this.props = response.data
            })
            .catch(function (error) {
                console.log(error);
                // window.Event.fire('loading_off');
            });
    }
}
</script>
