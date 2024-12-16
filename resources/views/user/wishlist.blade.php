@extends('layouts.app');
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Wishlist</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__wishlist">
                        @if (count($items) > 0)
                            <div class="products-grid row row-cols-2 row-cols-lg-3" id="products-grid">
                                @foreach ($items as $item)
                                    <div class="product-card-wrapper">
                                        <div class="product-card mb-3 mb-md-4 mb-xxl-5">
                                            <div class="pc__img-wrapper">
                                                @if ($item->model->galleryImages)
                                                    <div class="swiper-container background-img js-swiper-slider"
                                                        data-settings='{"resizeObserver": true}'>
                                                        <div class="swiper-wrapper">
                                                            @foreach ($item->model->galleryImages as $galeryImage)
                                                                <div class="swiper-slide">
                                                                    <img loading="lazy"
                                                                        src="{{ asset('uploads/products/gallery/' . $galeryImage->image_path) }}"
                                                                        width="330" height="400"
                                                                        alt="{{ $galeryImage->image_path }}"
                                                                        class="pc__img">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <span class="pc__img-prev"><svg width="7" height="11"
                                                                viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                                                                <use href="#icon_prev_sm" />
                                                            </svg></span>
                                                        <span class="pc__img-next"><svg width="7" height="11"
                                                                viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                                                                <use href="#icon_next_sm" />
                                                            </svg></span>
                                                    </div>
                                                @endif
                                                <a onclick="return confirm('Are you sure?')" href="{{ route('wishlist.remove', $item->rowId) }}" class="btn-remove-from-wishlist">
                                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <use href="#icon_close" />
                                                    </svg>
                                                </a>
                                            </div>

                                            <div class="pc__info position-relative">
                                                <p class="pc__category">{{ $item->model->category->name }}</p>
                                                <h6 class="pc__title">{{ $item->name }}</h6>
                                                <div class="product-card__price d-flex">
                                                    <span class="money price">{{ formatCurrency($item->price) }}</span>
                                                </div>

                                                <a href="javascript:void(0)"
                                                    class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                                                    title="Added To Wishlist">
                                                    <svg fill="red" width="16px" height="16px" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M14 20.408c-.492.308-.903.546-1.192.709-.153.086-.308.17-.463.252h-.002a.75.75 0 01-.686 0 16.709 16.709 0 01-.465-.252 31.147 31.147 0 01-4.803-3.34C3.8 15.572 1 12.331 1 8.513 1 5.052 3.829 2.5 6.736 2.5 9.03 2.5 10.881 3.726 12 5.605 13.12 3.726 14.97 2.5 17.264 2.5 20.17 2.5 23 5.052 23 8.514c0 3.818-2.801 7.06-5.389 9.262A31.146 31.146 0 0114 20.408z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h5 class="text-center text-dark">{{ __('No wishlist item found') }}</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
