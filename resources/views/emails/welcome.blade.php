<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to {{ config('app.name') }}</title>
</head>
<body>
    <h1>Welcome {{ $user->name }}</h1>

    {{-- `role` is an integer column, so a strict === against a string never matched. --}}
    <h2>You are successfully registered as a {{ ucfirst($user->roleName() ?? 'user') }}</h2>

    <p>Your Email is: &nbsp; {{ $user->email }}</p>
    <p>Your Name is: {{ $user->name }}</p>
</body>
</html>
