<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
   


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Chakra+Petch" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Teko:500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div class="forms-container" style="background-image: url({{asset('img/rider-bg.jpg')}});">
          <div id="registerForm" class="forms-style">
              <div class="login-form-inner">
                  <form class="form-signin" action="{{ route('register') }}" method="POST">
                      @csrf
                      <img class="mb-4" src="{{asset('img/logo2.png')}}" alt="" width="105" height="105">
                      <label for="name" class="sr-only">Name</label>
                          <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus placeholder="Name">
                          @if ($errors->has('name'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('name') }}</strong>
                              </span>
                          @endif
                          <label for="display_name" class="sr-only">Display Name</label>
                          <input id="display_name" type="text" class="form-control{{ $errors->has('display_name') ? ' is-invalid' : '' }}" name="display_name" value="{{ old('display_name') }}" required autofocus placeholder="Display name">
                          @if ($errors->has('display_name'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('display_name') }}</strong>
                              </span>
                          @endif
                          <label for="email" class="sr-only">Email address</label>
                          <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="Email address">
                          @if ($errors->has('email'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                          <label for="password" class="sr-only">Password</label>
                          <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">
                          @if ($errors->has('password'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('password') }}</strong>
                              </span>
                          @endif
                          <label for="password-confirm" class="sr-only">Confirm password</label>
                          <input id="password-confirm" type="password" class="form-control mb-4" name="password_confirmation" required placeholder="Confirm password">
                        
                          <button class="btn btn-blue btn-block mb-3" type="submit">{{ __('Sign up') }}</button>
                   </form>
              </div>
          </div>
        </div>
</div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>