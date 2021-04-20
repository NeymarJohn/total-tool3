@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="active">Search Results</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')
        <!-- .row -->
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">Search Here</h3>
                <form class="form-group" action="{{ route('admin.search.store') }}" novalidate method="POST" role="search">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group">
                        <input type="text"  name="search_key" class="form-control" placeholder="@lang('app.search')" value="{{ $searchKey }}">
                        <span class="input-group-btn"><button type="submit" class="btn waves-effect waves-light btn-info"><i class="fa fa-search"></i></button></span>
                    </div>
                </form>
                <h2 class="m-t-40">Search Result For "{{ $searchKey }}"</h2>
                <small>About {{ count($searchResults) }} result </small>
                <hr>
                <ul class="search-listing">
                    @forelse($searchResults as $result)
                    <li>
                        <h3><a href="{{ route($result->route_name, $result->searchable_id) }}">{{ $result->title }}</a></h3>
                        <a href="{{ route($result->route_name, $result->searchable_id) }}" class="search-links">{{ route($result->route_name, $result->searchable_id) }}</a>
                    </li>
                    @empty
                        <li>
                            No result found
                        </li>
                    @endforelse
                </ul>

            </div>
        </div>
    </div>
    <!-- /.row -->

@endsection