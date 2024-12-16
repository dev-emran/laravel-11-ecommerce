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
                            <div class="col-md-8">
                                <div class="card mb-5">
                                    <div class="card-header">
                                        <h5>Edit Address</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('update.address') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <input type="hidden" name="user_id" value="{{ $address->user_id }}">
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="name"
                                                            value="{{ $address->name }}">
                                                        <label for="name">Full Name *</label>
                                                    </div>
                                                    @error('name')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="phone"
                                                            value="{{ $address->phone }}">
                                                        <label for="phone">Phone Number *</label>
                                                    </div>
                                                    @error('phone')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="zip"
                                                            value="{{ $address->zip }}">
                                                        <label for="zip">Postal Code *</label>
                                                    </div>
                                                    @error('zip')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="landmark"
                                                            value="{{ $address->landmark }}">
                                                        <label for="landmark">Post *</label>
                                                    </div>
                                                    @error('landmark')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="locality"
                                                            value="{{ $address->locality }}">
                                                        <label for="locality">Thana/Upzilla</label>
                                                    </div>
                                                    @error('locality')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-floating my-3">
                                                        <input type="text" class="form-control" name="city"
                                                            value="{{ $address->city }}">
                                                        <label for="city">District</label>
                                                    </div>
                                                    @error('city')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="state"
                                                            value="{{ $address->state }}">
                                                        <label for="state">Division *</label>
                                                    </div>
                                                    @error('state')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" name="address"
                                                            value="{{ $address->address }}">
                                                        <label for="address">Address</label>
                                                    </div>
                                                    @error('address')
                                                        <em class="text-danger">{{ $message }}</em>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            id="isdefault" name="isdefault" {{ $address->isdefault == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="isdefault">
                                                            Make as Default address
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-right">
                                                    <button type="submit" class="btn btn-success">Update</button>
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
