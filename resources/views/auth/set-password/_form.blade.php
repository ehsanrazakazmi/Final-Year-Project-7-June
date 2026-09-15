{{--
    Shared body for the "choose your password" screen. Each portal wraps this in
    its own layout so an invited user sees the template for their role.
--}}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-1">Set your password</h4>
                    <p class="mb-0 text-sm text-muted">
                        Welcome, {{ $user->name }}. Choose a password to activate your
                        {{ $user->roleName() ?? 'account' }} account.
                    </p>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.set.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group mb-3">
                            <label for="email" class="form-control-label">{{ __('Email') }}</label>
                            <input id="email" name="email" type="email" class="form-control"
                                   value="{{ old('email', $email) }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-control-label">{{ __('New password') }}</label>
                            <input id="password" name="password" type="password" class="form-control"
                                   placeholder="At least 8 characters" required autofocus autocomplete="new-password">
                        </div>

                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-control-label">{{ __('Confirm password') }}</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                   class="form-control" placeholder="Repeat your password" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('Set password and continue') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
