@extends('layouts.app')
@section('content')
    <style>
        .pt-90 {
            padding-top: 90px !important;
        }

        .pr-6px {
            padding-right: 6px;
            text-transform: uppercase;
        }

        .my-account .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 40px;
            border-bottom: 1px solid;
            padding-bottom: 13px;
        }

        .my-account .wg-box {
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            padding: 24px;
            flex-direction: column;
            gap: 24px;
            border-radius: 12px;
            background: var(--White);
            box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
        }

        .bg-success {
            background-color: #40c710 !important;
        }

        .bg-danger {
            background-color: #f44032 !important;
        }

        .bg-warning {
            background-color: #f5d700 !important;
            color: #000;
        }

        .table-transaction>tbody>tr:nth-of-type(odd) {
            --bs-table-accent-bg: #fff !important;

        }

        .table-transaction th,
        .table-transaction td {
            padding: 0.625rem 1.5rem .25rem !important;
            color: #000 !important;
        }

        .table> :not(caption)>tr>th {
            padding: 0.625rem 1.5rem .25rem !important;
            background-color: #6a6e51 !important;
        }

        .table-bordered>:not(caption)>*>* {
            border-width: inherit;
            line-height: 32px;
            font-size: 14px;
            border: 1px solid #e1e1e1;
            vertical-align: middle;
        }

        .table-striped .image {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            border-radius: 10px;
            overflow: hidden;
        }

        .table-striped td:nth-child(1) {
            min-width: 250px;
            padding-bottom: 7px;
        }

        .pname {
            display: flex;
            gap: 13px;
        }

        .table-bordered> :not(caption)>tr>th,
        .table-bordered> :not(caption)>tr>td {
            border-width: 1px 1px;
            border-color: #6a6e51;
        }
    </style>
    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Order's Details</h2>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-10">
                    <div class="wg-box mt-5 mb-5">
                        <div class="row">
                            <div class="col-6">
                                <h5>Ordered Details</h5>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-sm btn-danger" href="{{ route('user.orders') }}">Back</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-transaction">
                                <tbody>
                                    <tr>
                                        <th>Order No</th>
                                        <td> 000{{ $order->id }}</td>
                                        <th>Mobile</th>
                                        <td>{{ $order->phone }}</td>
                                        <th>Pin/Zip Code</th>
                                        <td>{{ $order->zip }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order Date</th>
                                        <td>{{ $order->created_at->format('Y-M-d || h:i A') }}</td>
                                        <th>Delivered Date</th>
                                        <td>{{ $order->delivered_date ? $order->delivered_date : 'N/A' }}
                                        </td>
                                        <th>Canceled Date</th>
                                        <td>{{ $order->canceled_date ? $order->canceled_date : 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Order Status</th>
                                        <td colspan="5">
                                            @if ($order->status === 'ordered')
                                                <span class="badge bg-success text-primary">{{ $order->status }}</span>
                                            @elseif ($order->status === 'delivered')
                                                <span class="badge bg-warning text-primary">{{ $order->status }}</span>
                                            @else
                                                <span class="badge bg-danger text-primary">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="wg-box wg-table table-all-user">
                        <div class="row">
                            <div class="col-6">
                                <h5>Ordered Items</h5>
                            </div>
                            <div class="col-6 text-right">

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">SKU</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Brand</th>
                                        <th class="text-center">Options</th>
                                        <th class="text-center">Return Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td class="pname">
                                                <div class="image">
                                                    <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}"
                                                        alt="{{ $item->product->image }}" class="image">
                                                </div>
                                                <div class="name">
                                                    <a href="{{ route('product.details', $item->product->slug) }}"
                                                        target="_blank"
                                                        class="body-title-2">{{ $item->product->title }}</a>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ formatCurrency($item->product->sale_price) }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-center">{{ $item->product->sku }}</td>
                                            <td class="text-center">{{ $item->product->category->name }}</td>
                                            <td class="text-center">{{ $item->product->brand->name }}</td>
                                            <td class="text-center">{{ $item->options ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                @if ($item->rstatus == 1)
                                                    <span>Yes</span>
                                                @else
                                                    <span>NO</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                    </div>
                    <div class="wg-box mt-5">
                        <h5>Shipping Address</h5>
                        <div class="my-account__address-item__detail">
                            <p><strong>Address:</strong> {{ $order ? $order->address : 'N/A' }}</p>
                            <p><strong>Post:</strong> {{ $order ? $order->landmark : 'N/A' }} -
                                {{ $order ? $order->zip : 'N/A' }}</p>
                            <p><strong>Thana/Upzilla:</strong> {{ $order ? $order->locality : 'N/A' }}</p>
                            <p><strong>District:</strong> {{ $order ? $order->city : 'N/A' }}</p>
                            <p><strong>Division:</strong> {{ $order ? $order->state : 'N/A' }}</p>
                            <p><strong>Country:</strong> {{ $order ? $order->country : 'N/A' }}</p>
                            <br>
                            <p>Mobile : {{ $order ? $order->phone : Auth::user()->mobile }}</p>
                        </div>
                    </div>
                    <div class="wg-box mt-5">
                        <h5>Transactions</h5>
                        <div class="table-responsive">
                            <table class="table-striped table-bordered table-transaction">
                                <tbody>
                                    <tr>
                                        <th>Subtotal</th>
                                        <td>{{ formatCurrency($order->subtotal) }}</td>
                                        <th>Tax</th>
                                        <td>{{ formatCurrency($order->tax) }}</td>
                                        <th>Discount</th>
                                        <td>{{ formatCurrency($order->discount) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td>{{ formatCurrency($order->total) }}</td>
                                        <th>Payment Mode</th>
                                        <td>{{ $order->transaction->method }}</td>
                                        <th>Status</th>
                                        <td>
                                            @if ($order->transaction->status === 'pending')
                                                <span
                                                    class="badge bg-success text-primary">{{ $order->transaction->status }}</span>
                                            @elseif ($order->transaction->status === 'approved')
                                                <span
                                                    class="badge bg-warning text-primary">{{ $order->transaction->status }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $order->transaction->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($order->status === 'ordered')
                    <div class="wg-box mt-5 text-right">
                        <form id="orderCancelForm" action="{{ route('user.cancel-order') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <button onclick="return confirm('You Want to cancel the Order?')" type="submit"
                                id="orderCancelButton" class="btn btn-danger">Cancel Order</button>
                        </form>
                    </div>
                    @endif
                </div>

            </div>
        </section>
    </main>
@endsection
{{-- @push('scripts')
    <script>
        ;(function($){
            $('#orderCancelButton').on('click', function(e){
                e.preventDefault();
                let form = $('#orderCancelForm');
                swal({
                    title: "Are You Sure?",
                    text: "You want to cancel this order?",
                    type: "warning",
                    buttons: ['No', 'Yes'],
                    ConfirmButtonColor: '#dc3545',
                }).then(function(result)){
                    if(result){
                        form.submit();
                    }
                }
            });
        })(jQuery);
    </script>
@endpush --}}
