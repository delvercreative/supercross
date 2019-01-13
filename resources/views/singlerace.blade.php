@extends('layouts.app')

@section('content')
<div class="container">
    @guest
    <div class="card text-center">
        <div class="card-header">
          Login Required
        </div>
        <div class="card-body">
          <h5 class="card-title">Login or Create an Account to View Races</h5>
          <p class="card-text">Click below to either login or create a new account</p>
          <a class="btn btn-success" href="{{ route('login') }}">{{ __('Login') }}</a>
            @if (Route::has('register'))
                <a class="btn btn-primary" href="{{ route('register') }}">{{ __('Register') }}</a>
            @endif
        </div>
      </div>
      @else

    <single-race user="{{$user->id}}" raceid="{{$race->id}}" status="{{$race->status}}"></single-race>
  @endguest
</div>
@endsection