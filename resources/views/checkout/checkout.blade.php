@extends('layouts.app')

@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Shipping and Checkout</h2>
            <div class="checkout-steps">
                <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Shopping Bag</span>
                        <em>Manage Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Shipping and Checkout</span>
                        <em>Checkout Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Confirmation</span>
                        <em>Review And Submit Your Order</em>
                    </span>
                </a>
            </div>
            <form id="checkOutForm" method="POST" action="{{ route('checkout.store') }}">
                @csrf
                <div class="checkout-form">
                    <div class="billing-info__wrapper">
                        <div class="row">
                            <div class="col-6">
                                <h4>SHIPPING DETAILS</h4>
                            </div>
                            <div class="col-6">
                            </div>
                            <h5 class="text-center mt-3 text-danger">All fields are filled by your default address</h5>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $address ? $address->name : Auth::user()->name }}" required="">
                                    <label for="name">Full Name *</label>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ $address ? $address->phone : Auth::user()->mobile }}" required="">
                                    <label for="phone">Phone Number *</label>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="zip"
                                        value="{{ $address ? $address->zip : '' }}" required="">
                                    <label for="zip">Postal code *</label>
                                    @error('zip')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mt-3 mb-3">
                                    <input type="text" class="form-control" name="state"
                                        value="{{ $address ? $address->state : '' }}" required="">
                                    <label for="state">Division *</label>
                                    @error('state')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="city"
                                        value="{{ $address ? $address->city : '' }}" required="">
                                    <label for="city">District *</label>
                                    @error('city')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="locality"
                                        value="{{ $address ? $address->locality : '' }}" required="">
                                    <label for="locality">Thana/Upzilla *</label>
                                    @error('locality')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="address"
                                        value="{{ $address ? $address->address : '' }}" required="">
                                    <label for="address">Address *</label>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="landmark"
                                        value="{{ $address ? $address->landmark : '' }}" required="">
                                    <label for="landmark">Post *</label>
                                    @error('landmark')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="checkout__totals-wrapper">
                        <div class="sticky-content">
                            <div class="checkout__totals">
                                <h3>Your Order</h3>
                                <table class="checkout-cart-items">
                                    <thead>
                                        <tr>
                                            <th>PRODUCT</th>
                                            <th class="text-right">SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (Cart::instance('cart')->content() as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->name }} x {{ $item->qty }}
                                                </td>
                                                <td class="text-right">
                                                    {{ formatCurrency($item->subtotal()) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (Session::has('discounts'))
                                    <table class="checkout-totals">
                                        <tbody>
                                            <tr>
                                                <th>Subtotal</th>
                                                <td class="text-right">
                                                    {{ formatCurrency(Cart::instance('cart')->subTotal()) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Discount ({{ Session::get('coupon')['code'] ?? 'N/A' }})</th>
                                                <td class="text-right">
                                                    {{ formatCurrency(Session::get('discounts')['discount']) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Subtotal After Discount</th>
                                                <td class="text-right">
                                                    {{ formatCurrency(Session::get('discounts')['subtotal']) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping</th>
                                                <td class="text-right">Free</td>
                                            </tr>
                                            <tr>
                                                <th>Tax</th>
                                                <td class="text-right">
                                                    {{ formatCurrency(Session::get('discounts')['tax']) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td class="text-right">
                                                    {{ formatCurrency(Session::get('discounts')['total']) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="checkout-totals">
                                        <tbody>
                                            <tr>
                                                <th>SUBTOTAL</th>
                                                <td class="text-right">
                                                    {{ formatCurrency(Cart::instance('cart')->subtotal()) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>SHIPPING</th>
                                                <td class="text-right">Free shipping</td>
                                            </tr>
                                            <tr>
                                                <th>TAX</th>
                                                <td class="text-right">{{ formatCurrency(Cart::instance('cart')->tax()) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>TOTAL</th>
                                                <td class="text-right">
                                                    {{ formatCurrency(Cart::instance('cart')->total()) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="checkout__payment-methods">
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio"
                                        name="payment_method" value="cod" id="checkout_payment_method_1" checked>
                                    <label class="form-check-label" for="checkout_payment_method_1">
                                        Cash on delivery
                                        <p class="option-detail">
                                            Make your payment directly into our bank account. Please use your Order ID as
                                            the payment
                                            reference.Your order will not be shipped until the funds have cleared in our
                                            account.
                                        </p>

                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio"
                                        name="payment_method" value="bkash" id="checkout_payment_method_2">
                                    <label class="form-check-label" for="checkout_payment_method_2">
                                        Bkash
                                        <p class="option-detail">
                                            Phasellus sed volutpat orci. Fusce eget lore mauris vehicula elementum gravida
                                            nec dui. Aenean
                                            aliquam varius ipsum, non ultricies tellus sodales eu. Donec dignissim viverra
                                            nunc, ut aliquet
                                            magna posuere eget.
                                        </p>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio"
                                        name="payment_method" value="nagad" id="checkout_payment_method_3">
                                    <label class="form-check-label" for="checkout_payment_method_3">
                                        Nagad
                                        <p class="option-detail">
                                            Phasellus sed volutpat orci. Fusce eget lore mauris vehicula elementum gravida
                                            nec dui. Aenean
                                            aliquam varius ipsum, non ultricies tellus sodales eu. Donec dignissim viverra
                                            nunc, ut aliquet
                                            magna posuere eget.
                                        </p>
                                    </label>
                                </div>
                                <div class="policy-text">
                                    Your personal data will be used to process your order, support your experience
                                    throughout this
                                    website, and for other purposes described in our <a href="terms.html"
                                        target="_blank">privacy
                                        policy</a>.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-checkout">PLACE ORDER</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            console.log($('#checkOutForm').attr('action') );
            $('#checkOutForm').validate({
                errorClass: 'errDiv',
                validClass: 'SuccessDiv',
                rules: {
                    name: {
                        required: true,
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 11,
                        maxlength: 11,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    zip: {
                        required: true,
                    },
                    state: {
                        required: true,
                    },
                    city: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    locality: {
                        required: true,
                    },
                    landmark: {
                        required: true,
                    },
                    payment_method: {
                        required: true,
                    },
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    // Show loader and submit
                    $.LoadingOverlay("show");
                    form.submit();
                },
            });
        });
    </script>
@endpush

