{{--
    The invitee is still a guest, and layouts/app.blade.php only renders the
    'auth' section for authenticated users - hence the guest wrapper here.
--}}
@extends('layouts.user_type.guest')

@section('title', 'Set your password')

@section('content')
    @include('auth.set-password._form')
@endsection
