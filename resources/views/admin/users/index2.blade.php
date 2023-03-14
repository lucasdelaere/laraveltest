<!-- 'x-' zoekt naar directory components -->
<x-admin2>
    <x-heading heading="Alle Users"></x-heading>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{((session('status') && session('status')[0] == 'User deleted!') || !session('status')) ? 'active': ''}} bg-success text-white" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-tab-pane" type="button" role="tab" aria-controls="active-tab-pane" aria-selected="true">Active users</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{(session('status') && session('status')[0] == 'User restored!') ? 'active': ''}} bg-danger text-white" id="deleted-tab" data-bs-toggle="tab" data-bs-target="#deleted-tab-pane" type="button" role="tab" aria-controls="deleted-tab-pane" aria-selected="false">Deleted users</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade {{((session('status') && session('status')[0] == 'User deleted!') || !session('status')) ? 'show active': ''}}" id="active-tab-pane" role="tabpanel" aria-labelledby="active-tab" tabindex="0">
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
                        Aantal: {{$loop->count}}
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
                                <img class="img-thumbnail" width="62" height="62" src="{{$user->photo ? asset($user->photo->file) : 'http://via.placeholder.com/62x62'}}" alt="{{$user->name}}">
                            </a>
                        </td>
                        <td><a class="text-decoration-none text-gray-900" href="{{route('users.edit', $user->id)}}">{{$user->name}}</a></td>
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
                                <a class="btn btn-warning" href="{{ route('admin.restore', $user->id) }}">Restore user</a>
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
            {{$users->links()}}
        </div>
        <div class="tab-pane fade {{(session('status') && session('status')[0] == 'User restored!') ? 'show active': ''}}" id="deleted-tab-pane" role="tabpanel" aria-labelledby="deleted-tab" tabindex="0">
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
                        Aantal: {{$loop->count}}
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
                                <img class="img-thumbnail" width="62" height="62" src="{{$user->photo ? asset($user->photo->file) : 'http://via.placeholder.com/62x62'}}" alt="{{$user->name}}">
                            </a>
                        </td>
                        <td><a class="text-decoration-none text-gray-900" href="{{route('users.edit', $user->id)}}">{{$user->name}}</a></td>
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
                                <a class="btn btn-warning" href="{{ route('admin.restore', $user->id) }}">Restore user</a>
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
</x-admin2>
