@extends('layouts.admin')
@section('title')
    Users
@endsection
@section('content')
    <h1>USERS</h1>
    @if(session('status'))
        <div class="alert {{session('status')[1]}}"> <!-- class alert hier belangrijk -->
            <a href="" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
            <strong>Success!</strong> {{session('status')[0]}}
        </div>
    @endif
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button
                class="nav-link {{((session('status') && session('status')[0] == 'User deleted!') || !session('status')) ? 'active': ''}} bg-success text-white"
                id="active-tab" data-bs-toggle="tab" data-bs-target="#active-tab-pane" type="button" role="tab"
                aria-controls="active-tab-pane" aria-selected="true">Active users
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button
                class="nav-link {{(session('status') && session('status')[0] == 'User restored!') ? 'active': ''}} bg-danger text-white"
                id="deleted-tab" data-bs-toggle="tab" data-bs-target="#deleted-tab-pane" type="button" role="tab"
                aria-controls="deleted-tab-pane" aria-selected="false">Deleted users
            </button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div
            class="tab-pane fade {{((session('status') && session('status')[0] == 'User deleted!') || !session('status')) ? 'show active': ''}}"
            id="active-tab-pane" role="tabpanel" aria-labelledby="active-tab" tabindex="0">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Active</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Deleted</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($users as $user)
                    @if($loop->first)
                        Totaal aantal: {{ $users->total() }}
                        Aantal per pagina: {{$loop->count }}
                    @endif
                    {{--            <tr>--}}
                    {{--                <td>{{$user->user_id}}</td>--}}
                    {{--                <td>{{$user->photo_id}}</td>--}}
                    {{--                <td>{{$user->user_name}}</td>--}}
                    {{--                <td>{{$user->email}}</td>--}}
                    {{--                --}}{{--                    <td>{{$user->role_id?$user->role->name:'User without role'}}</td>--}}
                    {{--                <td>--}}
                    {{--                    @foreach($user->role_names as $role)--}}
                    {{--                        <span class="badge badge-pill badge-info">--}}
                    {{--                                {{$role}}--}}
                    {{--                            </span>--}}
                    {{--                    @endforeach--}}
                    {{--                </td>--}}
                    {{--                <td class="{{$user->is_active == 1?'bg-success':'bg-danger'}}">{{$user->is_active == 1?'Active':'Not Active'}}</td>--}}
                    {{--                <td>{{$user->user_created_at}}</td>--}}
                    {{--                <td>{{$user->user_updated_at}}</td>--}}
                    {{--            </tr>--}}
                    {{--        @endforeach--}}
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>
                            <a href="{{route('users.edit',$user->id)}}">
                                <img class="img-thumbnail" width="62" height="62"
                                     src="{{$user->photo ? asset($user->photo->file) : 'http://via.placeholder.com/62x62'}}"
                                     alt="{{$user->name}}">
                            </a>
                        </td>
                        <td><a class="text-decoration-none text-gray-900"
                               href="{{route('users.edit', $user->id)}}">{{$user->name}}</a></td>
                        <td>{{$user->email}}</td>
                        <td>
                            {{--                        @foreach($user_roles as $key2 => $value)--}}
                            {{--                            @if($value->user_id == $user->id)--}}
                            {{--                                @foreach($roles as $rolekey => $rolevalue)--}}
                            {{--                                    @if($rolekey == $value->role_id)--}}
                            @foreach($user->roles as $role)
                                <span class="badge badge-pill badge-info">
                                            {{$role->name}}
                                        </span>
                            @endforeach
                            {{--                        @endif--}}
                            {{--                    @endforeach--}}
                            {{--                 @endif--}}
                            {{--             @endforeach--}}
                        </td>
                        <td class="{{$user->is_active == 1?'bg-success':'bg-danger'}}">{{$user->is_active == 1?'Active':'Not active'}}</td>
                        <td>{{$user->created_at ? $user->created_at->diffForHumans() : ''}}</td>
                        <td>{{$user->updated_at ? $user->updated_at->diffForHumans() : ''}}</td>
                        <td>{{$user->deleted_at ? $user->deleted_at->diffForHumans() : ''}}
                        </td>
                        <!-- zou hier ook een toggle button kunnen maken -->
                        <td>
{{--                            @if($user->deleted_at != null)--}}
{{--                                <a class="btn btn-warning" href="{{ route('admin.restore', $user->id) }}">Restore--}}
{{--                                    user</a>--}}
{{--                            @else--}}
{{--                                {!! Form::open(['method'=>'DELETE', 'action'=>['\App\Http\Controllers\AdminUsersController@destroy', $user->id]]) !!}--}}
{{--                                <div class="form-group">--}}
{{--                                    {!! Form::submit('Delete User',['class' => 'btn btn-danger']) !!}--}}
{{--                                </div>--}}

{{--                                {!! Form::close() !!}<a href=""></a>--}}
{{--                            @endif--}}

                            <div class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown{{$user->id}}" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                    </svg>
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu shadow"
                                     aria-labelledby="userDropdown{{$user->id}}">
                                    <a class="dropdown-item" href="{{route('users.edit', $user->id)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    @if($user->deleted_at != null)
                                        <form action="{{route('admin.restore', $user->id)}}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button class="dropdown-item" type="submit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bootstrap-reboot" viewBox="0 0 16 16">
                                                    <path d="M1.161 8a6.84 6.84 0 1 0 6.842-6.84.58.58 0 1 1 0-1.16 8 8 0 1 1-6.556 3.412l-.663-.577a.58.58 0 0 1 .227-.997l2.52-.69a.58.58 0 0 1 .728.633l-.332 2.592a.58.58 0 0 1-.956.364l-.643-.56A6.812 6.812 0 0 0 1.16 8z"/>
                                                    <path d="M6.641 11.671V8.843h1.57l1.498 2.828h1.314L9.377 8.665c.897-.3 1.427-1.106 1.427-2.1 0-1.37-.943-2.246-2.456-2.246H5.5v7.352h1.141zm0-3.75V5.277h1.57c.881 0 1.416.499 1.416 1.32 0 .84-.504 1.324-1.386 1.324h-1.6z"/>
                                                </svg>
                                                Restore
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{route('users.destroy', $user->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item" type="submit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{$users->links()}}
        </div>
        <div
            class="tab-pane fade {{(session('status') && session('status')[0] == 'User restored!') ? 'show active': ''}}"
            id="deleted-tab-pane" role="tabpanel" aria-labelledby="deleted-tab" tabindex="0">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Active</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Deleted</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($trashedUsers as $user)
                    @if($loop->first)
                        Totaal aantal: {{ $users->total() }}
                        Aantal per pagina: {{$loop->count }}
                    @endif
                    {{--            <tr>--}}
                    {{--                <td>{{$user->user_id}}</td>--}}
                    {{--                <td>{{$user->photo_id}}</td>--}}
                    {{--                <td>{{$user->user_name}}</td>--}}
                    {{--                <td>{{$user->email}}</td>--}}
                    {{--                --}}{{--                    <td>{{$user->role_id?$user->role->name:'User without role'}}</td>--}}
                    {{--                <td>--}}
                    {{--                    @foreach($user->role_names as $role)--}}
                    {{--                        <span class="badge badge-pill badge-info">--}}
                    {{--                                {{$role}}--}}
                    {{--                            </span>--}}
                    {{--                    @endforeach--}}
                    {{--                </td>--}}
                    {{--                <td class="{{$user->is_active == 1?'bg-success':'bg-danger'}}">{{$user->is_active == 1?'Active':'Not Active'}}</td>--}}
                    {{--                <td>{{$user->user_created_at}}</td>--}}
                    {{--                <td>{{$user->user_updated_at}}</td>--}}
                    {{--            </tr>--}}
                    {{--        @endforeach--}}
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>
                            <a href="{{route('users.edit',$user->id)}}">
                                <img class="img-thumbnail" width="62" height="62"
                                     src="{{$user->photo ? asset($user->photo->file) : 'http://via.placeholder.com/62x62'}}"
                                     alt="{{$user->name}}">
                            </a>
                        </td>
                        <td><a class="text-decoration-none text-gray-900"
                               href="{{route('users.edit', $user->id)}}">{{$user->name}}</a></td>
                        <td>{{$user->email}}</td>
                        <td>
                            {{--                        @foreach($user_roles as $key2 => $value)--}}
                            {{--                            @if($value->user_id == $user->id)--}}
                            {{--                                @foreach($roles as $rolekey => $rolevalue)--}}
                            {{--                                    @if($rolekey == $value->role_id)--}}
                            @foreach($user->roles as $role)
                                <span class="badge badge-pill badge-info">
                                            {{$role->name}}
                                        </span>
                            @endforeach
                            {{--                        @endif--}}
                            {{--                    @endforeach--}}
                            {{--                 @endif--}}
                            {{--             @endforeach--}}
                        </td>
                        <td class="{{$user->is_active == 1?'bg-success':'bg-danger'}}">{{$user->is_active == 1?'Active':'Not active'}}</td>
                        <td>{{$user->created_at}}</td>
                        <td>{{$user->updated_at}}</td>
                        <td>{{$user->deleted_at}}
                        </td>
                        <!-- zou hier ook een toggle button kunnen maken -->
                        <td>
                            @if($user->deleted_at != null)
                                <!-- h-ref in een a-tag is altijd een GET methode, dus moet dit in web.php ook Route::get zijn -->
                                <form action="{{route('admin.restore', $user->id)}}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button class="btn btn-warning" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bootstrap-reboot" viewBox="0 0 16 16">
                                            <path d="M1.161 8a6.84 6.84 0 1 0 6.842-6.84.58.58 0 1 1 0-1.16 8 8 0 1 1-6.556 3.412l-.663-.577a.58.58 0 0 1 .227-.997l2.52-.69a.58.58 0 0 1 .728.633l-.332 2.592a.58.58 0 0 1-.956.364l-.643-.56A6.812 6.812 0 0 0 1.16 8z"/>
                                            <path d="M6.641 11.671V8.843h1.57l1.498 2.828h1.314L9.377 8.665c.897-.3 1.427-1.106 1.427-2.1 0-1.37-.943-2.246-2.456-2.246H5.5v7.352h1.141zm0-3.75V5.277h1.57c.881 0 1.416.499 1.416 1.32 0 .84-.504 1.324-1.386 1.324h-1.6z"/>
                                        </svg>
                                        Restore
                                    </button>
                                </form>
                            @else
                                {!! Form::open(['method'=>'DELETE', 'action'=>['\App\Http\Controllers\AdminUsersController@destroy', $user->id]]) !!}
                                <div class="form-group">
                                    {!! Form::submit('Delete User',['class' => 'btn btn-danger']) !!}
                                </div>

                                {!! Form::close() !!}<a href=""></a>
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            {{$trashedUsers->links()}}
        </div>
    </div>

@endsection
