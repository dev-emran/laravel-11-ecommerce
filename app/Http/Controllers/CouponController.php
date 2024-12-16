<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Carbon;

class CouponController extends Controller
{
    public function coupons()
    {
        $coupons = Coupon::orderBy('expire_date', 'DESC')->paginate(12);
        return view('coupon.coupon', compact('coupons'));
    }

    public function createCoupon()
    {
        return view('coupon.create');
    }

    public function storeCoupon(Request $request)
    {
        $validatedAttr = $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required',
            'cart_value' => 'required',
            'expire_date' => 'required',
        ]);

        Coupon::create($validatedAttr);
        flash()->success('Coupon has been added successfull');
        return redirect()->route('coupon.index');
    }

    public function editCoupon($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            return view('coupon.edit', compact('coupon'));
        } catch (ModelNotFoundException $e) {
            flash()->error('Somethings went wrong');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $validatedAttr = $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required',
            'cart_value' => 'required',
            'expire_date' => 'required',
            'c_id' => 'required|numeric',
        ]);

        try {
            $coupon = Coupon::findOrFail($validatedAttr['c_id']);
            if ($coupon) {
                $coupon->code = $validatedAttr['code'];
                $coupon->type = $validatedAttr['type'];
                $coupon->value = $validatedAttr['value'];
                $coupon->cart_value = $validatedAttr['cart_value'];
                $coupon->expire_date = $validatedAttr['expire_date'];
                $coupon->save();
            }
            flash()->success('Coupon has been successfully Updated');
            return redirect()->route('coupon.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Somethings went wrong');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        $validatedAttr = $request->validate([
            'c_id' => 'required|numeric',
        ]);

        try {
            $coupon = Coupon::findOrFail($validatedAttr['c_id']);
            if ($coupon) {
                $coupon->delete();
            }
            flash()->success('Coupon has been successfully deleted');
            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            flash()->error('Somethings went wrong');
            return redirect()->back();
        }
    }

    public function applyCoupon(Request $request)
    {
        $validatedAttr = $request->validate([
            'coupon_code' => 'required',
        ]);

        $coupon = Coupon::where('code', $validatedAttr['coupon_code'])
            ->where('expire_date', '>=', Carbon::today())
            ->where('cart_value', '>=', Cart::instance('cart')->subtotal())
            ->first();
        if (!$coupon) {
            flash()->error('Invalid coupon code!');
            return redirect()->back();
        }

        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value,
        ]);

        $this->calculateDiscount();
        flash()->success('Coupon has been successfully applied');
        return redirect()->route('cart.index');

    }

    public function removeCoupon()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        flash()->success('Coupon offer Successfully removed');
        return redirect()->back();
    }

    protected function calculateDiscount()
    {
        $discount = 0;
        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');
            $_subTotal = Cart::instance('cart')->subtotal();
            $subTotal = floatval(str_replace(',', '', $_subTotal));

            if ($coupon['type'] === 'fixed') {
                $discount = $coupon['value'];

            } elseif ($coupon['type'] === 'percent') {
                $discount = ($coupon['value'] / 100) * $subTotal;

            }
            $subtotalAfterDiscount = $subTotal - $discount;
            $taxRate = config('cart.tax', 0);
            $taxAfterDiscount = ($subtotalAfterDiscount * $taxRate) / 100;
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

            Session::put('discounts', [
                'discount' => number_format($discount, 2, '.', ','),
                'subtotal' => number_format($subtotalAfterDiscount, 2, '.', ','),
                'tax' => number_format($taxAfterDiscount, 2, '.', ','),
                'total' => number_format($totalAfterDiscount, 2, '.', ','),
            ]);
        }
    }
}
