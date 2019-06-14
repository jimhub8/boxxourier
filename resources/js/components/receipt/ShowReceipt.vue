<template>
	<v-layout row justify-center>
	
		<v-dialog v-model="openAddRequest" persistent max-width="900px">
	
			<v-card>
	
				<v-card-title fixed>
	
					<span class="headline">Receipt</span>
	
				</v-card-title>
	
				<v-card-text>
	
					<v-container grid-list-md>
	
						<v-layout wrap>
	
							<div class="panel panel-default col-md-12">
	
								<div class="panel-heading">
	
									<div class="clearfix">
	
										<span class="panel-title">Receipt</span>
	
										<!--<div class="pull-right">
	
							                 <a href="{{route('receipts.index')}}" class="btn btn-default">Back</a>
	
							                <a href="{{route('receipts.edit', $receipt)}}" class="btn btn-primary">Edit</a> 
	
							                <form class="form-inline" method="post"
	
							                    action="{{route('receipts.destroy', $receipt)}}"
	
							                    onsubmit="return confirm('Are you sure?')"
	
							                >
	
							                    <input type="hidden" name="_method" value="delete">
	
							                    <input type="hidden" name="_token" value="{{csrf_token()}}">
	
							                    <input type="submit" value="Delete" class="btn btn-danger">
	
							                </form> 
	
							            </div>-->
	
									</div>
	
								</div>
	
								<div class="panel-body">
	
									<div class="row">
	
										<div class="col-sm-4">
	
											<div class="form-group">
	
												<label>receipt No.</label>
	
												<p>{{receipt.receipt_no}}</p>
	
											</div>
	
											<div class="form-group">
	
												<label>Grand Total</label>
	
												<p>Ksh{{receipt.grand_total}}</p>
	
											</div>
	
										</div>
	
										<div class="col-sm-4">
	
											<div class="form-group">
	
												<label>Client</label>
	
												<p>{{receipt.client}}</p>
	
											</div>
	
											<div class="form-group">
	
												<label>Client Address</label>
	
												<pre class="pre">{{receipt.client_address}}</pre>
	
											</div>
	
										</div>
	
										<div class="col-sm-4">
	
											<div class="form-group">
	
												<label>Title</label>
	
												<p>{{receipt.title}}</p>
	
											</div>
	
											<div class="row">
	
												<div class="col-sm-6">
	
													<label>receipt Date</label>
	
													<p>{{receipt.receipt_date}}</p>
	
												</div>
	
												<div class="col-sm-6">
	
													<label>Due Date</label>
	
													<p>{{receipt.due_date}}</p>
	
												</div>
	
											</div>
	
										</div>
	
									</div>
	
									<hr>
	
									<table class="table table-bordered table-striped">
	
										<thead>
	
											<tr>
	
												<th>Product Name</th>
	
												<th>Price</th>
	
												<th>Qty</th>
	
												<th>Total</th>
	
											</tr>
	
										</thead>
	
										<tbody>
	
											<tr v-for="product in receipt.products">
	
												<td class="table-name">{{product.name}}</td>
	
												<td class="table-price">Ksh{{product.price}}</td>
	
												<td class="table-qty">{{product.qty}}</td>
	
												<td class="table-total text-right">Ksh{{product.qty * product.price}}</td>
	
											</tr>
	
										</tbody>
	
										<tfoot>
	
											<tr>
	
												<td class="table-empty" colspan="2"></td>
	
												<td class="table-label">Sub Total</td>
	
												<td class="table-amount">Ksh{{receipt.sub_total}}</td>
	
											</tr>
	
											<tr>
	
												<td class="table-empty" colspan="2"></td>
	
												<td class="table-label">Discount</td>
	
												<td class="table-amount">Ksh{{receipt.discount}}</td>
	
											</tr>
	
											<tr>
	
												<td class="table-empty" colspan="2"></td>
	
												<td class="table-label">Grand Total</td>
	
												<td class="table-amount">Ksh{{receipt.grand_total}}</td>
	
											</tr>
	
										</tfoot>
	
									</table>
	
								</div>
	
							</div>
	
						</v-layout>
	
					</v-container>
	
				</v-card-text>
	
				<v-card-actions>
	
					<v-btn @click="close" flat color="primary">Close</v-btn>
	
				</v-card-actions>
	
			</v-card>
	
		</v-dialog>
	
	</v-layout>
</template>

<script>
	export default {
	
		props: ['openAddRequest', 'receipt'],
	
		data() {
	
			return {
	
	
	
			}
	
		},
	
		methods: {
	
			close() {
	
				this.$emit('closeRequest')
	
			},
	
		},
	
	
	
		mounted() {},
	
	}
</script>