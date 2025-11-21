@extends('layouts.app') 
@section('content')
<div class="container">
    <h2>Edit User</h2>

    <form action="{{ route('super.update.user', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="firstname" value="{{ $user->firstname }}" placeholder="Firstname" required>
        <input type="text" name="lastname" value="{{ $user->lastname }}" placeholder="Lastname" required>
        <input type="text" name="name" value="{{ $user->name }}" placeholder="Name" required>
        <input type="text" name="username" value="{{ $user->username }}" placeholder="Username" required>
        <input type="email" name="email" value="{{ $user->email }}" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password (leave blank to keep)">
        <button type="submit">Update</button>
    </form>
</div>
@endsection
