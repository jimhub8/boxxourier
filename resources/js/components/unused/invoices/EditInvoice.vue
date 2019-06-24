<template>
  <v-layout row justify-center>
  
    <v-dialog v-model="openAddRequest" persistent max-width="900px">
  
      <v-card>
  
        <v-card-title fixed>
  
          <span class="headline">Edit Invoice</span>
  
        </v-card-title>
  
        <v-card-text>
  
          <v-container grid-list-md v-if="openAddRequest">
  
            <v-layout wrap>
  
              <v-form ref="form" @submit.prevent>
  
                <v-container grid-list-xl fluid>
  
                  <v-layout wrap>
  
                    <v-flex xs12 sm6>
  
                      <v-text-field v-model="invoiceData.invoice_no" color="blue darken-2" label="Invoice Number" required></v-text-field>
  
                      <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
  
                    </v-flex>
  
                    <v-flex xs12 sm6>
  
                      <v-text-field v-model="invoiceData.due_date" color="blue darken-2" label="Due Date" type='date' required></v-text-field>
  
                      <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
  
                    </v-flex>
  
                    <v-flex xs12 sm6>
  
                      <v-text-field v-model="invoiceData.invoice_date" color="blue darken-2" label="Invoice Date" type='date' required></v-text-field>
  
                      <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
  
                    </v-flex>
  
                    <v-flex xs12 sm6>
  
                      <v-text-field v-model="invoiceData.grand_total" color="blue darken-2" label="Grand Total" required></v-text-field>
  
                      <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
  
                    </v-flex>
  
                    <div class="form-group col-md-6">
  
                      <label for="password" class="col-md-4 col-form-label text-md-right">Select buyers</label>
  
                      <div class="col-md-6">
  
                        <select class="custom-select" v-model="invoiceData.client_id">
  
                              <option v-for="element in buyers" data-subtext="" :value="element.client_id">{{element.name}}</option>
  
                            </select>
  
                      </div>
  
                    </div>
  
                    <table class="table table-bordered table-form">
  
                      <thead>
  
                        <tr>
  
                          <th>Product Name</th>
  
                          <th>Price</th>
  
                          <th>Qty</th>
  
                          <th>Total</th>
  
                        </tr>
  
                      </thead>
  
                      <tbody>
  
                        <tr v-for="product in invoiceData.products">
  
                          <td class="table-name">
  
                            <textarea class="table-control" v-model="product.name"></textarea>
  
                          </td>
  
                          <td class="table-price">
  
                            <input type="text" class="table-control" v-model="product.price">
  
                          </td>
  
                          <td class="table-qty">
  
                            <input type="text" class="table-control" v-model="product.qty">
  
                          </td>
  
                          <td class="table-total">
  
                            <span class="table-text">@{{product.qty * product.price}}</span>
  
                          </td>
  
                          <td class="table-remove">
  
                            <span @click="remove(product)" class="table-remove-btn">&times;</span>
  
                          </td>
  
                        </tr>
  
                      </tbody>
  
                      <tfoot>
  
                        <tr>
  
                          <td class="table-empty" colspan="2">
  
                            <span @click="addLine" class="table-add_line">Add Line</span>
  
                          </td>
  
                          <td class="table-label">Sub Total</td>
  
                          <td class="table-amount">@{{subTotal}}</td>
  
                        </tr>
  
                        <tr>
  
                          <td class="table-empty" colspan="2"></td>
  
                          <td class="table-label">Discount</td>
  
                          <td class="table-discount">
  
                            <input type="text" class="table-discount_input" v-model="invoiceData.discount">
  
                          </td>
  
                        </tr>
  
                        <tr>
  
                          <td class="table-empty" colspan="2"></td>
  
                          <td class="table-label">Grand Total</td>
  
                          <td class="table-amount">@{{grandTotal}}</td>
  
                        </tr>
  
                      </tfoot>
  
                    </table>
  
                  </v-layout>
  
                </v-container>
  
                <v-card-actions>
  
                  <!-- <v-btn flat @click="resetForm">reset</v-btn> -->
  
                  <v-btn flat @click="close">Close</v-btn>
  
                  <v-spacer></v-spacer>
  
                  <v-btn :disabled="loading" :loading="loading" flat color="primary" @click="save">Submit</v-btn>
  
                </v-card-actions>
  
              </v-form>
  
            </v-layout>
  
          </v-container>
  
        </v-card-text>
  
      </v-card>
  
    </v-dialog>
  
  </v-layout>
</template>

<script>
  export default {
  
    props: ['openAddRequest', 'buyers', 'invoiceData'],
  
    components: {},
  
    data() {
  
      /*const defaultForm = Object.freeze({
  
        grand_total: '',
  
        invoice_no: '',
  
        invoice_date: '',
  
        due_date: '',
  
        client: '',
  
      })*/
  
      return {
  
        errors: {},
  
        // defaultForm,
  
        list: {},
  
        loading: false,
  
        rules: {
  
          name: [val => (val || '').length > 0 || 'This field is required']
  
        },
  
      }
  
    },
  
    methods: {
  
      save() {
  
        this.loading = true
  
        axios.patch(`/invoice/${this.invoiceData.id}`, this.invoiceData).
  
        then((response) => {
  
            this.loading = false
  
            console.log(response.data);
  
            // this.close;
  
            // this.$emit('closeRequest');
  
            this.$emit('alertRequest');
  
            Object.assign(this.$parent.invoices[this.$parent.editedIndex], this.$parent.editedItem)
  
          })
  
          .catch((error) => {
  
            this.loading = false
  
            this.errors = error.response.data.errors
  
          })
  
      },
  
      addLine: function() {
  
        this.form.products.push({
  
          name: '',
  
          price: 0,
  
          qty: 1
  
        });
  
      },
  
      remove: function(product) {
  
        // this.form.products.$remove(product);
  
        const index = this.invoiceData.products.indexOf(product)
  
        this.receiptData.products.splice(index, 1);
  
      },
  
      /*resetForm () {
  
        this.form = Object.assign({}, this.defaultForm)
  
        this.$refs.form.reset()
  
      },*/
  
      close() {
  
        this.$emit('closeRequest')
  
      },
  
  
  
    },
  
  
  
    computed: {
  
      subTotal: function() {
  
        return this.invoiceData.products.reduce(function(carry, product) {
  
          return carry + (parseFloat(product.qty) * parseFloat(product.price));
  
        }, 0);
  
      },
  
      /*subTotal: function() {
  
        return this.invoiceData.products.reduce(function(carry, product) {
  
          return carry + (parseFloat(product.qty) * parseFloat(product.price));
  
        }, 0);
  
      },*/
  
      grandTotal: function() {
  
        return this.subTotal - parseFloat(this.invoiceData.discount);
  
      }
  
    },
  
  }
</script>
