<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart.index', compact('items'));
    }

    public function addToCart(Request $request)
    {
        $validatedAttr = $request->validate([
            'id' => "required|numeric",
            'title' => 'string|required|min:3',
            'sale_price' => 'required',
            'quantity' => 'numeric|required|min:1|max:1',
        ]);
        Cart::instance('cart')->add($validatedAttr['id'], $validatedAttr['title'], $validatedAttr['quantity'], $validatedAttr['sale_price'])->associate(Product::class);

        Session::flash('coupon');
        Session::flash('discounts');
        return redirect()->back();

    }

    public function qtyIncrease($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        Session::forget('coupon');
        Session::forget('discounts');
        return redirect()->back();
    }
 
    public function qtyDecrease($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $product->qty -= 1;
        Cart::instance('cart')->update($rowId, $product->qty);
        Session::forget('coupon');
        Session::forget('discounts');
        return redirect()->back();
    }

    public function removeCartItem($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

}
