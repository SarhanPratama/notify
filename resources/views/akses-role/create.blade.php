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

        <div>
            <label for="select2Multiple">Multiple-Select Boxes (pillbox)</label>
            <select class="select2-multiple form-control" name="states[]" multiple="multiple"
              id="select2Multiple">
              <option value="">Select</option>
              <option value="Aceh">Aceh</option>
              <option value="Sumatra Utara">Sumatra Utara</option>
              <option value="Sumatra Barat">Sumatra Barat</option>
              <option value="Riau">Riau</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
