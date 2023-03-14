@extends('layouts.admin')
@section('title')
    Posts
@endsection
@section('content')
    <div class="d-flex justify-content-between shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="d-flex align-items-center">
            <p class="rounded bg-primary m-0 d-flex align-self-center p-2 text-white mr-3">
                {{ $posts->total() }}
            </p>
            <h1 class="mb-0 mr-3">| Posts</h1>
            <div class="d-flex align-items-center">
                @if(request('search'))
                    <p class="mb-0 mr-2">Filters: </p>

                    <a class="badge badge-pill badge-info">
                        {{request('search')}}
                    </a>

                    <a href="{{route('posts.index')}}" class="rounded bg-primary m-0 d-flex align-self-center p-2 text-white mx-3">
                        Clear all filters
                    </a>
                @endif
            </div>
        </div>

        <div class="d-flex">
            <a class="btn btn-primary mx1 my-2 rounded-pill" href="{{ route('posts.index') }}">All posts</a>
            <a class="btn btn-primary mx-1 my-2 rounded-pill" href="{{ route('posts.create') }}">Create post</a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success"> <!-- class alert hier belangrijk -->
            <a href="" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
            <strong>Success!</strong> {{session('status')}}
        </div>
    @endif

    <table class="table table-striped shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <thead>
        <tr>
            <th>Id</th>
            <th>Photo</th>
            <th>Author</th>
            <th>Category</th>
            <th>Title</th>
            <th>Body</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Deleted</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($posts as $post)
            <tr>
                <td>{{$post->id }}</td>
                <td>
                    <img class="img-fluid" src="{{$post->photo_id ? asset($post->photo->file): 'http://via.placeholder.com/62x62'}}" alt="">
                </td>
                <td>
                    <!-- check if user still exists (isn't soft deleted) -->
                    @if($post->user)
                    <a href="{{route('authors', $post->user->name)}}">
                        {{$post->user_id ? $post->user->name : 'no name'}}
                    </a>
                    @else
                        <p class="text-danger">{{$post->user()->withTrashed()->first() ? $post->user()->withTrashed()->first()->name : "no name"}}</p>
                    @endif
                </td>
                <td>
                    @foreach($post->categories as $cateogry)
                        <span class="badge badge-pill badge-info">
                            {{$cateogry->name}}
                        </span>
                    @endforeach
                </td>
                <td>{{$post->title}}</td>
                <td>{{Str::limit($post->body, 100, '...')}}</td>
                <td>{{$post->created_at ? $post->created_at->diffForHumans() : null}}</td>
                <td>{{$post->updated_at ? $post->updated_at ->diffForHumans() : ''}}</td>
                <td>{{$post->deleted_at ? $post->deleted_at->diffForHumans() : ''}}</td>
                <td>
                    <div class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown{{$post->id}}" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            </svg>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu shadow"
                             aria-labelledby="userDropdown{{$post->id}}">
                            <a class="dropdown-item" href="{{route('posts.show', $post->slug)}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                                Show
                            </a>
                            <a class="dropdown-item" href="{{route('posts.edit', $post->id)}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                </svg>
                                Edit
                            </a>
                            <div class="dropdown-divider"></div>
                            @if($post->deleted_at != null)
                                <form action="{{route('admin.postrestore', $post->id)}}" method="POST">
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
                                <form action="{{route('posts.destroy', $post->id)}}" method="POST">
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
    <!-- is in principe niet nodig om deze extra request te doen (gebeurt al in controller), maar voor de zekerheid -->
    {{$posts->appends(['search'=>Request::get('search'), 'fields' => Request::get('fields')])->links()}}
{{--    {{$posts->links()}}--}}
@endsection
