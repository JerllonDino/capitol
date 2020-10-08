@extends('nav')

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $base['permissioncategory']->name }}</dd>
            <dt>Description</dt>
            <dd>{{ $base['permissioncategory']->description }}</dd>
        </dl>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12">
        <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Read</th>
                        <th>Write</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($base['group_permissions'] as $group_permission)
                    <tr>
                        <td>
                            {{ $group_permission->description }}
                        </td>
                        <td>
                            @if ($group_permission->value & $base['can_read'])
                            <i class="fa fa-check-square-o"></i>
                            @else
                            <i class="fa fa-square-o"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group_permission->value & $base['can_write'])
                            <i class="fa fa-check-square-o"></i>
                            @else
                            <i class="fa fa-square-o"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group_permission->value & $base['can_delete'])
                            <i class="fa fa-check-square-o"></i>
                            @else
                            <i class="fa fa-square-o"></i>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</div>

@if ( Session::get('permission')['group'] & $base['can_write'] )
<div class="row">
    <div class="form-group col-sm-12">
        <a href="{{ route('group.permission.edit', ['group_id' =>$base['group_id'], 'permissioncategory_id' =>$base['permissioncategory_id']]) }}" class="btn btn-info datatable-btn">
            Update
        </a>
    </div>
</div>
@endif

@endsection