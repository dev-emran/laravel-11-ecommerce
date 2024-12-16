@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Slide</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="index.html">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="slider.html">
                            <div class="text-tiny">Slider</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">New Slide</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-style-1" id="createSliderForm" method="POST" action="{{ route('slide.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <fieldset class="title">
                        <div class="body-title">Title <span class="tf-color-1">*</span></div>
                        <div>
                            <input class="flex-grow" type="text" placeholder="Title" name="title" tabindex="0"
                                value="{{ $slide->title }}" aria-required="true">
                            <input type="hidden" name="slide_id" value="{{ $slide->id }}">
                            @error('title')
                                <em class="text-danger">{{ $message }}</em>
                            @enderror
                        </div>
                    </fieldset>
                    <fieldset class="tagline">
                        <div class="body-title">Tag line <span class="tf-color-1">*</span></div>
                        <div>
                            <input class="flex-grow" type="text" placeholder="Tag line" name="tagline" tabindex="0"
                                value="{{ $slide->tagline }}" aria-required="true">
                            @error('tagline')
                                <em class="text-danger">{{ $message }}</em>
                            @enderror
                        </div>
                    </fieldset>
                    <fieldset class="subtitle">
                        <div class="body-title">Sub title <span class="tf-color-1">*</span></div>
                        <div>
                            <input class="flex-grow" type="text" placeholder="Sub title" name="subtitle" tabindex="0"
                                value="{{ $slide->subtitle }}" aria-required="true">
                            @error('subtitle')
                                <em class="text-danger">{{ $message }}</em>
                            @enderror
                        </div>
                    </fieldset>
                    <fieldset class="link">
                        <div class="body-title">Link<span class="tf-color-1">*</span></div>
                        <div>
                            <input class="flex-grow" type="url" placeholder="Link" name="link" tabindex="0"
                                value="{{ $slide->link }}" aria-required="true">
                            @error('link')
                                <em class="text-danger">{{ $message }}</em>
                            @enderror
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="body-title">Upload images <span class="tf-color-1">*</span>
                        </div>
                        <div class="w-100">
                            <div class="upload-image flex-grow">
                                <div class="item" id="imgpreview">
                                    <img id="imgView" src="{{ asset('uploads/slider/' . $slide->image) }}" class="effect8" alt="">
                                </div>
                                <div id="slideImageDiv" class="item up-load">
                                    <label class="uploadfile" for="slideImage">
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="body-text">Drop your images here or select <span class="tf-color">click
                                                to
                                                browse</span></span>
                                        <input type="file" id="slideImage" name="image">
                                    </label>
                                </div>
                            </div>
                            @error('image')
                                <em class="text-danger">{{ $message }}</em>
                            @enderror
                        </div>
                    </fieldset>
                    <fieldset class="category">
                        <div class="body-title">Select Status</div>
                        <div class="w-100">
                            <div class="select flex-grow">
                                <select name="status" class="">
                                    <option value="">Select Status</option>
                                    <option value="active" {{ $slide->status === 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $slide->status === 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                            @error('status')
                                <em class="text-danger">{{ $message }}</em>
                            @enderror
                        </div>
                    </fieldset>
                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Save</button>
                    </div>
                </form>
            </div>
            <!-- /new-category -->
        </div>
        <!-- /main-content-wrap -->
    </div>
@endsection
@push('scripts')
    <script>
        //Slider Images drag and drop and preview functionallity============================================================>
        ;
        (function($) {
            $(document).ready(function() {

                //Drag and drop upload
                $(document).ready(function() {
                    var uploadFile = $('#slideImageDiv');
                    var fileInput = $('#slideImage');
                    var previewContainer = $('#imgpreview');
                    var imagePreview = $('#imgView');

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
            });
        })(jQuery);
    </script>
@endpush
