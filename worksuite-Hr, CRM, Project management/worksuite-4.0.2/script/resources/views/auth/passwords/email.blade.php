@extends('layouts.auth')

@section('content')


    <form class="form-horizontal form-material"  method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}

        @if (session('status'))
            <div class="alert alert-success m-t-10">
                {{ session('status') }}
            </div>
        @endif

        <h3 class="box-title m-t-40 m-b-0">@lang('app.recoverPassword')</h3>

        <div class="form-group ">
            <div class="col-xs-12">
                <p class="text-muted">@lang('app.enterEmailInstruction') </p>
            </div>
        </div>
        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
            <div class="col-xs-12">
                <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required="" placeholder="@lang('app.email')">
                @if ($errors->has('email'))
                    <span class="help-block">
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-primary btn-rounded btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">@lang('app.sendPasswordLink')</button>
            </div>
        </div>

        <div class="form-group m-b-0">
            <div class="col-sm-12 text-center">
                <p><a href="{{ route('login') }}" class="text-primary m-l-5"><b>@lang('app.login')</b></a></p>
            </div>
        </div>

    </form>
@endsection
