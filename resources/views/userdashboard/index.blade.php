@extends('layouts.app')

@section('content')
<div class="container">
<next-race race="{{$next}}" logo="{{$logo}}" event="{{$next->event}}"></next-race>
</div>
@endsection
