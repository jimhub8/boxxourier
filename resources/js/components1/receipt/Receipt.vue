<template>
	<div>

		<v-content>

			<v-container fluid fill-height>

				<div v-show="loader" style="text-align: center; width: 100%; margin-top: 200px;">

					<v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>

				</div>





				<v-layout justify-center align-center v-show="!loader">

					<!-- <v-flex sm12>

						<v-btn @click="receiptAdd" flat color="primary">Add receipt</v-btn>

					</v-flex> -->

					<div class="row">

						<!-- <div class="col-md-12">

						<v-btn @click="receiptAdd" flat color="primary">Add Receipt</v-btn>

						<v-flex xs12 sm4>

						    <v-text-field

						    v-model="form.start_date"

						    color="blue darken-2"

						    type="date"

						    required

						    ></v-text-field>

						</v-flex>

						<v-flex xs12 sm4 offset-sm1>

						    <v-text-field

						    v-model="form.end_date"

						    color="blue darken-2"

						    type="date"

						    required

						    ></v-text-field>

						</v-flex>

						<v-flex sm3>

						   <v-btn color="primary" flat @click="sort">Sort</v-btn>

						</v-flex>

					</div> -->

						<div class="col-md-12">

							<v-btn @click="receiptAdd" flat color="primary">Add Receipt</v-btn>

							<!-- <v-flex xs12 sm4> -->

							<div class="row">

								<div class="col-md-4">

									<v-text-field v-model="form.start_date" color="blue darken-2" type="date" required></v-text-field>

								</div>

								<!-- </v-flex> -->

								<!-- <v-flex xs12 sm4 offset-sm1> -->

								<div class="col-md-4">

									<v-text-field v-model="form.end_date" color="blue darken-2" type="date" required></v-text-field>

								</div>

								<!-- </v-flex> -->

								<!-- <v-flex sm3> -->

								<div class="col-md-4">

									<v-btn color="primary" flat @click="sort">Sort</v-btn>

								</div>

							</div>

							<!-- </v-flex> -->

						</div>



						<!-- <v-flex sm12> -->

						<div class="col-md-12">

							<table class="table table-hover table-striped table-responsive">

								<thead>

									<tr>

										<th scope="col">#</th>

										<th scope="col">Receipt Number</th>

										<th scope="col">Grand Total</th>

										<th scope="col">Client</th>

										<!-- <th scope="col">Due Date</th> -->

										<!-- <th scope="col">Created On</th> -->

										<th scope="col">Receipt Date</th>

										<th scope="col">Action</th>

									</tr>

								</thead>

								<tbody>

									<tr v-for="receipt, key in receipts" :key="receipt.id" v-if="receipt.created_at >= form.start_date || receipt.created_at >= form.end_date">

										<th scope="row">{{key+1}}</th>

										<td>{{receipt.receipt_no}}</td>

										<td>{{receipt.grand_total}}</td>

										<td v-for="buyers in AllBuyers" v-if="receipt.client = buyers.id">{{buyers.name}}</td>

										<!-- <td>{{receipt.due_date}}</td> -->

										<!-- <td>{{receipt.created_at}}</td> -->

										<td>{{receipt.receipt_date}}</td>

										<td>

											<v-btn @click="receiptEdit(receipt)" flat color="primary">Edit</v-btn>

											<v-btn @click="receiptShow(receipt)" flat color="info">View</v-btn>

											<v-btn @click="invoiceMail(receipt)" flat color="info">Mail</v-btn>

											<!-- <v-btn @click="receiptdel(key, receipt.id)" flat color="danger">Delete</v-btn> -->

										</td>

									</tr>

								</tbody>

							</table>

						</div>

					</div>

					<!-- </v-flex> -->

				</v-layout>

			</v-container>

			<v-snackbar :timeout="timeout" bottom :color="color" left v-model="snackbar">

				{{ message }}

				<!-- <v-icon dark right>check_circle</v-icon> -->

				<v-btn>close</v-btn>

			</v-snackbar>

		</v-content>

		<AddReceipt @closeRequest="close" :openAddRequest="dispAdd" @alertRequest="showAlert" :buyers="AllBuyers"></AddReceipt>

		<EditReceipt @closeRequest="close" :openAddRequest="dispEdit" @alertRequest="showAlert" :buyers="AllBuyers" :receiptData="editReceipt"></EditReceipt>

		<ShowReceipt @closeRequest="close" :openAddRequest="dispShow" @alertRequest="showAlert" :receipt="editReceipt"></ShowReceipt>

		<MailInvoice @closeRequest="close" :openMailRequest="dispMail" @alertRequest="showAlert" :invoice="editReceipt"></MailInvoice>

	</div>
</template>

<script>
	import AddReceipt from './AddReceipt';

	import EditReceipt from './EditReceipt';

	import ShowReceipt from './ShowReceipt';

	import MailInvoice from './EMail';

	export default {

		components: {

			AddReceipt,

			EditReceipt,

			ShowReceipt,

			MailInvoice

		},

		data() {

			return {

				dispAdd: false,

				dispEdit: false,

				dispShow: false,

				dispMail: false,

				loader: false,

				snackbar: false,

				timeout: 5000,

				color: '',

				message: '',

				Allusers: [],

				receipts: {},

				AllBuyers: {},

				editReceipt: {},

				form: {

					start_date: '',

					end_date: ''

				}

			}

		},

		methods: {

			sort() {

				this.loader = true

				axios.post('/getInvoiceSort', this.form)

					.then((response) => {

						this.loader = false

						this.receipts = response.data

					})

					.catch((error) => {

						this.loader = false

						this.errors = error.response.data.errors

					})

			},

			receiptEdit(receipt) {

				// console.log(receipt);

				this.editReceipt = Object.assign({}, receipt)

				this.editedIndex = this.receipts.indexOf(receipt)

				// console.log(this.editedItem);

				this.dispEdit = true

			},

			receiptAdd() {

				this.dispAdd = true

			},



			invoiceMail(receipt) {

				this.editReceipt = Object.assign({}, receipt)

				this.editedIndex = this.receipts.indexOf(receipt)

				// console.log(this.editedItem);

				this.dispMail = true

				// this.$children[3].list = this.invoices[key]

			},

			/*receiptEdit(key){

	      	this.$children[2].list = this.receipts[key]

				this.dispEdit  = true

			},*/

			receiptShow(receipt) {

				this.editReceipt = Object.assign({}, receipt)

				this.editedIndex = this.receipts.indexOf(receipt)

				// console.log(this.editedItem);

				this.dispShow = true

				// this.$children[3].list = this.receipts[key]

				this.dispShow = true

			},

			editItem(item) {

				this.editedItem = Object.assign({}, item)

				this.editedIndex = this.Allusers.indexOf(item)

				// console.log(this.editedItem);

				this.pdialog2 = true

			},



			showAlert() {

				this.message = 'Successifully Added';

				this.snackbar = true;

				this.color = 'indigo';

			},



			receiptdel(key, id) {

				if (confirm('Are you sure you want to delete this item?')) {

					this.loader = true

					axios.delete(`/users/${id}`)

						.then((response) => {

							this.Allusers.splice(index, 1)

							this.loader = false

							this.message = 'deleted successifully'

							this.color = 'red'

							this.snackbar = true

						})

						.catch((error) => {

							this.errors = error.response.data.errors

							this.loader = false

							this.message = 'something went wrong'

							this.color = 'red'

							this.snackbar = true

						})

				}

			},

			close() {

				this.dispAdd = this.dispShow = this.dispEdit = false

			},

		},



		computed: {

			Start_dates() {

				return this.form.start_date;

			},

			end_dates() {

				return this.form.end_date;

			}

		},





		mounted() {

			this.loader = true

			axios.get('/getUsers')

				.then((response) => {

					this.Allusers = response.data

				})

				.catch((error) => {

					this.errors = error.response.data.errors

				})



			axios.post('/getBuyers')

				.then((response) => {

					this.AllBuyers = response.data

				})

				.catch((error) => {

					this.errors = error.response.data.errors

				})



			axios.post('/getReceipts')

				.then((response) => {

					this.loader = false

					this.receipts = response.data

				})

				.catch((error) => {

					this.loader = false

					this.errors = error.response.data.errors

				})

		},

	}
</script>
