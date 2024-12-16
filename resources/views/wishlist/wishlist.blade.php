@extends('layouts.app')
@section('content')
    @if (count($wishlistItems) > 0)
        <div class="wishlist-main-wrapper mt-5">
            <div class="container">
                <!-- Wishlist Page Content Start -->
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Wishlist Table Area -->
                        <div class="cart-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="pro-thumbnail">Thumbnail</th>
                                        <th class="pro-title">Product</th>
                                        <th class="pro-price">Price</th>
                                        <th class="pro-quantity">Stock Status</th>
                                        <th class="pro-subtotal">Add to Cart</th>
                                        <th class="pro-remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wishlistItems as $wishlistItem)
                                        <tr>
                                            <td class="pro-thumbnail"><a
                                                    href="{{ route('product.details', $wishlistItem->model->slug) }}"><img
                                                        class="img-fluid"
                                                        src="{{ asset('uploads/products/thumbnails/' . $wishlistItem->model->image) }}"
                                                        alt="$wishlistItem" /></a></td>
                                            <td class="pro-title"><a
                                                    href="{{ route('product.details', $wishlistItem->model->slug) }}">{{ $wishlistItem->name }}</a>
                                            </td>
                                            <td class="pro-price"><span>৳ {{ $wishlistItem->price }}</span></td>
                                            <td class="pro-quantity"><span
                                                    class="px-2 py-1 rounded-3 {{ $wishlistItem->model->stock_status === 'instock' ? 'text-dark bg-warning' : 'text-danger bg-dark' }}">{{ $wishlistItem->model->stock_status }}</span>
                                            </td>
                                            <td class="pro-subtotal">
                                                @if ($wishlistItem->model->stock_status === 'instock')
                                                    <a href="{{ route('wishlist.ToCart', $wishlistItem->rowId) }}" class="sqr-btn text-white">Move
                                                        to
                                                        Cart
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0)"
                                                        class="btn border-0 text-uppercase fw-medium text-dark"
                                                        title="out Of stock" @disabled(true)
                                                        aria-disabled="true">Out of Stock</a>
                                                @endif
                                            </td>
                                            <td class="pro-remove"><a onclick="return confirm('Are You Sure?')"
                                                    href="{{ route('wishlist.remove', $wishlistItem->rowId) }}"><i
                                                        class="fa fa-trash-o"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Wishlist Page Content End -->
            </div>
        </div>
    @else
        <h5 class="text-center text-dark mt-10 py-5">{{ __('No Wishlist items found') }}</h5>
    @endif
@endsection
