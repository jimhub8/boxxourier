<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="1400px">
        <v-card v-if="dialog">
            <v-card-title fixed>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>

            <div class="card">
                <div class="card-header">
                    <ul class="list-group text-center">
                        <li class="list-group-item active">{{ order.buyer_id }}'s' Orders</li>
                    </ul>
                </div>
                <div class="card-body">
                    <!-- <li class="list-group-item">
                        <span class="badge" style="float: right; color: #fff; background: #000;">{{ cart.item.price }}</span>
                        {{ cart.item.name }} | {{ cart.qty }}
                    </li> -->

                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product</th>
                                <!-- <th scope="col">Payment Id</th> -->
                                <th scope="col">Description</th>
                                <th scope="col">Price</th>
                                <th scope="col">List Price</th>
                                <th scope="col">Order Date</th>
                                <!-- <th scope="col">Status</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(cart, index) in order.cart" :key="index">
                                <th scope="row">{{ index + 1 }}</th>
                                <td>{{ cart.item.name }}</td>
                                <!-- <td>{{ cart.payment_id }}</td> -->
                                <td>{{ cart.item.description }}</td>
                                <td>{{ cart.item.price }}</td>
                                <td>{{ cart.item.list_price }}</td>
                                <td>{{ cart.item.created_at }}</td>
                                <!-- <td>{{ cart.status }}</td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <!-- <b>Tax:
                        <span class="badge" style="float: right; color: #fff; background: #f00;">Ksh{{ getSubTotal }}</span>
                    </b> -->
                    <strong>Subtotal:
                        <span class="badge" style="float: right; color: #fff; background: #f00;">Ksh{{ getSubTotal }}</span>
                    </strong>
                </div>
            </div>
            <v-card-actions>
                <v-btn flat @click="close">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    data() {
        return {
            dialog: false,
            carts: [],
            order: [],
            totalPrice: '',
            discount: 0,
        };
    },
    methods: {
        close() {
            // eventBus.$emit("closeRequest", product);
            this.dialog = false;
        }
    },
    created() {
        eventBus.$on("viewOrdEvent", data => {
            this.order = data;
            this.carts = data.cart;
            this.dialog = true;
        });
    },

    computed: {
        getSubTotal() {
            if (this.carts.length > 0) {
                this.totalPrice = 0;
                for (let index = 0; index < this.carts.length; index++) {
                    const element = this.carts[index];
                    this.totalPrice = parseInt(element.price) + this.totalPrice;
                }
            }
            return this.totalPrice;
        },
        getTotal() {
            if (this.carts.length > 0) {
                return parseInt(this.getSubTotal) - this.discount;
            }
        }
    },
};
</script>

<style scoped>
.badge,
.badge-danger,
.badge-dark,
.badge-default,
.badge-info,
.badge-primary,
.badge-secondary,
.badge-success,
.badge-warning {
    font-size: 12px !important;
}
</style>
