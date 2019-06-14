<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\Payment;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\models\Cart;
use App\Order;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderNotification;
use App\models\Product;

class PaymentController extends Controller
{
    public function __construct()
    {

    }

    public function create(Request $request)
    {
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AdqfQ_xn0HyksbTB2lY_BZSTfgIxnsVZ2O6ZhItIt_S3el1B23iWyOagUFS4Ikrd4Maj-GYDGJvKYRju',     // ClientID
                'EOCGee6_vyg1J6n1S-fKPykBnfEL_IHP_yDa2DMyfCwpmSmoqUOgqSwl2tiT6YRyy-CvLENIVS0N4nB-'      // ClientSecret
            )
        );
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        // $item1 = new Item();
        // $item1->setName('Ground Coffee 40 oz')
        //     ->setCurrency('USD')
        //     ->setQuantity(1)
        //     ->setSku("123123") // Similar to `item_number` in Classic API
        //     ->setPrice(7.5);
        // $item2 = new Item();
        // $item2->setName('Granola bars')
        //     ->setCurrency('USD')
        //     ->setQuantity(5)
        //     ->setSku("321321") // Similar to `item_number` in Classic API
        //     ->setPrice(2);
        $items = [];
        $total = 0;
        $tax = 0;
        $shipment = 0;
        $qty = 0;
        $price = 0;
        foreach ($this->getCart() as $cart) {
            $name = $cart['item']['name'];
            $total = $total + ($cart['item']['price'] * $cart['qty']);
            $item = new Item();
            // dd($cart['item']['price']);
            // dd($cart['qty']);
            $qty = $cart['qty'];
            $price = $cart['item']['price'];
            $items[] = $item->setName($name)
                ->setCurrency('USD')
                ->setQuantity($qty)
                ->setSku("123123") // Similar to `item_number` in Classic API
                ->setPrice($price);


        }
        // dd($qty);
        // dd($price);

        $tax = $total * 16 / 100;
        $shipment = $total * 1 / 100;
        $subtotal = $total - $tax - $shipment;
        // dd($total + $tax + $shipment);

        $itemList = new ItemList();
        $itemList->setItems($items);
        // $itemList->setItems(array($item1, $item2));
        $details = new Details();
        $details->setShipping($shipment)
            ->setTax($tax)
            ->setSubtotal($total);
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($total + $tax + $shipment)
            ->setDetails($details);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());
        // $baseUrl = getBaseUrl();
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("http://ecommerce.test/execute-payment")
            ->setCancelUrl("http://ecommerce.test/paypal");
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        // $request = clone $payment;
        $payment->create($apiContext);
        return redirect($payment->getApprovalLink());
        // return $payment;
    }

    public function execute(Request $request)
    {
        // After Step 1
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AdqfQ_xn0HyksbTB2lY_BZSTfgIxnsVZ2O6ZhItIt_S3el1B23iWyOagUFS4Ikrd4Maj-GYDGJvKYRju',     // ClientID
                'EOCGee6_vyg1J6n1S-fKPykBnfEL_IHP_yDa2DMyfCwpmSmoqUOgqSwl2tiT6YRyy-CvLENIVS0N4nB-'      // ClientSecret
            )
        );

        $total = 0;
        $tax = 0;
        $shipment = 0;
        foreach ($this->getCart() as $cart) {
            $total = $total + ($cart['item']['price'] * $cart['qty']);
        }
        $tax = $total * 16 / 100;
        $shipment = $total * 1 / 100;
        $subtotal = $total - $tax - $shipment;

        $paymentId = $request->paymentId;
        // dd($paymentId);
        $payment = Payment::get($paymentId, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);
        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();

        $details->setShipping($shipment)
            ->setTax($tax)
            ->setSubtotal($total);

        $amount->setCurrency('USD');
        $amount->setTotal($total + $tax + $shipment);
        $amount->setDetails($details);
        $transaction->setAmount($amount);
        $execution->addTransaction($transaction);
        $result = $payment->execute($execution, $apiContext);

        // Create Orders

        $order = new Order;
        $order->buyer_id = Auth::id();
        $order->payment_id = $payment->id;
        $order->address = 'Nairobi';
        $order->status = 'Payed';
        $order->amount = $amount->total;
        $order->name = Auth::user()->name;
        $order->cart = serialize($this->getCart());
        $order->paypal = serialize($payment);
        // $orderSave = Auth::user()->orders()->save();
        $order->save();
        $cart = $this->getCart();
        $this->getCartProduct();
        if ($order->save()) {
            $request->session()->forget('cart');
        }
        $user = Auth::user();
		Notification::send($user, new OrderNotification($order, $cart));
        return redirect('/#/thankyou');
        // return $order;
        // return $payment;  
        // return $request('paymentId');
    }

    public function getCart()
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $carts = new Cart($oldCart);
        $carts = $carts->getCart($oldCart);
        $newCart = [];
        // foreach ($carts as $cart) {
        //     $newCart = $cart->item;
        // }
        return $carts;
    }
    
    public function getCartProduct()
    {
        $cart = $this->getCart();
        foreach ($cart as $product) {
            $cart_id = $product['item']['id'];
            $cart_qty = $product['qty'];
            $product_s = Product::find($cart_id);
            $new_qty = $product_s->quantity - $cart_qty;
            // dd($product_s->quantity .' - '. $cart_qty . ' = '. $new_qty);
            $product = Product::where('id', $cart_id)->update(['quantity' => $new_qty]);
        }
        return $product;
    }
}
