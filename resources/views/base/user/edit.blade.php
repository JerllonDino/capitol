@extends('nav')

@section('content')
<div class="row">
    {{ Form::model($result, ['method' => 'PATCH', 'action' => ['UserController@update', $result -> id] ]) }}
        <div class="form-group col-sm-12">
            <label for="realname">Real Name</label>
            <input type="text" class="form-control" id="realname" name="realname" value="{{$result -> realname}}" autofocus required>
        </div>
        <div class="form-group col-sm-12">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{$result -> username}}" readonly>
        </div>
        <div class="form-group col-sm-12">
            <label for="position">Position</label>
            <input type="text" class="form-control" id="position" name="position" value="{{$result -> position}}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="password">New Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group col-sm-6">
            <label for="retype_password">Retype New Password</label>
            <input type="password" class="form-control" id="retype_password" name="retype_password">
        </div>
        <div class="form-group col-sm-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $result -> email}}">
        </div>
        <div class="form-group col-sm-6">
            <label for="group">Group</label>
            <select class="form-control" id="group" name="group" required>
            <option value = "{{$grp -> id}}" hidden>{{$grp -> name}}</option>
                @foreach($base['groups'] as $group)
                    @if (Request::old('group') == $group->id)
                    <option title="{{ $group->description }}" value="{{ $group->id }}" selected>{{ $group->name }}</option>
                    @else
                    <option title="{{ $group->description }}" value="{{ $group->id }}">{{ $group->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" class="btn btn-success" id="submit" value="Update">
        </div>
    {{ Form::close() }}
</div>
@endsection
