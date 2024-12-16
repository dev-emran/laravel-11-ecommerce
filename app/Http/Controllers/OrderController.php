<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function orderConfirmation()
    {
        if (Session::has('order_id')) {

            $order = Order::find(Session::get('order_id'));
            return view('orderconfirm', compact('order'));
        }

        flash()->error('Somethings went wrong');
        return redirect()->route('cart.index');
    }

    public function ordersAdminPanel()
    {
        $orders = Order::orderBy('created_at', 'DESC')->paginate(12);
        return view('admin.orders.order', compact('orders'));
    }

    public function orderdItemsAdminPanel($orderId)
    {
        $order = Order::where('id', $orderId)->first();
        // $transaction = Order::with('transaction')->where('id', $orderId)->first();
        $orderedItems = OrderItem::select(['id', 'order_id', 'product_id', 'quantity',  'options', 'rstatus'])
                        ->with([
                            'product'   => function($query){
                                $query->select(['id','title', 'slug', 'image', 'sale_price', 'sku', 'brand_id', 'category_id']);
                            },
                            'product.brand' => function($query){
                                $query->select(['id','name']);
                            },
                            'product.category' => function($query){
                                $query->select(['id','name']);
                            }
                        ])
                        ->where('order_id', $orderId)
                        ->paginate(12);

        // $orderedItems = OrderItem::with(['product', 'product.brand', 'product.category'])->where('order_id', $orderId)->select()->get();
        return view('admin.orders.order-details', compact('orderedItems', 'order'));

    }

    public function updateAdminOrderStatus(Request $request)
    {
        $orderId = $request->order_id;
        $orderStatus = $request->order_status;
        $order = Order::find($orderId);
        $txn = Transaction::where('order_id', $orderId)->first();

        if(!$order){
            flash('Somethings went wrong!');
            return redirect()->back();
        }
        if($orderStatus === 'delivered'){
            $order->status = 'delivered';
            $order->delivered_date = Carbon::now();
            $order->canceled_date = null;
            $txn->status = 'approved';
        }

        if($orderStatus === 'canceled'){
            $order->status = 'canceled';
            $order->canceled_date = Carbon::now();
            $txn->status = 'declined';
            $order->delivered_date = null;
        }

        if($orderStatus === 'ordered'){
            $order->status = 'ordered';
            $order->canceled_date = null;
            $order->delivered_date = null;
            $txn->status = 'pending';
        }

        $order->save();
        $txn->save();
        flash()->success('Order Status updated');
        return redirect()->back();

    }

 
    public function userOrders()
    {
        $userId = Auth::user()->id;
        $orders = Order::where('user_id', $userId)->orderBy('created_at', 'DESC')->paginate(12);
        return view('user.orders', compact('orders'));
    }

    public function userOrderDetails($orderId)
    {
        $userId = Auth::user()->id;
        $order = Order::where('id', $orderId)->where('user_id', $userId)->first();
        if(!$order){
            flash('Somethings went wrong!');
            return redirect()->route('user.index');
        }
        return view('user.order-detail', compact( 'order'));
    }

    public function userCancelOrder(Request $request)
    {
        $orderId = $request->order_id;
        $userId = Auth::user()->id;
        $order = Order::where('id', $orderId)->where('user_id', $userId)->first();
        $txn = Transaction::where('order_id', $orderId)->where('user_id', $userId)->first();
        if(!$order && !$txn ){
            flash('Access denieid');
            return redirect()->back();
        }
        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();

        $txn->status = 'declined';
        $txn->save();

        flash()->success('Order Canceled');
        return redirect()->back();
    }
}
