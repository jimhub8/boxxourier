<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="600">
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="close">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Email</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click="sendMail">Send</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text>
                <div class="container">
                    <div class="form-group">
                        <label for="">From</label>
                        <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email" v-model="order_data.mail_from" disabled>
                    </div>
                    <label for="">To</label>
                    <el-select v-model="order_data.mail_to" clearable filterable placeholder="Select">
                        <el-option v-for="item in clients.data" :key="item.id" :label="item.email" :value="item.email">
                        </el-option>
                    </el-select>
                    <label for="">Cc</label>
                    <el-select v-model="order_data.mail_cc" clearable filterable placeholder="Select">
                        <el-option v-for="item in clients.data" :key="item.id" :label="item.email" :value="item.email">
                        </el-option>
                    </el-select>
                    <label for="">Bcc</label>
                    <el-select v-model="order_data.mail_bcc" clearable filterable placeholder="Select">
                        <el-option v-for="item in clients.data" :key="item.id" :label="item.email" :value="item.email">
                        </el-option>
                    </el-select>
                    <div class="form-group">
                        <label for="">Subject</label>
                        <input type="text" class="form-control" placeholder="Subject" v-model="order_data.mail_subject">
                    </div>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn color="primary" @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="sendMail">Send</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>

export default {
    props: ['user'],
    data() {
        return {
            dialog: false,
            order_data: {
                mail_from: '',
                mail_to: '',
                mail_cc: '',
                mail_bcc: '',
            },
        }
    },
    methods: {
        close() {
            this.dialog = false
        },
        sendMail() {
            axios.post('invoiceMail', this.order_data)
                .then((response) => {
                    this.$message('Email sent');
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    } else if (error.response.status === 401 || error.response.status === 409) {
                        eventBus.$emit('reloadRequest', error.response.statusText)
                    }
                    // console.log(error.response);
                    eventBus.$emit('errorEvent', error.response.data.message)
                    this.errors = error.response.data.errors
                })
        }
    },
    computed: {
        clients() {
            return this.$store.getters.clients
        }
    },
    created () {
        eventBus.$on('openEmailEvent', data => {
            this.order_data = data
            this.order_data.mail_from = this.user.email
            this.dialog = true
        })
    },
    mounted () {
        if (this.clients.length < 1) {
            this.$store.dispatch('getClients');
        }
    },
}
</script>


<style scoped>
    .el-select {
    width: 100%;
    margin-top: 10px;
}
</style>
