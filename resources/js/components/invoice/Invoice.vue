<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
        <VCard>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Orders</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click="printPage">Print</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text id="print">
                <div class="invoice-box" v-loading="loading">
                    <div class="container">
                        <!-- <div class="ribbon">
                            <div class="ribbon-inner ribbon-draft">Draft</div>
                        </div> -->
                        <table cellpadding="0" cellspacing="0" class="table table-hover">
                            <tr class="top">
                                <td colspan="4">
                                    <table>
                                        <tr>
                                            <td>
                                                <img :src="image" style="width:100%; max-width:150px;">  <br>
                                                Sender details.<br> Boxleo Courier & Fulfillment Services<br>

                                            </td>
                                            <td>
                                                Date :{{ today.getFullYear() }}<br>
                                                Client Name : {{ invoice_data.client['name'] }}
                                                <br>
                                                Client Email : {{ invoice_data.client['email'] }}
                                                <br>
                                                Client Address : {{ invoice_data.client['address'] }}
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th scope="col">Item & Description</th>
                                    <th scope="col">Quantity</th>
                                    <!-- <th scope="col">Cod</th> -->
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item" v-for="(item, index) in invoice_data.shipment" :key="index">
                                    <td style="text-align: left;">{{ index + 1 }}</td>
                                    <td style="text-align: left;">{{ item.airway_bill_no }} <br>
                                        <small>{{ item.amount_ordered }}</small>
                                    </td>
                                    <td style="text-align: left;">{{ item.amount_ordered }}</td>
                                    <td style="text-align: left;">{{ item.cod_amount }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <td></td>
                                <td></td>
                                <td></td>
                                <!-- <td><b>Total</b></td> -->
                                <!-- <td><b>{{ invoice_total }}</b></td> -->
                            </tfoot>
                        </table>
                        <v-divider></v-divider>
                        <!-- <h5>Notes</h5> -->
                        <br>
                        <!-- <small>{{ invoice_data.shipment.instructions }}</small> -->
                    </div>
                </div>
            </v-card-text>
        </VCard>
    </v-dialog>
</v-layout>
</template>

<script>
import {
    image
} from "./image";
export default {
    // props: ['image'],
    data() {
        return {
            today: new Date(),
            dialog: false,
            image: '',
            invoice_data: [],
            loading: false,
            activeName: 'first'
        }
    },
    computed: {
        invoice_total() {
            var total = 0
            if (this.invoice_data.shipment.length > 0) {
                this.invoice_data.shipment.forEach(element => {
                    total = parseFloat(total) + (parseFloat(element.cod_amount))
                });
                return total
            }
        }
    },
    methods: {
        handleClick(tab, event) {
            console.log(tab, event);
            this.loading = true
            setTimeout(() => {
                this.loading = false
            }, 1000);
        },
        loadPage() {
            this.loading = true
            setTimeout(() => {
                this.loading = false
            }, 1000);
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
        },

    },
    // filters: {
    //     currency(value) {
    //         return value.toFixed(2);
    //     },
    // },
    created() {
        eventBus.$on('invoiceEvent', data => {
            // console.log(data);
            this.dialog = true

            // setTimeout(() => {
            //     this.loading = false
            // }, 1000);
            this.invoice_data = data
        });
        eventBus.$on('invoiceLoadEvent', data => {
            this.loading = true
            setTimeout(() => {
                this.loading = false
            }, 1000);
        });
        // eventBus.$on('invoiceLoadEvent', data => {
        //     this.loading = true
        //     setTimeout(() => {
        //         this.loading = false
        //     }, 1000);
        // });
    },
    mounted() {
        // console.log(image);
        this.image = image
    },
}
</script>

<style scoped>
.invoice-box {
    max-width: 800px;
    margin: auto;
    padding: 30px;
    border: 1px solid #eee;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
    font-size: 16px;
    line-height: 24px;
    font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    color: #555;
}

.invoice-box table {
    width: 100%;
    line-height: inherit;
    text-align: left;
}

.invoice-box table td {
    padding: 5px;
    vertical-align: top;
}

.invoice-box table tr td:nth-child(n + 2) {
    text-align: right;
}

.invoice-box table tr.top table td {
    padding-bottom: 20px;
}

.invoice-box table tr.top table td.title {
    font-size: 45px;
    line-height: 45px;
    color: #333;
}

.invoice-box table tr.information table td {
    padding-bottom: 40px;
}

.invoice-box table tr.heading td {
    background: #eee;
    border-bottom: 1px solid #ddd;
    font-weight: bold;
}

.invoice-box table tr.details td {
    padding-bottom: 20px;
}

.invoice-box table tr.item td {
    border-bottom: 1px solid #eee;
}

.invoice-box table tr.item.last td {
    border-bottom: none;
}

.invoice-box table tr.item input {
    padding-left: 5px;
}

.invoice-box table tr.item td:first-child input {
    margin-left: -5px;
    width: 100%;
}

.invoice-box table tr.total td:nth-child(2) {
    border-top: 2px solid #eee;
    font-weight: bold;
}

.invoice-box input[type="number"] {
    width: 60px;
}

@media only screen and (max-width: 600px) {
    .invoice-box table tr.top table td {
        width: 100%;
        display: block;
        text-align: center;
    }

    .invoice-box table tr.information table td {
        width: 100%;
        display: block;
        text-align: center;
    }
}

/** RTL **/
.rtl {
    direction: rtl;
    font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial,
        sans-serif;
}

.rtl table {
    text-align: right;
}

.rtl table tr td:nth-child(2) {
    text-align: left;
}

.ribbon {
    margin: -27px;
    position: absolute !important;
    top: -5px;
    left: -5px;
    overflow: hidden;
    width: 96px;
    height: 94px;
    border-bottom-right-radius: 92px;
}

.ribbon .ribbon-draft {
    background: #94a5a6;
    border-color: #788e8f;
}

.ribbon .ribbon-inner {
    text-align: center;
    color: #FFF;
    top: 24px;
    left: -31px;
    width: 135px;
    padding: 3px;
    position: relative;
    transform: rotate(-45deg);
}

.ribbon .ribbon-inner:before {
    left: 0;
    border-left: 2px solid transparent;
}

.ribbon .ribbon-inner:after,
.ribbon .ribbon-inner:before {
    content: "";
    border-top: 5px solid transparent;
    border-left: 5px solid;
    border-left-color: inherit;
    border-right: 5px solid transparent;
    border-bottom: 5px solid;
    border-bottom-color: inherit;
    position: absolute;
    top: 20px;
    transform: rotate(-45deg);
}

.ribbon .ribbon-inner:after {
    right: -4px;
    top: 22px;
    border-bottom: 3px solid transparent;
}

.ribbon .ribbon-inner:after,
.ribbon .ribbon-inner:before {
    content: "";
    border-top: 5px solid transparent;
    border-left: 5px solid;
    border-left-color: inherit;
    border-right: 5px solid transparent;
    border-bottom: 5px solid;
    border-bottom-color: inherit;
    position: absolute;
    top: 20px;
    transform: rotate(-45deg);
}
</style>
