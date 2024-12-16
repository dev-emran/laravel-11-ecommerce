@extends('layouts.app')
@section('content')
    <style>
        .text-danger {
            color: red !important;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Address</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__address">
                        <div class="row">
                            <div class="col-6">
                                <p class="notice">The following addresses will be used on the checkout page by default.</p>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('customer.address') }}" class="btn btn-sm btn-danger">Back</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-5">
                                    <div class="card-header">
                                        <h5>Add New Address</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('store.address') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="name"
                                                            value="{{ old('name') }}">
                                                        <label for="name">Full Name *</label>
                                                    </div>
                                                    @error('name')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="phone"
                                                            value="{{ old('phone') }}">
                                                        <label for="phone">Phone Number *</label>
                                                    </div>
                                                    @error('phone')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="zip"
                                                            value="{{ old('zip') }}">
                                                        <label for="zip">Postal Code *</label>
                                                    </div>
                                                    @error('zip')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="landmark"
                                                            value="{{ old('landmark') }}">
                                                        <label for="landmark">Post *</label>
                                                    </div>
                                                    @error('landmark')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="locality"
                                                            value="{{ old('locality') }}">
                                                        <label for="locality">Thana/Upzilla</label>
                                                    </div>
                                                    @error('locality')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-floating my-3">
                                                        <input type="text" class="form-control" name="city"
                                                            value="{{ old('city') }}">
                                                        <label for="city">District</label>
                                                    </div>
                                                    @error('city')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="state"
                                                            value="{{ old('state') }}">
                                                        <label for="state">Division *</label>
                                                    </div>
                                                    @error('state')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="address"
                                                            value="{{ old('address') }}">
                                                        <label for="address">Address</label>
                                                    </div>
                                                    @error('address')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            id="isdefault" name="isdefault" checked>
                                                        <label class="form-check-label" for="isdefault">
                                                            Make as Default address
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-right">
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
