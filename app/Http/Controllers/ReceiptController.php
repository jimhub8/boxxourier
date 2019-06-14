<?php

namespace App\Http\Controllers;

use App\Buyer;
use App\Invoice;
use App\Mail\InvoiceMail;
use App\Receipt;
use App\ReceiptProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReceiptController extends Controller {
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$this->validate($request, [
			'receipt_no' => 'required|alpha_dash|unique:receipts',
			'client' => 'required|max:255',
			'currency' => 'required',
			// 'client_address' => 'required|max:255',
			'receipt_date' => 'required|date_format:Y-m-d',
			'due_date' => 'required|date_format:Y-m-d',
			'title' => 'required|max:255',
			'discount' => 'required|numeric|min:0',
			'products.*.name' => 'required|max:255',
			'products.*.price' => 'required|numeric|min:1',
			'products.*.price' => 'required|numeric|min:1',
			'invoice_id' => 'required|min:1',
		]);

		$products = collect($request->products)->transform(function ($product) {
			$product['total'] = $product['qty'] * $product['price'];
			return new ReceiptProduct($product);
		});
		// var_dump($products);die;

		if ($products->isEmpty()) {
			return response()
			->json([
				'products_empty' => ['One or more Product is required.'],
			], 422);
		}

		$data = $request->except('products');
		$receipt_no = 'REC_' . $data['receipt_no'];
		$data['sub_total'] = $products->sum('total');
		$data['grand_total'] = $data['sub_total'] - $data['discount'];
		$data['receipt_no'] = $receipt_no;
		$data['invoice_id'] = $data['invoice_id'];

		;
		if ($receipt = Receipt::create($data)) {
			// debit the Client 
			$client = Buyer::where('client_id', $data['client'])->first();
		    $transaction_2 = $client->journal->debitDollars($data['grand_total']);
		    // return response()
		    // ->json($transaction_2);
		    // check our balance (should be 25)
		    // $current_balance = $user->journal->getCurrentBalanceInDollars();
		    
		    //get the product referenced in the journal (optional)
		    // $product_copy = $transaction_1->getReferencedObject()

			if ($receipt->products()->saveMany($products)) {
				$invoice = Invoice::where('invoice_no', $data['invoice_id'])->first();
				// $balance = $invoice->balance;
				// foreach ($invoice as $element) {
				// return $invoice;
				$balance = $invoice->balance;
				// }
				$paid = $invoice->paid;
				// return $value;
				$receipted = $data['grand_total'];
				$new_balance = $balance - $receipted;
				$invoice->balance = $new_balance;
				$invoice->paid = $paid + $data['grand_total'];
				// return $diff;
				$invoice->save();
			}
		} else {
			return response()
		          ->json('failed');
		}

		return response()
		->json($invoice);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Receipt  $receipt
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Receipt $receipt) {
		// return $request->all();
		$this->validate($request, [
			'receipt_no' => 'required|alpha_dash|unique:receipts,receipt_no,' . $id . ',id',
			'client' => 'required|max:255',
			// 'client_address' => 'required|max:255',
			'receipt_date' => 'required|date_format:Y-m-d',
			'due_date' => 'required|date_format:Y-m-d',
			'title' => 'required|max:255',
			'discount' => 'required|numeric|min:0',
			'products.*.name' => 'required|max:255',
			'products.*.price' => 'required|numeric|min:1',
			'products.*.qty' => 'required|integer|min:1',
		]);

		$receipt = Receipt::findOrFail($id);

		$products = collect($request->products)->transform(function ($product) {
			$product['total'] = $product['qty'] * $product['price'];
			return new ReceiptProduct($product);
		});

		if ($products->isEmpty()) {
			return response()
			->json([
				'products_empty' => ['One or more Product is required.'],
			], 422);
		}

		$data = $request->except('products');
		$data['sub_total'] = $products->sum('total');
		$data['grand_total'] = $data['sub_total'] - $data['discount'];

		$receipt->update($data);

		ReceiptProduct::where('receipt_id', $receipt->id)->delete();

		$receipt->products()->saveMany($products);

		return response()
		->json([
			'updated' => true,
			'id' => $receipt->id,
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Receipt  $receipt
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Receipt $receipt) {
		Receipt::find($receipt->id)->delete();
	}

	public function getReceipts() {
		return Receipt::with('products')->get();
	}

	public function getReceiptSort(Request $request) {
		return Receipt::whereBetween('created_at', [$request->start_date, $request->end_date])->get();
	}


	public function sendRecMail(Request $request)
	{
		return $request->all();
		$thisemail_to = Buyer::select('email')->where('client', $request->client)->get();
		return $thisemail_to;
		foreach ($thisemail_to as $email_tothis) {
			$email_to = $email_tothis->email;
		}
		// return $email_to;
		// $data = $request->all();
		// $invoice = Invoice::find(22);
		// return view('emails.InvoiceMail', compact('invoice'));
		// return $request->subject;
        // Mail::queue( new CampaignReady($subscriber, $request->title, $request->content));
		$invoice = Invoice::where('invoice_no', $request->invoice_no)->with('products')->first();
		Mail::queue( new InvoiceMail($invoice, $email_to, $request->subject));
	}
}
