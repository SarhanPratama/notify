@extends('layouts.master')

@section('content')
<div class="container">
    <form action="{{ route('akses-role.update', $role->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <h1>Role : {{ $role->name  }}</h1>
        </div>

        <div class="mb-3">
            <label>Permissions</label><br>
            @foreach ($permissions as $permission)
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                {{ $permission->name }} <br>
            @endforeach
        </div>


        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
