@extends('layouts.app')
@section('content')
    <style>
        .text-danger {
            color: #f71c0a !important;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="contact-us container">
            <div class="mw-930">
                <h2 class="page-title">CONTACT US</h2>
            </div>
        </section>

        <hr class="mt-2 text-secondary " />
        <div class="mb-4 pb-4"></div>

        <section class="contact-us container">
            <div class="mw-930">
                <div class="contact-us__form">
                    <form id="contactForm" action="{{ route('contact.store') }}" name="contact-us-form"
                        class="needs-validation" novalidate="" method="POST">
                        @csrf
                        <h3 class="mb-5">Get In Touch</h3>
                        <div class="form-floating my-4">
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                placeholder="Name *" required="">
                            <label for="contact_us_name">Name *</label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <p class="err nameErr"></p>
                        </div>
                        <div class="form-floating my-4">
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                placeholder="Phone *" required="">
                            <label for="contact_us_name">Phone *</label>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <p class="err phoneErr"></p>
                        </div>
                        <div class="form-floating my-4">
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                placeholder="Email address *" required="">
                            <label for="contact_us_name">Email address *</label>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <p class="err emailErr"></p>
                        </div>
                        <div class="my-4">
                            <textarea class="form-control form-control_gray" name="message" placeholder="Your Message" cols="30" rows="8"
                                required="">{{ old('message') }}</textarea>
                            @error('message')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <p class="err messageErr"></p>
                        </div>
                        <div class="my-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <script>
        ;
        (function($) {
            $(document).ready(function() {
                $('#contactForm').validate({
                    ignore: '.ignore',
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
                            maxlength: 11
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        message: {
                            required: true
                        }
                    },
                    errorPlacement: function(error, element) {
                        if (element.attr('name') === 'name') {
                            error.appendTo($('.nameErr'))
                        } else if (element.attr('name') === 'phone') {
                            error.appendTo($('.phoneErr'));
                        } else if (element.attr('name') === 'email') {
                            error.appendTo($('.emailErr'));
                        }  else if (element.attr('name') === 'message') {
                            error.appendTo($('.messagelErr'));
                        } 
                        else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function(form) {
                        $.LoadingOverlay("show");
                        form.submit();
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
