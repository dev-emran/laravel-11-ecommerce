@extends('layouts.admin')
@section('content')
    <style>
        .table-transaction>tbody>tr:nth-of-type(odd) {
            --bs-table-accent-bg: #fff !important;
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Order Details</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="#">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Order Items</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h5>Ordered Items</h5>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.orders') }}">Back</a>
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
                            @foreach ($orderedItems as $item)
                                <tr>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}"
                                                alt="" class="image">
                                        </div>
                                        <div class="name">
                                            <a href="{{ route('product.details', $item->product->slug) }}" target="_blank"
                                                class="body-title-2">{{ $item->product->title }}</a>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ formatCurrency($item->product->sale_price) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ $item->product->sku }}</td>
                                    <td class="text-center">{{ $item->product->category->name }}</td>
                                    <td class="text-center">{{ $item->product->brand->name }}</td>
                                    <td class="text-center">{{ $item->options }}</td>
                                    <td class="text-center">{{ $item->rstatus == 0 ? 'NO' : 'YES' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $orderedItems->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <div class="wg-box mt-5">
                <h5>Shipping Address</h5>
                <div class="my-account__address-item col-md-6">
                    <div class="my-account__address-item__detail">
                        <p>{{ $order->name }}</p>
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->locality }}</p>
                        <p>{{ $order->state }}</p>
                        <p>{{ $order->city }}</p>
                        <p>{{ $order->landmark }}</p>
                        <br>
                        <p>Mobile : {{ $order->phone }}</p>
                    </div>
                </div>
            </div>
            <div class="wg-box mt-5">
                <h5>Transactions</h5>
                <table class="table table-striped table-bordered table-transaction">
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
                            <td>{{ strtoupper($order->transaction->method) }}</td>
                            <th>Status</th>
                            <td>
                                @if ($order->transaction->status === 'pending')
                                    <span class="bg-warning badge">{{ $order->transaction->status }}</span>
                                @elseif ($order->transaction->status === 'approved')
                                    <span class="bg-info badge">{{ $order->transaction->status }}</span>
                                @else
                                    <span class="bg-danger badge">{{ $order->transaction->status }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <td>{{ $order->created_at->format('Y-M-d') }}</td>
                            <th>Delivered Date</th>
                            <td>{{ $order->delivered_date ? $order->delivered_date : 'N/A' }}
                            </td>

                            <th>Canceled Date</th>
                            <td>{{ $order->canceled_date ? $order->canceled_date : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="wg-box mt-5">
                <h5>Update Status</h5>
                <form id="orderStatusForm" action="{{ route('update.order-status') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="select">
                                <select name="order_status" id="orderStatus">
                                    <option value="ordered" {{ $order->status === 'ordered' ? 'selected' : '' }}>Ordered
                                    </option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>
                                        Delivered</option>
                                    <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Canceled
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        ;
        (function($) {
            $(document).ready(function() {
                $('#orderStatus').on('change', function() {
                    $('#orderStatusForm').submit();
                });
            });
        })(jQuery);
    </script>
@endpush
