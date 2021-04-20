<section class="section bg-img" id="section-contact" style="background-image: url({{ asset('front/img/bg-cup.jpg') }})" data-overlay="8">
    <div class="container">
            <div class="row gap-y">
                <div class="col-12 col-md-10 offset-md-1">
                    {!! $frontDetail->contact_html !!}
                </div>
            </div>
        <div class="row gap-y">

            <div class="col-12 col-md-6">
                {!! Form::open(['id'=>'contactUs','class'=>'row', 'method'=>'POST']) !!}
                    <div class="col-12 col-md-10 offset-md-1 bg-white px-30 py-45 rounded">
                        <p id="alert"></p>
                        <div id="contactUsBox">
                            <div class="form-group">
                                <input class="form-control form-control-lg" type="text" name="name" placeholder="@lang('modules.profile.yourName')">
                            </div>

                            <div class="form-group">
                                <input class="form-control form-control-lg" type="email" name="email" placeholder="@lang('modules.profile.yourEmail')">
                            </div>

                            <div class="form-group">
                                <textarea class="form-control form-control-lg" rows="4" placeholder="@lang('modules.messages.message')" name="message"></textarea>
                            </div>

                            @if ($global->google_recaptcha_status)
                            <div class="g-recaptcha" data-sitekey="{{ $global->google_recaptcha_key }}"></div>
                            <br>
                            @endif
                           

                            <button class="btn btn-lg btn-block btn-primary " type="button" id="save-form">@lang('modules.frontCms.submitEnquiry')</button>
                        </div>


                    </div>
                {!! Form::close() !!}
            </div>

            <div class="col-12 col-md-4 offset-md-1 text-inverse pt-40">
                <h6>@lang('app.address')</h6>
                <p>{{ $detail->address }}</p>
                @if($detail->phone)
                    <br>
                    <h6>@lang('app.phone')</h6>
                    <p>{{ $detail->phone }}</p>
                @endif
                <br>
                <h6>@lang('app.email')</h6>
                <p>{{ $detail->email }}</p>
            </div>

        </div>
    </div>
</section>
