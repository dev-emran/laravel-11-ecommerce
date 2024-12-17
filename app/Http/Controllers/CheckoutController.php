<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    public function checkout()
    {

        if (Cart::instance('cart')->content()->count() == 0) {
            flash()->error('Your Cart is Empty!');
            return redirect()->route('shop.index');
        }
        $userId = Auth::user()->id;
        $address = Address::where('user_id', $userId)->where('isdefault', 1)->first();
        return view('checkout.checkout', compact('address'));

    }

    public function storeCheckout(Request $request)
    {
        $userId = Auth::user()->id;
        $address = $this->getDefaultAddress($userId);

        if ($address) {
            $address->delete();
        }

        $validatedAttr = $this->ValidateCheckout($request);
        $address = $this->createAddress($userId, $validatedAttr);

        $this->setAmountForCheckout();
        $order = $this->CreateOrder($userId, $address);

        foreach (Cart::instance('cart')->content() as $item) {
            OrderItem::create([
                'product_id' => $item->id,
                'order_id' => $order->id,
                'price' => $item->price,
                'quantity' => $item->qty,
            ]);
        }

        $validatedAttr = $request->validate([
            "payment_method" => 'required|in:cod,bkash,nagad',
        ]);

        $this->handlePayment($userId, $order->id, $validatedAttr['payment_method']);
        $this->clearSession();
        Session::put('order_id', $order->id);
        return redirect()->route('order.confirm');

    }

    private function setAmountForCheckout()
    {
        if (Cart::instance('cart')->content()->count() <= 0) {
            Session::forget('checkout');
            return;
        }

        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' => Session::get('discounts')['discount'],
                'subtotal' => Session::get('discounts')['subtotal'],
                'tax' => Session::get('discounts')['tax'],
                'total' => Session::get('discounts')['total'],
            ]);

        } else {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total(),
            ]);
        }

    }

    private function CreateOrder($userId, $address)
    {
        return Order::create([
            'user_id' => $userId,
            'subtotal' => str_replace(',', '', Session::get('checkout')['subtotal']),
            'discount' => str_replace(',', '', Session::get('checkout')['discount']),
            'tax' => str_replace(',', '', Session::get('checkout')['tax']),
            'total' => str_replace(',', '', Session::get('checkout')['total']),
            'name' => $address->name,
            'phone' => $address->phone,
            'locality' => $address->locality,
            'address' => $address->address,
            'city' => $address->city,
            'state' => $address->state,
            'country' => $address->country,
            'landmark' => $address->landmark,
            'zip' => $address->zip,
        ]);
    }

    private function handlePayment($userId, $orderId, $paymentMethod)
    {
        $status = "pending";
        if ($paymentMethod === 'cod') {
            Transaction::create([
                'user_id' => $userId,
                'order_id' => $orderId,
                'method' => $paymentMethod,
                'status' => $status,
            ]);
        }

        if ($paymentMethod === 'bkash') {
            Transaction::create([
                'user_id' => $userId,
                'order_id' => $orderId,
                'method' => $paymentMethod,
                'status' => $status,
            ]);
        }

        if ($paymentMethod === 'nagad') {
            Transaction::create([
                'user_id' => $userId,
                'order_id' => $orderId,
                'method' => $paymentMethod,
                'status' => $status,
            ]);
        }
    }

    private function clearSession()
    {
        Cart::instance('cart')->destroy();
        Session::forget('coupon');
        Session::forget('checkout');
        Session::forget('discounts');
    }

    private function getDefaultAddress($userId)
    {
        return Address::where('user_id', $userId)->where('isdefault', true)->first();
    }

    private function createAddress($userId, $validatedAttr)
    {
        return Address::create([
            'user_id' => $userId,
            "name" => $validatedAttr["name"],
            "phone" => $validatedAttr["phone"],
            "locality" => $validatedAttr["locality"],
            "address" => $validatedAttr["address"],
            "city" => $validatedAttr["city"],
            "state" => $validatedAttr["state"],
            'country' => 'Bangladesh',
            "landmark" => $validatedAttr["landmark"],
            "zip" => $validatedAttr["zip"],
            'type' => 'home',
            'isdefault' => true,
        ]);
    }

    private function ValidateCheckout(Request $request)
    {
        return $request->validate([
            "name" => 'required|string',
            "phone" => 'required|numeric|digits:11',
            "zip" => 'required',
            "state" => 'required',
            "city" => 'required',
            "address" => 'required',
            "locality" => 'required',
            "landmark" => 'required',
            "payment_method" => 'required|in:cod,bkash,nagad',
        ]);
    }
}
