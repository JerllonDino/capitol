@extends('nav')

@section('content')
<div class="row">
    {{ Form::open([ 'route' => ['group.permission.update', 'group_id' => $base['group_id'], 'permissioncategory_id' => $base['permissioncategory']->id], 'method' => 'put' ]) }}
        <div class="col-lg-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ $base['permissioncategory']->name }}</th>
                        <th><button type="button" id="toggle-read" class="btn btn-sm btn-info toggle"><i class="fa fa-check-square-o"></i></button></th>
                        <th><button type="button" id="toggle-write" class="btn btn-sm btn-info toggle"><i class="fa fa-check-square-o"></i></button></th>
                        <th><button type="button" id="toggle-delete" class="btn btn-sm btn-info toggle"><i class="fa fa-check-square-o"></i></button></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($base['group_permissions'] as $group_permission)
                    <tr>
                        <td>
                            {{ $group_permission->description }}
                        </td>
                        <td>
                            <label class="checkbox-inline">
                                @if ($group_permission->value & $base['can_read'])
                                <input type="checkbox" class="read" name="{{ $group_permission->permission_id }}[]" value="{{ $base['can_read'] }}" checked>
                                @else
                                <input type="checkbox" class="read" name="{{ $group_permission->permission_id }}[]" value="{{ $base['can_read'] }}">
                                @endif
                                Read
                            </label>
                        </td>
                        <td>
                            <label class="checkbox-inline">
                                @if ($group_permission->value & $base['can_write'])
                                <input type="checkbox" class="write" name="{{ $group_permission->permission_id }}[]" value="{{ $base['can_write'] }}" checked>
                                @else
                                <input type="checkbox" class="write" name="{{ $group_permission->permission_id }}[]" value="{{ $base['can_write'] }}">
                                @endif
                                Write
                            </label>
                        </td>
                        <td>
                            <label class="checkbox-inline">
                                @if ($group_permission->value & $base['can_delete'])
                                <input type="checkbox" class="delete" name="{{ $group_permission->permission_id }}[]" value="{{ $base['can_delete'] }}" checked>
                                @else
                                <input type="checkbox" class="delete" name="{{ $group_permission->permission_id }}[]" value="{{ $base['can_delete'] }}">
                                @endif
                                Delete
                            </label>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" class="btn btn-success" id="submit" value="Save">
        </div>
    {{ Form::close() }}
</div>
@endsection

@section('js')
<script>
$('.toggle').click( function() {
    switch (this.id) {
        case 'toggle-read':
            $('.read').prop('checked', !$('.read').prop('checked'));
            break;
        case 'toggle-write':
            $('.write').prop('checked', !$('.write').prop('checked'));
            break;
        case 'toggle-delete':
            $('.delete').prop('checked', !$('.delete').prop('checked'));
            break;
    }
});
</script>
@endsection