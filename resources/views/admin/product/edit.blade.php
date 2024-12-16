@extends('layouts.admin')
@section('content')
    <!-- main-content-wrap -->
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Product</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="index-2.html">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="all-product.html">
                            <div class="text-tiny">Products</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit product</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form id="ProductEditForm" class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                action="{{ route('product.update') }}">
                @method('PUT')
                @csrf
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                        </div>
                        <input id="productName" class="mb-10" type="text" placeholder="Enter product name"
                            name="name" tabindex="0" value="{{ $product->title }}" aria-required="true">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="text-tiny">Do not exceed 100 characters when entering the
                            product name.</div>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                        <input id="productSlug" class="mb-10" type="text" placeholder="Enter product slug"
                            name="slug" tabindex="0" value="{{ $product->slug }}" aria-required="true">
                        <div class="text-tiny">Do not exceed 100 characters when entering the
                            product name.</div>
                    </fieldset>

                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="category_id">
                                    <option selected disabled>Choose category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        <fieldset class="brand">
                            <div class="body-title mb-10">Brand <span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="brand_id">
                                    <option selected disabled>Choose Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                    </div>

                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0"
                            aria-required="true">{{ $product->short_description }}</textarea>
                        <div class="text-tiny">Do not exceed 100 characters when entering the
                            product name.</div>
                    </fieldset>

                    <fieldset class="description">
                        <div class="body-title mb-10">Description <span class="tf-color-1">*</span>
                        </div>
                        <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true">{{ $product->description }}</textarea>
                        <div class="text-tiny">Do not exceed 100 characters when entering the
                            product name.</div>
                    </fieldset>
                </div>
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Upload images <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="">
                                <img id="imgView" src="{{ asset('uploads/products/thumbnails/' . $product->image) }}"
                                    class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click
                                            to browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                        <p><em id="imgErrDiv"></em></p>
                    </fieldset>

                    <fieldset>
                        <div class="body-title mb-10">Upload Gallery Images</div>
                        <div class="upload-image mb-16">
                            <div id="imagePreview" class="image-preview">
                                @if (count($gallery_images) > 0)
                                    @foreach ($gallery_images as $g_img)
                                        <div>
                                            <img class="preview-img" src="{{ asset('uploads/products/gallery/' . $g_img->image_path) }}" alt="">
                                            <button id="g_img_removeBtn" class="remove-img-btn">Remove</button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div id="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="text-tiny">Drop your images here or select <span class="tf-color">click
                                            to browse</span></span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*"
                                        multiple="">
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price"
                                tabindex="0" value="{{ $product->regular_price }}" aria-required="true">
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price"
                                tabindex="0" value="{{ $product->sale_price }}" aria-required="true">
                        </fieldset>
                    </div>


                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" tabindex="0"
                                value="{{ $product->sku }}" aria-required="true">
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity"
                                tabindex="0" value="{{ $product->quantity }}" aria-required="true">
                        </fieldset>
                    </div>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Stock</div>
                            <div class="select mb-10">
                                <select class="" name="stock_status">
                                    <option value="instock" {{ $product->stock_status == 'instock' ? 'selected' : '' }}>
                                        InStock
                                    </option>
                                    <option value="outofstock"
                                        {{ $product->stock_status == 'outofstock' ? 'selected' : '' }}>Out of
                                        Stock
                                    </option>
                                </select>
                            </div>
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Featured</div>
                            <div class="select mb-10">
                                <select class="" name="featured">
                                    <option value="0" {{ $product->featured == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $product->featured == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Update product</button>
                    </div>
                </div>
            </form>
            <!-- /form-add-product -->
        </div>
        <!-- /main-content-wrap -->
    </div>
    <!-- /main-content-wrap -->
@endsection
@push('scripts')
    <script>
        //Gallery Images drag and drop and preview functionallity============================================================>
        $(document).ready(function() {
            var fileInput = $('#gFile');
            var previewContainer = $('#imagePreview');

            // Handle file selection
            fileInput.on('change', function(event) {
                var files = event.target.files;
                displayImagePreview(files);
            });

            // Handle drag and drop
            $('#galUpload').on('dragover', function(event) {
                event.preventDefault();
                event.stopPropagation();
                $(this).addClass('drag-over'); // Optional: add class for styling
            });

            $('#galUpload').on('dragleave', function(event) {
                event.preventDefault();
                event.stopPropagation();
                $(this).removeClass('drag-over'); // Optional: remove class when dragging leaves
            });

            $('#galUpload').on('drop', function(event) {
                event.preventDefault();
                event.stopPropagation();
                $(this).removeClass('drag-over'); // Optional: remove class when dragging ends
                var files = event.originalEvent.dataTransfer.files;
                fileInput[0].files = files; // Update file input with dropped files
                displayImagePreview(files); // Preview the dropped images
            });

            // Function to display image preview
            function displayImagePreview(files) {
                previewContainer.empty(); // Clear existing previews
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (file.type.startsWith('image/')) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            var imgElement = $('<div>', {
                                class: 'preview-container'
                            }).append(
                                $('<img>', {
                                    src: event.target.result,
                                    alt: 'Image Preview',
                                    class: 'preview-img'
                                }),
                                $('<button>', {
                                    text: 'Remove',
                                    class: 'remove-img-btn',
                                    click: function() {
                                        $(this).parent().remove(); // Remove the image container
                                        // Optionally: You can also remove the file from the file input (if needed)
                                    }
                                })
                            );
                            previewContainer.append(imgElement);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }


            $('#g_img_removeBtn').on('click', function(){
                $(this).parent().remove();
            });
        });
    </script>
@endpush