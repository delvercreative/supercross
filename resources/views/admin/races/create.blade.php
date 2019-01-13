@extends('layouts.admin')

@section('admin-content')
<div class="wrapper">
  <div class="page-title">Add New Race</div>
  <div class="form-create">
      <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="form-group">
              <label for="name">Race Name</label>
              <input type="text" class="form-control" id="name" placeholder="Ex. 250 Main">
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
      </form>
  </div>
</div>
@endsection