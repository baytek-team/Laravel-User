@extends('user::permission.template')

@section('page.head.menu')
    <div class="ui secondary menu">
        <div class="right item">
            <a href="{{route('permission.create')}}" class="ui icon labeled button"><i class="add icon"></i> Add permission</a>
            <a href="" class="ui icon labeled button"><i class="privacy icon"></i> Manage Permissions</a>
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
        @foreach ($permissions as $permission)
            <tr>
                <td>{{ $permission->name }}</td>
                <td class="collapsing right aligned">
                    <div class="ui compact text menu">
                        <a href="{{ route('permission.edit', ['permission' => $permission]) }}" class="item">
                            <i class="pencil icon"></i>Edit
                        </a>

                        {!! new Baytek\Laravel\Menu\Button('Delete', [
                            'class' => 'item action',
                            'location' => 'permission.destroy',
                            'method' => 'delete',
                            'model' => $permission,
                            'prepend' => '<i class="delete icon"></i>',
                            'type' => 'route',
                            'confirm' => 'Are you sure you want to delete this permission?'
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
