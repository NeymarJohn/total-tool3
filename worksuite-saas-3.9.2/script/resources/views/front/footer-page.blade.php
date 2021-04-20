@extends('layouts.front-app')
@section('content')
    <style>
        .video-box {
            width:75%;
            height: 400px;
        }
    </style>
    <section class="section" style="min-height: 450px;">
        <div class="container">
            <div class="row gap-y">
                <div class="col-12 d-flex justify-content-center ">
                    <h3>{{ ucwords($slugData->name) }}</h3>
                </div>

                <div class="col-12">
                    <p>
                        @if(!is_null($slugData->video_embed))
                            <div class="row">
                                {!! $slugData->video_embed !!}
                            </div>
                        @elseif(!is_null($slugData->video_link))
                            <div class="row">
                                <div align="center" class="embed-responsive embed-responsive-4by3 video-box">
                                    <video controls class="embed-responsive-item">
                                        <source src="{!! $slugData->video_link !!}" type="video/mp4">
                                    </video>

                                </div>
                            </div>
                        @elseif(!is_null($slugData->file_name))
                            <div class="row">
                                <div align="center" class="embed-responsive embed-responsive-4by3 video-box">
                                    <video controls class="embed-responsive-item">
                                        <source src="{{ asset_url('footer-files/'.$slugData->id.'/'.$slugData->file_name) }}" type="video/mp4">
                                    </video>

                                </div>
                            </div>
                        @endif
                    </p>
                    <p>{!! $slugData->description !!}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
