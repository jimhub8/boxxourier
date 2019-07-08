<template>
<el-tabs type="border-card" v-if="order_data">
    <el-tab-pane label="Packages">
        <div v-if="order_data.package">
            <table class="table table-stripped table-bordered table-hover">
                <thead>
                    <th>PACKAGE SLIP#</th>
                    <th>SHIPMENT ORDER#</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                    <th>COURIER</th>
                    <th>TRACKING#</th>
                    <th>DATE OF SHIPMENT</th>
                    <th></th>
                </thead>
                <tr>
                    <td>{{ order_data.package['package_no'] }}</td>
                    <td>{{ order_data.order_no }}</td>
                    <td>{{ order_data.package['created_at'] }}</td>
                    <td>{{ order_data.package['status'] }}</td>
                    <td></td>
                    <td>{{ order_data.order_no }}</td>
                    <td>{{ order_data.pickup_date }}</td>
                    <td>
                        <el-dropdown>
                            <v-toolbar-side-icon class="el-dropdown-link"></v-toolbar-side-icon>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item>
                                    <div @click="updateDelivered">Mark as delivered</div>
                                    </el-dropdown-item>
                                <el-dropdown-item>Print Shipment Label</el-dropdown-item>
                                <el-dropdown-item divided>Print Package Slip</el-dropdown-item>
                                <el-dropdown-item>Delete Shipment</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </td>
                </tr>
            </table>
        </div>
        <div v-else class="text-center" style="color: #777;font-size: 12px;">No Package created so far</div>
    </el-tab-pane>
    <el-tab-pane label="Invoices">
        <div v-if="order_data.invoice">
            <table class="table table-stripped table-bordered table-hover">
                <thead>
                    <th>INVOICE#</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                    <th>DUE DATE</th>
                    <th>AMOUNT#</th>
                    <th>BALANCE DUE</th>
                    <th></th>
                </thead>
                <tr>
                    <td>{{ order_data.invoice_no }}</td>
                    <td>{{ order_data.invoice_date }}</td>
                    <td>{{ order_data.invoice['status'] }}</td>
                    <td>{{ order_data.invoice['due_date'] }}</td>
                    <td>{{ order_data.invoice['amount'] }}</td>
                    <td>{{ order_data.invoice['balance'] }}</td>
                    <td>
                        <el-dropdown>
                            <v-toolbar-side-icon class="el-dropdown-link"></v-toolbar-side-icon>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item>Delete</el-dropdown-item>
                                <el-dropdown-item divided>Action 2</el-dropdown-item>
                                <el-dropdown-item>Email</el-dropdown-item>
                                <el-dropdown-item>Print</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </td>
                </tr>
            </table>
        </div>
        <div v-else class="text-center" style="color: #777;font-size: 12px;">No Invoice created so far</div>
    </el-tab-pane>
    <el-tab-pane label="Comment & History">
        <v-card class="mx-auto" max-width="400">
            <v-card dark flat>
                <v-card-title class="pa-2 white lighten-3">

                </v-card-title>
            </v-card>
            <v-card-text class="py-0">
                <v-timeline align-top dense>
                    <v-timeline-item color="pink" small v-for="history in order_data.order_history" :key="history.id">
                        <v-layout pt-3>
                            <v-flex xs3>
                                <small>{{ history.created_at }}</small>
                            </v-flex>
                            <v-flex style="margin-left: 20px;">
                                <small>{{ history.update_instructions }}</small>
                                <div class="caption">{{ history.reference_no }}</div>
                            </v-flex>
                        </v-layout>
                    </v-timeline-item>
                </v-timeline>
            </v-card-text>
        </v-card>
    </el-tab-pane>
</el-tabs>
</template>

<script>
export default {
    props: ['order_data'],
    name: 'orderTabs',
    methods: {
        updateDelivered() {
            axios.post(`orderDelivered/${this.order_data.id}`)
            .then((response) => {
                this.$store.dispatch('alertEvent', 'Order updated')
            })
        }
    },
}
</script>
