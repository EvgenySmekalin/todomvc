@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>User's lists</h3>
            @if (empty($userLists))
                <p>No any lists</p>
            @else
                <div class="list-group">
                @foreach ($userLists as $userList)
                    <a href="{{ url('edit') . '/'. $userList->id . '/all' }}" class="list-group-item">{{ $userList->name }}</a>
                @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
