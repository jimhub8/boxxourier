<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getProducts() {
		$results = Product::where('branch_id', Auth::user()->branch_id)->get();
		return json_decode(json_encode($results), true);
	}

	public function productAdd(Request $request, Product $product, $id) {
		// return $request->all();
		$product = new Product;
		$product->product_name = $request->product_name;
		$product->weight = $request->weight;
		$product->price = $request->price;
		$product->total = $request->total;
		$product->quantity = $request->quantity;
		$product->user_id = Auth::id();
		$product->branch_id = Auth::user()->branch_id;
		$product->branch_id = Auth::user()->branch_id;
		$product->shipments_id = $id;
		$product->save();
		return $product;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		return $request->all();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Product $product) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Product $product) {
		//
	}
}
