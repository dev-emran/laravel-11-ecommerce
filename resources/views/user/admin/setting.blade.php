@extends('layouts.admin')
@section('content')
    <style>
        .text-danger {
            font-size: initial;
            line-height: 36px;
        }

        .alert {
            font-size: initial;
        }
    </style>

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Settings</h3>
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
                        <div class="text-tiny">Settings</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="col-lg-8">
                    <div class="page-content my-account__edit">
                        <div class="my-account__edit-form">
                            <form id="profileSettingForm" name="account_edit_form" action="{{ route('update.setting') }}"
                                method="POST" enctype="multipart/form-data" class="form-style-1 needs-validation"
                                novalidate="">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <fieldset class="name">
                                    <div class="body-title">Name <span class="tf-color-1">*</span>
                                    </div>
                                    <input class="flex-grow w-25" type="text" placeholder="Full Name" name="name"
                                        value="{{ $user->name }}" tabindex="0" aria-required="true" required="">
                                </fieldset>

                                <fieldset class="name">
                                    <div class="body-title">Mobile Number <span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="Mobile Number" name="mobile"
                                        value="{{ $user->mobile }}" tabindex="0" aria-required="true" required="">
                                </fieldset>

                                <fieldset class="name">
                                    <div class="body-title">Email Address <span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="Email Address" name="email"
                                        value="{{ $user->email }}" tabindex="0" aria-required="true" required="">
                                </fieldset>

                                <fieldset>
                                    <div class="body-title">Upload images <span class="tf-color-1">*</span>
                                    </div>
                                    <div class="upload-image flex-grow">
                                        <div class="item" id="profileImageView"
                                            style="{{ $user->profilePhoto ? '' : 'display:none' }}">
                                            <img id="profilePreview"
                                                src="{{ $user->profilePhoto ? asset('uploads/profile/' . $user->profilePhoto->image) : '' }}"
                                                class="effect8" alt="">
                                        </div>
                                        <div id="upload-profile" class="item up-load">
                                            <label class="uploadfile" for="myProfile">
                                                <span class="icon">
                                                    <i class="icon-upload-cloud"></i>
                                                </span>
                                                <span class="body-text">Drop your images here or select <span
                                                        class="tf-color">click
                                                        to browse</span></span>
                                                <input type="file" id="myProfile" name="profile_photo" accept="image/*">
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="my-3">
                                            <h5 class="text-uppercase mb-0">Password Change</h5>
                                            @if (Session::has('passErr'))
                                                <p class=" alert alert-danger">{{ Session('passErr') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <fieldset class="name">
                                            <div class="body-title pb-3">Old password <span class="tf-color-1">*</span>
                                            </div>
                                            <input class="flex-grow" type="password" placeholder="Old password"
                                                id="old_password" name="old_password" aria-required="true">
                                        </fieldset>
                                        @error('old_password')
                                            <em class="text-danger">{{ $message }}</em>
                                        @enderror

                                    </div>
                                    <div class="col-md-12">
                                        <fieldset class="name">
                                            <div class="body-title pb-3">New password <span class="tf-color-1">*</span>
                                            </div>
                                            <input class="flex-grow" type="password" placeholder="New password"
                                                id="new_password" name="password" aria-required="true">
                                        </fieldset>
                                        @error('password')
                                            <em class="text-danger">{{ $message }}</em>
                                        @enderror

                                    </div>
                                    <div class="col-md-12">
                                        <fieldset class="name">
                                            <div class="body-title pb-3">Confirm new password <span
                                                    class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="password" placeholder="Confirm new password"
                                                cfpwd="" data-cf-pwd="#new_password" id="new_password_confirmation"
                                                name="password_confirmation" aria-required="true">
                                            <div class="invalid-feedback">Passwords did not match!
                                            </div>
                                        </fieldset>
                                        @error('password_confirmation')
                                            <em class="text-danger">{{ $message }}</em>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <div class="my-3">
                                            <button type="submit" class="btn btn-primary tf-button w208">Save
                                                Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function($) {
            $(document).ready(function() {

                $('#profileSettingForm').validate({
                    ignore: '.ignore',
                    errorClass: 'errDiv',
                    validClass: 'SuccessDiv',
                    rules: {
                        name: {
                            required: true,
                        },
                        mobile: {
                            required: true,
                        },
                        email: {
                            required: true,
                            email: true
                        }
                    },
                    errorPlacement: function(error, element) {
                        if (element.attr('name') === 'name') {
                            error.appendTo($('#nameErrDiv'))
                        } else if (element.attr('name') === 'slug') {
                            error.appendTo($('#slugErrDiv'));
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function(form) {
                        $.LoadingOverlay("show");
                        form.submit();
                    }
                });

                //Drag and drop upload
                var uploadFile = $('#upload-profile');
                var fileInput = $('#myProfile');
                var previewContainer = $('#profileImageView');
                var imagePreview = $('#profilePreview');

                // Highlight on drag enter
                uploadFile.on('dragenter', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadFile.addClass('dragging');
                });

                // Remove highlight on drag leave
                uploadFile.on('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadFile.removeClass('dragging');
                });

                // Highlight on drag over
                uploadFile.on('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadFile.addClass('dragging');
                });

                // Handle file drop
                uploadFile.on('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadFile.removeClass('dragging');

                    var files = e.originalEvent.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput[0].files = files;
                        displayImagePreview(files[0]);
                    }
                });

                // Trigger file input on clicking the label
                uploadFile.on('click', function() {
                    fileInput.click();
                });

                // Handle file selection
                fileInput.on('change', function() {
                    if (fileInput[0].files.length > 0) {
                        displayImagePreview(fileInput[0].files[0]);
                    }
                });

                // Display image preview
                function displayImagePreview(file) {
                    if (file && file.type.startsWith('image/')) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.attr('src', e.target.result);
                            previewContainer.show();
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        })(jQuery)
    </script>
@endpush
