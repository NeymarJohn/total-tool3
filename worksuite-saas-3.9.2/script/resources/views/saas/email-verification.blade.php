@extends('layouts.sass-app')
@section('content')
    <!-- START Contact Section -->
    <section class="contact-section bg-white sp-100-70">
        <div class="container">
            <div class="row gap-y">
                <div class="col-12 col-md-6 offset-md-3 form-section">
                    <div class="col-12 col-md-10 bg-white px-30 py-45 rounded">
                        <div class="alert alert-{{$class}}">
                            {!! $message !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END Contact Section -->
@endsection
