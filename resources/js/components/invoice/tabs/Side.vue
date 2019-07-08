<template>
<v-content>
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <el-container style="height: 100vh; border: 1px solid #eee">
                <el-aside width="200px" style="background-color: rgb(238, 241, 246)">
                    <el-menu :default-openeds="['1', '2', '3', '4']">
                        <VCard style="padding: 0 10px;max-height: 800px;overflow: scroll;">
                            <v-item-group v-model="window" class="shrink mr-4" mandatory tag="v-flex">
                                <v-item v-for="order in orders.data" :key="order.id">
                                    <div>
                                        <v-list two-line>
                                            <template>
                                                <v-subheader style="font-size: 12px">
                                                    {{ order.client_name }}
                                                </v-subheader>
                                                <v-list-tile @click="openInvoice(order)">
                                                    <v-list-tile-content>
                                                        <v-list-tile-title style="font-size: 11px">{{ order.created_at }}</v-list-tile-title>
                                                        <v-list-tile-sub-title style="font-size: 10px">{{ order.order_no }}</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                            </template>
                                            <v-divider></v-divider>
                                        </v-list>
                                    </div>
                                </v-item>
                            </v-item-group>
                        </VCard>
                    </el-menu>
                </el-aside>

                <el-container>
                    <el-header style="text-align: right; font-size: 12px">
                        <myHeader :user="user"></myHeader>
                    </el-header>
                    <orderHistory :order_data="order_data"></orderHistory>
                    <el-main id="print">
                        <myInvoice></myInvoice>
                    </el-main>
                </el-container>
            </el-container>
        </v-layout>
    </v-container>
</v-content>
</template>

<script>
import {
    image
} from "../image";
import jspdf from 'jspdf'
import myHeader from './Header'
import myInvoice from '../Invoice'
import orderHistory from './Tabs'
export default {
    props: ['user'],
    components: {
        myInvoice,
        myHeader,
        orderHistory
    },
    data() {
        const item = {
            date: '2016-05-02',
            name: 'Tom',
            address: 'No. 189, Grove St, Los Angeles'
        };
        return {
            tableData: Array(20).fill(item),
            length: 3,
            window: 0,
            order_data: {},
            text: 'center',
            icon: 'justify',
            toggle_none: null,
            toggle_one: 0,
            toggle_exclusive: 2,
            toggle_multiple: [0, 1, 2],

        }
    },

    methods: {
        openInvoice(data) {
            eventBus.$emit('invoiceEvent', data)
        },
        getOrders() {
            this.$store.dispatch('getOrders')
        },
        printPage() {
            // Get HTML to print from element
            const prtHtml = document.getElementById('print').innerHTML;

            // Get all stylesheets HTML
            let stylesHtml = '';
            for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
                stylesHtml += node.outerHTML;
            }

            // Open the print window
            const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

            WinPrint.document.write(`<!DOCTYPE html>
            <html>
            <head>
                ${stylesHtml}
            </head>
            <body>
                ${prtHtml}
            </body>
            </html>`);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
            // var doc = new jspdf();
            // var specialElementHandlers = {
            //     '#print': function (element, renderer) {
            //         return true;
            //     }
            // };

            // $('#print').click(function () {
            //     doc.fromHTML($('#print').html(), 15, 15, {
            //         'width': 170,
            //         'elementHandlers': specialElementHandlers
            //     });
            //     doc.save('sample-file.pdf');
            // });
        },

        handleClick(tab, event) {
            // console.log(tab, event);
            eventBus.$emit('invoiceLoadEvent')
            // this.loading = true
            // setTimeout(() => {
            //     this.loading = false
            // }, 1000);
        },
        invoiceOpen(data) {
            eventBus.$emit('invoiceEvent', data)
        }
    },
    created() {
        eventBus.$on('printInvoiceEvent', data => {
            this.printPage()
        });
        eventBus.$on('invoiceEvent', data => {
            // console.log(data);
            this.order_data = data
        });
    },

    computed: {
        orders() {
            return this.$store.getters.orders
        }
    },
    mounted() {
        this.getOrders();
        this.invoiceOpen(this.$route.params.data)
    },
};
</script>

<style scoped>
.el-header {
    background-color: #B3C0D1;
    color: #333;
    line-height: 60px;
}

.el-aside {
    color: #333;
}

.el-submenu__title i {
    display: none !important;
}

.el-submenu__icon-arrow {
    display: none !important;
}

.v-btn-toggle .v-btn:not(:last-child) {
    border-right: 2px solid #f0f0f0 !important;
}

.el-dropdown-link {
    cursor: pointer;
    color: #409EFF;
}

.el-icon-arrow-down {
    font-size: 12px;
}
</style>
