@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Addresses</h2>
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
                                <a href="{{ route('create.address') }}" class="btn btn-sm btn-info">Add New</a>
                            </div>
                        </div>
                        <div class="my-account__address-list row">
                            <h5>Shipping Address</h5>

                            <div class="my-account__address-item col-md-6">
                                <div class="my-account__address-item__title">
                                    <h5>{{ $address ? $address->name : Auth::user()->name }} <i class="fa fa-check-circle text-success"></i></h5>
                                    <a href="{{ route('edit.address', $address->id) }}">Edit</a>
                                </div>
                                <div class="my-account__address-item__detail">
                                    <p><strong>Address:</strong> {{ $address ? $address->address : 'N/A' }}</p>
                                    <p><strong>Post:</strong> {{ $address ? $address->landmark : 'N/A' }} - {{ $address ? $address->zip : 'N/A' }}</p>
                                    <p><strong>Thana/Upzilla:</strong> {{ $address ? $address->locality : 'N/A' }}</p>
                                    <p><strong>District:</strong> {{ $address ? $address->city : 'N/A' }}</p>
                                    <p><strong>Division:</strong> {{ $address ? $address->state : 'N/A' }}</p>
                                    <p><strong>Country:</strong> {{ $address ? $address->country : 'N/A' }}</p>
                                    <br>
                                    <p>Mobile : {{ $address ? $address->phone : Auth::user()->mobile }}</p>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
