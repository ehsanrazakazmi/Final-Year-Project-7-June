@extends('layouts.user_type.auth')

@section('content')
<div class="row">
    <div class="col-lg-7 col-md-9 mx-auto">
        <div class="card mb-4 mx-4 p-3">
            <div class="card-header pb-0">
                <h5 class="mb-1" style="font-size: 22px">Add User</h5>
                <p class="text-sm text-secondary mb-0">
                    The user will receive an email invitation and choose their own password.
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

                <form method="POST" action="{{ route('adminpanel.users.store') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name" class="form-control-label">{{ __('Full Name') }}</label>
                        <input id="name" name="name" type="text" class="form-control"
                               value="{{ old('name') }}" placeholder="Name" required autofocus>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-control-label">{{ __('Email') }}</label>
                        <input id="email" name="email" type="email" class="form-control"
                               value="{{ old('email') }}" placeholder="name@example.com" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="role" class="form-control-label">{{ __('Role') }}</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="">Select a role</option>
                            @foreach ($roles as $option)
                                <option value="{{ $option }}" @selected(old('role') === $option)>
                                    {{ ucfirst($option) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- A technician's dashboard lists shipped orders whose items
                         match this category. Without it the dashboard is always
                         empty, because SQL never matches `category_id = NULL`. --}}
                    <div class="form-group mb-4" id="category-field"
                         @style(['display: none' => old('role') !== 'technician'])>
                        <label for="category_id" class="form-control-label">{{ __('Category') }}</label>
                        <select id="category_id" name="category_id" class="form-control">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-secondary">Only used for technicians &mdash; it decides which jobs they see.</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('adminpanel.users.index') }}" class="btn btn-link text-dark mb-0">Cancel</a>
                        <button type="submit" class="btn bg-gradient-primary mb-0">Create and send invitation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Show the Category field only when the selected role is technician.
    (function () {
        var role = document.getElementById('role');
        var field = document.getElementById('category-field');
        if (!role || !field) return;
        function sync() { field.style.display = role.value === 'technician' ? '' : 'none'; }
        role.addEventListener('change', sync);
        sync();
    })();
</script>
@endsection
