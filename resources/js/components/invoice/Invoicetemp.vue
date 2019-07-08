<template>
<div>
    <div class="invoice-box" v-loading="loading">
        <div class="container">
            <div class="ribbon">
                <div class="ribbon-inner ribbon-draft">Draft</div>
            </div>
            <table cellpadding="0" cellspacing="0" class="table table-hover">
                <tr class="top">
                    <td colspan="4">
                        <table>
                            <tr>
                                <td class="title">
                                    <img :src="image" style="width:100%; max-width:200px;">
                                </td>
                                <td>
                                    Order Date : 123<br> {{ invoice_data.created_at }}<br> Shipment Date : {{ invoice_data.pickup_date }}
                                    <br>
                                    Delivery Method : {{ invoice_data.delivery_method }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="information">
                    <td colspan="4">
                        <table>
                            <tr>
                                <td>
                                    Sparksuite, Inc.<br> 12345 Sunny Road<br> Sunnyville, CA 12345
                            </td>
                                <td>
                                    Acme Corp.<br> John Doe<br> john@example.com
                            </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- <tr class="heading">
                    <td colspan="3">Payment Method</td>
                    <td>{{ invoice_data.payment_type }} #</td>
                </tr>
                <tr class="details">
                    <td colspan="3">Check</td>
                    <td>1000</td>
                </tr> -->
            </table>
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th scope="col">Item & Description</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Rate</th>
                        <th scope="col">Amout</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="item" v-for="(item, index) in invoice_data.product" :key="index">
                        <td style="text-align: left;">{{ index + 1 }}</td>
                        <td style="text-align: left;">{{ item.product_name }} <br>
                            <small>{{ item.description }}</small>
                        </td>
                        <td style="text-align: left;">
                            <!-- <el-input placeholder="Qty" :disabled="true" type="number" v-model="item.order_qty"></el-input> -->
                            {{ item.order_qty }}
                        </td>
                        <td style="text-align: left;">
                            <!-- <el-input placeholder="Qty" :disabled="true" type="number" v-model="item.price"></el-input> -->
                            {{ item.price }}
                        </td>
                        <td style="text-align: left;">{{ item.price * item.order_qty | currency }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>Total</b></td>
                    <td><b>{{ invoice_total }}</b></td>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</template>

<script>
import {
    image
} from "./image";
export default {
    props: ['invoice_data'],
    data() {
        return {
            items: [{
                    description: "Website design",
                    quantity: 1,
                    price: 300
                },
                {
                    description: "Website design",
                    quantity: 1,
                    price: 75
                },
                {
                    description: "Website design",
                    quantity: 1,
                    price: 10
                }
            ],
            image: '',
            invoice_data: [],
            loading: false,
            activeName: 'first'
        }
    },
    computed: {
        total() {
            return this.items.reduce(
                (acc, item) => acc + item.price * item.quantity,
                0
            );
        },
        invoice_total() {
            var total = 0
            this.invoice_data.product.forEach(element => {
                total = parseFloat(total) + (parseFloat(element.price) * parseFloat(element.order_qty))
            });
            return total
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
        addRow() {
            this.items.push({
                description: "",
                quantity: 1,
                price: 0
            });
        },
        loadPage() {
            this.loading = true
            setTimeout(() => {
                this.loading = false
            }, 1000);
        },
    },
    filters: {
        currency(value) {
            return value.toFixed(2);
        },
    },
    created() {
        eventBus.$on('invoiceEvent', data => {
            // console.log(data);
            this.loading = true
            setTimeout(() => {
                this.loading = false
            }, 1000);
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
