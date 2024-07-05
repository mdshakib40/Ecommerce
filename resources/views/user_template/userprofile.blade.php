@extends('user_template.layouts.template')
@section('main-content')
<h2>WelCome{{ Auth::user()->name }}</h2>
@endsection