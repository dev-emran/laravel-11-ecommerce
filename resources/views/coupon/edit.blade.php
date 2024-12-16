@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Coupon infomation</h3>
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
                        <a href="#">
                            <div class="text-tiny">Coupons</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">New Coupon</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form id="CouponForm" class="form-new-product form-style-1" method="POST"
                    action="{{ route('coupon.update') }}">
                    @csrf
                    @method('PUT')
                    <fieldset class="name">
                        <div class="body-title">Coupon Code <span class="tf-color-1">*</span></div>
                        <div>
                            <input class="flex-grow" type="text" placeholder="Coupon Code" name="code" tabindex="0"
                                value="{{ $coupon->code }}" aria-required="true" required="">
                            @error('code')
                                <p><em class="text-danger">{{ $message }}</em></p>
                            @enderror
                        </div>
                    </fieldset>
                    <fieldset class="category">
                        <div class="body-title">Coupon Type</div>
                        <div class="select flex-grow">
                            <select id="couponType" class="" name="type">
                                <option value="" disabled>Select Coupon Type</option>
                                <option value="fixed" {{  $coupon->type  === 'fixed' ? 'selected' : '' }}>Fixed</option>
                                <option value="percent" {{  $coupon->type  === 'percent' ? 'selected' : '' }}>Percent</option>
                            </select>
                            @error('type')
                                <p><em class="text-danger">{{ $message }}</em></p>
                            @enderror
                        </div>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Value <span class="tf-color-1">*</span></div>
                        <div>
                            <input id="CV" class="flex-grow" type="text" placeholder="" name="value"
                                tabindex="0" value="{{ $coupon->value }}" aria-required="true" required="">
                            @error('value')
                                <p><em class="text-danger">{{ $message }}</em></p>
                            @enderror
                        </div>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Cart Value <span class="tf-color-1">*</span></div>
                        <div>
                            <input class="flex-grow" type="text" placeholder="Cart Value" name="cart_value"
                                tabindex="0" value="{{ $coupon->cart_value }}" aria-required="true" required="">
                            @error('cart_value')
                                <p><em class="text-danger">{{ $message }}</em></p>
                            @enderror
                        </div>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Expiry Date <span class="tf-color-1">*</span></div>
                        <div>
                            <input class="flex-grow" type="date" placeholder="Expiry Date" name="expire_date"
                                tabindex="0" value="{{ $coupon->expire_date }}" aria-required="true" required="">
                                <input type="hidden" name="c_id" value="{{ $coupon->id }}">
                            @error('expire_date')
                                <p><em class="text-danger">{{ $message }}</em></p>
                            @enderror
                        </div>
                    </fieldset>

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Save</button>
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
                $('#CouponForm').validate({
                    ignore: '.ignore',
                    errorClass: 'errDiv',
                    validClass: 'SuccessDiv',
                    rules: {
                        code: {
                            required: true,
                        },
                        type: {
                            required: true,
                        },
                        value: {
                            required: true,
                        },
                        cart_value: {
                            required: true,
                        },
                        expire_date: {
                            required: true,
                            date: true,
                        },
                    },
                    // errorPlacement: function(error, element) {
                    //     if (element.attr('name') === 'name') {
                    //         error.appendTo($('#cateNameErrDiv'))
                    //     } else if (element.attr('name') === 'slug') {
                    //         error.appendTo($('#cateSlugErrDiv'));
                    //     } else {
                    //         error.insertAfter(element);
                    //     }
                    // },
                    submitHandler: function(form) {
                        $.LoadingOverlay("show");
                        form.submit();
                    }
                });

                $('#couponType').on('change', function() {
                    if ($(this).val() === '') {
                        $('#CV').attr('placeholder', 'Coupon Value');
                    } else if ($(this).val() === 'fixed') {
                        $('#CV').attr('placeholder', 'Fixed Coupon Value');
                    } else if ($(this).val() === 'percent') {
                        $('#CV').attr('placeholder', 'Percent Coupon Value');
                    }

                })
            });
        })(jQuery);
    </script>
@endpush
