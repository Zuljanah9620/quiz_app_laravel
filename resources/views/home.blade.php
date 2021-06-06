@extends('layouts.admin')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/jquery.dataTables.css') }}">
@endsection
@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Dashboard') }}</h1>

    @if (session('success'))
    <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    
       
   <div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-lg font-weight-bold text-dark text-uppercase mb-1">Profile</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <a href="{{ route('profile') }}" class="btn btn-dark"><i class="fas fa-edit"></i> Edit</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-fw fa-user fa-3x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-lg font-weight-bold text-dark text-uppercase mb-1">Categories {{ $widget['categories'] }}</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <a href="{{ route('category') }}" class="btn btn-dark btn-block"><i class="fas fa-edit"></i> Edit</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-fw fa-th-list fa-3x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-lg font-weight-bold text-dark text-uppercase mb-1">Questions {{ $widget['questions'] }}</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <a href="{{ route('question.index') }}" class="btn btn-dark btn-block"><i class="fas fa-edit"></i> Edit</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-question fa-3x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-lg font-weight-bold text-dark text-uppercase mb-1">Reports <span class="text-danger">*{{ $widget['reports'] }}</span></div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <a href="{{ route('report') }}" class="btn btn-dark btn-block"><i class="fas fa-edit"></i> Edit</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-question-circle fa-3x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-lg font-weight-bold text-dark text-uppercase mb-1">Notification</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <a href="{{ route('notification.index') }}" class="btn btn-dark btn-block"><i class="fas fa-edit"></i> Edit</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-fw fa-bell fa-3x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    @if(Auth::user()->role == "admin")
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-lg font-weight-bold text-dark text-uppercase mb-1">Panel Users {{$widget['users']}}</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <a href="{{ route('user') }}" class="btn btn-dark btn-block"><i class="fas fa-edit"></i> Edit</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-lock fa-3x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-lg font-weight-bold text-dark text-uppercase mb-1">App Config</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <a href="{{ route('appconfig') }}" class="btn btn-dark btn-block"><i class="fas fa-edit"></i> Edit</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-cog fa-3x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

</div>
        
@section('scripts')

@endsection
@endsection
