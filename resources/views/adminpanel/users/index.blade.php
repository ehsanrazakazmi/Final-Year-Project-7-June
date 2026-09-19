@extends('layouts.user_type.auth')

@section('content')
<div>
    {{-- Success flashes render once, globally, in layouts/app.blade.php. --}}

    @if ($errors->any())
        <div class="alert alert-danger mx-4" role="alert">
            <span class="text-white">{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 p-3">
                <div class="card-header pb-0">
                    <div class="mb-3 d-flex flex-row justify-content-between align-items-center">
                        <h5 class="mb-0" style="font-size: 24px">All Users</h5>
                        <a href="{{ route('adminpanel.users.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                            +&nbsp;Add User
                        </a>
                    </div>

                    <form method="GET" action="{{ route('adminpanel.users.index') }}" class="row g-2 mb-3">
                        <div class="col-sm-5">
                            <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm"
                                   placeholder="Search name or email">
                        </div>
                        <div class="col-sm-4">
                            <select name="role" class="form-control form-control-sm">
                                <option value="">All roles</option>
                                @foreach ($roles as $option)
                                    <option value="{{ $option }}" @selected($role === $option)>
                                        {{ ucfirst($option) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-sm bg-gradient-secondary mb-0 w-100">Filter</button>
                        </div>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $user->id }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ $user->name }}</p></td>
                                        <td><p class="text-xs text-secondary mb-0">{{ $user->email }}</p></td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-info">
                                                {{ ucfirst($user->roleName() ?? 'none') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($user->email_verified_at)
                                                <span class="badge badge-sm bg-gradient-success">Active</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-warning">Invitation pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ $user->created_at?->format('d M Y') ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('adminpanel.users.edit', $user) }}"
                                               class="btn btn-link text-dark px-2 mb-0">Edit</a>

                                            @unless ($user->email_verified_at)
                                                <form method="POST" class="d-inline"
                                                      action="{{ route('adminpanel.users.resend', $user) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-info px-2 mb-0">
                                                        Resend invite
                                                    </button>
                                                </form>
                                            @endunless

                                            <form method="POST" class="d-inline"
                                                  action="{{ route('adminpanel.users.destroy', $user) }}"
                                                  onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger px-2 mb-0">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-sm py-4">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 pt-3">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
