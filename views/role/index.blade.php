@extends('user::role.template')

@section('page.head.menu')
    <div class="ui secondary menu">
        <div class="right item">
            <a href="{{route('role.create')}}" class="ui icon labeled button"><i class="add icon"></i> Add Role</a>
            <a href="{{route('permission.index')}}" class="ui icon labeled button"><i class="privacy icon"></i> Manage Permissions</a>
        </div>
    </div>
@endsection

@section('content')
<table class="ui selectable table">
    <thead>
        <tr>
            <th>Name</th>
            <th class="right aligned">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td class="collapsing right aligned">
                    <div class="ui compact text menu">
                        <a href="{{ route('role.edit', ['role' => $role]) }}" class="item">
                            <i class="pencil icon"></i>Edit
                        </a>

                        {!! new Baytek\Laravel\Menu\Button('Delete', [
                            'class' => 'item action',
                            'location' => 'role.destroy',
                            'method' => 'delete',
                            'model' => $role,
                            'prepend' => '<i class="delete icon"></i>',
                            'type' => 'route',
                            'confirm' => 'Are you sure you want to delete this role?'
                        ]) !!}
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- pagination start -->
<div class="ui hidden divider"></div>
<!-- pagination end -->

@endsection
