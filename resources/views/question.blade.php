@extends('layouts.admin')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/jquery.dataTables.css') }}">
@endsection
@section('main-content')

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">{{ __('Questions') }}</h1>
<div class="row">
    <!-- Earnings (Monthly) Card Example -->
    @if(isset($response_data))
    @if($response_data)
    @foreach($response_data as $category)
    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="card" style="margin: 5px;">
            <img style="max-height: 200px;" src="{{$category['image_url']}}" class="card-img-top" >
            <div class="card-body">
                <h4 class="card-title">{{ $category['name'] }}</h4>
                <p class="card-text">Total questions {{ $category['totalRecords'] }}</p>
                <a href="/question/{{$category['id']}}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Manage Questions</a>
            </div>
            
        </div>
    </div>
    @endforeach
    @else
    <div class="col-lg-5 col-md-3 col-sm-6">
        <div class="card" style="margin: 5px;">
            <div class="card-body">
                <h4 class="card-title">There is no category avaliable</h4>
                <a href="/category" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-th-list"></i> Manage Categories</a>
            </div>  
        </div>
    </div>
    @endif
    @endif
    
    
</div>



@section('scripts')

@endsection
@endsection
