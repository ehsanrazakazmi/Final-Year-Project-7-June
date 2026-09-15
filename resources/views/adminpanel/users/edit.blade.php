@extends('layouts.user_type.auth')

@section('content')
<div class="row">
    <div class="col-lg-7 col-md-9 mx-auto">
        <div class="card mb-4 mx-4 p-3">
            <div class="card-header pb-0">
                <h5 class="mb-1" style="font-size: 22px">Edit User</h5>
                <p class="text-sm text-secondary mb-0">
                    Passwords are chosen by the user and cannot be changed here.
                </p>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0 ps-3 text-white">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('adminpanel.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="name" class="form-control-label">{{ __('Full Name') }}</label>
                        <input id="name" name="name" type="text" class="form-control"
                               value="{{ old('name', $user->name) }}" required autofocus>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-control-label">{{ __('Email') }}</label>
                        <input id="email" name="email" type="email" class="form-control"
                               value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="role" class="form-control-label">{{ __('Role') }}</label>
                        <select id="role" name="role" class="form-control" required>
                            @foreach ($roles as $option)
                                <option value="{{ $option }}" @selected(old('role', $user->roleName()) === $option)>
                                    {{ ucfirst($option) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('adminpanel.users.index') }}" class="btn btn-link text-dark mb-0">Cancel</a>
                        <button type="submit" class="btn bg-gradient-primary mb-0">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
