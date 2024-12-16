@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Slider</h3>
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
                        <div class="text-tiny">Slider</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="name"
                                    tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('slide.create') }}"><i class="icon-plus"></i>Add
                        new</a>
                </div>
                <div class="wg-table table-all-user">
                    @if (count($slides) > 0)
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Tagline</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Link</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($slides as $slide)
                                    <tr>
                                        <td>{{ $slide->id }}</td>
                                        <td class="pname">
                                            <div class="image">
                                                <img src="{{ asset('uploads/slider/' . $slide->image) }}"
                                                    alt="{{ $slide->image }}" class="image">
                                            </div>
                                        </td>
                                        <td>{{ $slide->tagline }}</td>
                                        <td>{{ $slide->title }}</td>
                                        <td>{{ $slide->subtitle }}</td>
                                        <td>{{ $slide->link }}</td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="{{ route('slide.edit', $slide->id) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
                                                <form action="{{ route('slide.delete') }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="slide_id" value="{{ $slide->id }}">
                                                    <div class="item text-danger delete">
                                                        <button class="slide-delete-btn" type="submit"><i
                                                                class="icon-trash-2"></i></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                        {{-- onclick="return confirm('You want to delete this slider?')" --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <h5 class="text-center text-dark">{{ __('No Slider found') }}</h5>
                    @endif
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $slides->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        ;
        (function($) {
            $(document).ready(function() {
                // Use event delegation to bind the click event
                $(document).on('click', '.slide-delete-btn', function(e) {
                    e.preventDefault(); // Prevent default form action
                    let form = $(this).closest('form'); // Get the closest form
                    swal({
                        title: "Are you sure?",
                        text: "You want to delete this slider?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then(function(result) {
                        if (result) {
                            form.submit(); // Submit the form if confirmed
                        }
                    });
                });


                // $(document).on('click', '.slide-delete-btn', function(e) {
                //     e.preventDefault();
                //     let form = $(this).closest('form');
                //     Swal.fire({
                //         title: "Are you sure?",
                //         text: "You want to delete this slider?",
                //         icon: "warning",
                //         showCancelButton: true,
                //         confirmButtonText: "Yes, delete it!",
                //         cancelButtonText: "Cancel"
                //     }).then((result) => {
                //         if (result.isConfirmed) {
                //             form.submit();
                //         }
                //     });
                // });

            });
        })(jQuery);
    </script>
@endpush
