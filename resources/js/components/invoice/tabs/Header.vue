<template>
<div>
    <v-btn-toggle v-model="toggle_exclusive">
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn color="white" flat v-on="on">
                    <v-icon>edit</v-icon>
                </v-btn>
            </template>
            <span>Edit</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn color="white" flat v-on="on">
                    <v-icon>picture_as_pdf</v-icon>
                </v-btn>
            </template>
            <span>PDF</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn color="white" flat v-on="on" @click="printPage">
                    <v-icon>print</v-icon>
                </v-btn>
            </template>
            <span>Print</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn color="white" flat v-on="on" @click="sendMail">
                    <v-icon>email</v-icon>
                </v-btn>
            </template>
            <span>Email</span>
        </v-tooltip>
    </v-btn-toggle>
    <el-dropdown>
        <el-button type="primary">
            Create<i class="el-icon-arrow-down el-icon--right"></i>
        </el-button>
        <el-dropdown-menu slot="dropdown">
            <el-dropdown-item>
                <a href="#" @click="create_list('packages')" v-if="!order_data.packaged">Package</a>
            </el-dropdown-item>
            <!-- <el-dropdown-item>Shipment</el-dropdown-item> -->
            <el-dropdown-item v-if="!order_data.invoiced">
                <a href="#" @click="create_list('invoice')" v-if="!order_data.invoiced">Invoice</a>
            </el-dropdown-item>
        </el-dropdown-menu>
    </el-dropdown>
    <el-dropdown>
        <el-button type="primary">
            More<i class="el-icon-arrow-down el-icon--right"></i>
        </el-button>
        <el-dropdown-menu slot="dropdown">
            <el-dropdown-item>Convert to Purchase Order</el-dropdown-item>
            <el-dropdown-item>Mark Shipment as Fulfilled</el-dropdown-item>
            <el-dropdown-item>Void</el-dropdown-item>
            <el-dropdown-item>Dropship</el-dropdown-item>
            <el-dropdown-item divided>Clone Sales Order</el-dropdown-item>
            <el-dropdown-item>Delete</el-dropdown-item>
        </el-dropdown-menu>
    </el-dropdown>
    <myEmail :user="user"></myEmail>
</div>
</template>

<script>
import myEmail from '../../email/Email'
export default {
    props: ['user'],
    components: {
        myEmail,
    },
    data() {
        return {
            toggle_exclusive: 2,
            order_data: {},
            errors: {}
        }
    },
    methods: {
        printPage() {
            eventBus.$emit('printInvoiceEvent')
        },
        handleCommand(command) {
            this.$message('click on item ' + command);
        },
        create_list(url) {
            // this.$message('Package list created');
            axios.post(url, this.order_data)
                .then((response) => {
                    this.$message(url + ' created');
                    this.$store.dispatch('getOrders')
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    } else if (error.response.status === 401 || error.response.status === 409) {
                        eventBus.$emit('reloadRequest', error.response.statusText)
                    }
                    console.log(error.response);

                    eventBus.$emit('errorEvent', error.response.data.message)
                    this.errors = error.response.data.errors
                })

        },
        sendMail() {
            eventBus.$emit('openEmailEvent', this.order_data)
            /* axios.post('invoiceMail', this.order_data)
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
                    console.log(error.response);

                    eventBus.$emit('errorEvent', error.response.data.message)
                    this.errors = error.response.data.errors
                }) */
        },

        getClients() {
            eventBus.$emit("LoadingEvent");
            this.$store.dispatch('getClients');
        },
    },
    created() {
        eventBus.$on('invoiceEvent', data => {
            // console.log(data);
            this.order_data = data
        });
    },
}
</script>

<style scoped>
.theme--light.v-btn-toggle {
    background: #1716168c !important;
}
</style>
