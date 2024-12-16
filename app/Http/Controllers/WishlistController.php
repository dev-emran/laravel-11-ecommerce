<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Cart::instance('wishlist')->content();
        // return $wishlistItems;
        return view('wishlist.wishlist', compact('wishlistItems'));
    }

    public function addToWishlist(Request $request)
    {
        Cart::instance('wishlist')->add($request->id, $request->title, $request->quantity, $request->sale_price)->associate(Product::class);
        return redirect()->back();
    }

    public function moveToCart($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);
        Cart::instance('cart')->add($item->id, $item->name, $item->qty, $item->price)->associate(Product::class);
        Cart::instance('wishlist')->remove($rowId);
        return redirect()->back();
    }


    public function removeFromWhislist($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);
        return redirect()->back();
    }
}
