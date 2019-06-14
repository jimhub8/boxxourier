<template>
  <v-layout row justify-center>
  
    <v-dialog v-model="openAddRequest" persistent max-width="1200px">
  
      <v-card>
  
        <v-card-title fixed>
  
          <span class="headline">Add Receipt</span>
  
        </v-card-title>
  
        <v-card-text>
  
          <v-container grid-list-md>
  
            <v-layout wrap>
  
              <v-form ref="form" @submit.prevent>
  
                <v-container grid-list-xl fluid>
  
                  <v-layout wrap>
  
                    <v-flex xs12 sm6>
  
                      <v-text-field v-model="form.title" color="blue darken-2" label="Title" required></v-text-field>
  
                      <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
  
                    </v-flex>
  
                    <v-flex xs12 sm6>
  
                      <v-text-field v-model="form.receipt_no" color="blue darken-2" label="Invoice Number" required></v-text-field>
  
                      <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
  
                    </v-flex>
  
                    <v-flex xs12 sm4>
  
                      <v-text-field v-model="form.due_date" color="blue darken-2" label="Due Date" type='date' required></v-text-field>
  
                      <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
  
                    </v-flex>
  
                    <v-flex xs12 sm4>
  
                      <v-text-field v-model="form.receipt_date" color="blue darken-2" label="Invoice Date" type='date' required></v-text-field>
  
                      <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
  
                    </v-flex>
  
                    <div class="form-group col-md-4">
  
                      <label for="password" class="col-md-4 col-form-label text-md-right">Select buyers</label>
  
                      <div class="col-md-6">
  
                        <select class="custom-select" v-model="form.client">
  
                              <option v-for="element in buyers" data-subtext="" :value="element.client_id">{{element.name}}</option>
  
                            </select>
  
                      </div>
  
                    </div>
  
                    <div class="form-group col-md-4">
  
                      <label for="password" class="col-md-12 col-form-label text-md-right">Select Incoice</label>
  
                      <div class="col-md-12">
  
                        <select class="custom-select" v-model="form.invoice_id">
  
                                <option v-for="element in invoices" data-subtext="" :value="element.invoice_no">{{element.invoice_no}}</option>
  
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
  
                        <tr v-for="product in form.products">
  
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
  
                            <input type="text" class="table-discount_input" v-model="form.discount">
  
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
  
                  <v-btn flat @click="resetForm">reset</v-btn>
  
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
  
    props: ['openAddRequest', 'buyers'],
  
    components: {},
  
    data() {
  
      const defaultForm = Object.freeze({
  
        receipt_no: '',
  
        receipt_date: '',
  
        due_date: '',
  
        client: '',
  
        discount: 0,
  
        client_address: '',
  
        title: '',
  
        products: [{
  
          name: '',
  
          price: 0,
  
          qty: 1
  
        }],
  
      })
  
      return {
  
        errors: {},
  
        invoices: {},
  
        defaultForm,
  
        society: {},
  
        loading: false,
  
        form: Object.assign({}, defaultForm),
  
        rules: {
  
          name: [val => (val || '').length > 0 || 'This field is required']
  
        },
  
      }
  
    },
  
    methods: {
  
      save() {
  
        this.loading = true
  
        axios.post('/receipt', this.$data.form).
  
        then((response) => {
  
            this.loading = false
  
            console.log(response);
  
            // this.close;
  
            // this.resetForm();
  
            // this.$emit('closeRequest');
  
            this.$emit('alertRequest');
  
            this.$parent.receipts.push(response.data)
  
  
  
          })
  
          .catch((error) => {
  
            this.loading = false
  
            this.errors = error.response.data.errors
  
          })
  
      },
  
      resetForm() {
  
        this.form = Object.assign({}, this.defaultForm)
  
        this.$refs.form.reset()
  
      },
  
      close() {
  
        this.$emit('closeRequest')
  
      },
  
  
  
  
  
      addLine: function() {
  
        this.form.products.push({
  
          name: '',
  
          price: 0,
  
          qty: 1
  
        });
  
      },
  
      remove: function(product) {
  
        const index = this.form.products.indexOf(product)
  
        this.form.products.splice(index, 1);
  
      },
  
  
  
    },
  
    computed: {
  
      formIsValid() {
  
        return (
  
          this.form.receipt_no &&
  
          this.form.receipt_date &&
  
          this.form.client &&
  
          this.form.due_date
  
        )
  
      },
  
  
  
      subTotal: function() {
  
        return this.form.products.reduce(function(carry, product) {
  
          return carry + (parseFloat(product.qty) * parseFloat(product.price));
  
        }, 0);
  
      },
  
      grandTotal: function() {
  
        return this.subTotal - parseFloat(this.form.discount);
  
      }
  
    },
  
    mounted() {
  
      axios.post('/getSociety')
  
        .then((response) => {
  
          this.society = response.data
  
        })
  
        .catch((error) => {
  
          this.errors = error.response.data.errors
  
        })
  
  
  
      axios.get('/getInvoice')
  
        .then((response) => {
  
          this.invoices = response.data
  
        })
  
        .catch((error) => {
  
          this.errors = error.response.data.errors
  
        })
  
    }
  
  }
</script>
