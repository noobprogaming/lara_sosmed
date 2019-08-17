@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 row">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="col-md-3">
                <div class="card">
                    <div class="form-group text-center my-3">
                        <img src='{{ asset('data_file/'.$user_describe->id.'') }}' class="img-profile">
                        <div class="uname mt-3">{{ $user_describe->name }}</div>
                        <hr>
                        <div class="form-inline">
                            <div class="col">
                                <a href="message?q={{ Auth::user()->id }}" data-toggle="modal"
                                    data-target="#followingModal">
                                    Following<br>
                                    {{ $friend_following_c }}
                                </a>
                            </div>
                            <div class="col">
                                <a href="message?q={{ Auth::user()->id }}" data-toggle="modal"
                                    data-target="#followersModal">
                                    Followers<br>
                                    {{ $friend_followers_c }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <a href="profile?q={{ Auth::user()->id }}">
                                <div class="fa fa-address-book mx-3"></div> {{ $user_describe->name }}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="profile?q={{ Auth::user()->id }}">
                                <div class="fa fa-envelope mx-3"></div> {{ $user_describe->email }}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="profile?q={{ Auth::user()->id }}">
                                <div class="fa fa-book mx-3"></div> {{ $user_describe->created_at }}
                            </a>
                        </li>
                        @if (Auth::user()->id !== $user_describe->id)
                        <li class="list-group-item text-center">
                            <a href="message?q={{ $user_describe->id }}">
                                Send Message
                            </a>
                        </li>
                        @endif
                    </ul>

                    @if (Auth::user()->id !== $user_describe->id)
                    <div class="card-body">
                            <a href="#" class="card-link">
                                <form action="profile/store_follow" method="post" class="mt-3">
                                    {{ csrf_field() }}
    
                                    <input type="hidden" class="form-control @error('id') is-invalid @enderror"
                                        name="user_id" value="{{ Auth::user()->id }}" required autocomplete="user_id"
                                        autofocus>
    
                                    <input type="hidden" class="form-control @error('friend_id') is-invalid @enderror"
                                        name="friend_id" value="{{ $user_describe->id }}" required autocomplete="friend_id"
                                        autofocus>
    
                                    <button type="submit" class="btn {{ $btn }}" >
                                        {{ $follow_c }}
                                    </button>
                                </form>
                            </a>
                            <br>{{ $follow_me }}
                        </div>
                    @endif

                    <div class="modal fade" id="followingModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">{{ $user_describe->name }}'s followers</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="mx-3 my-3">
                                        @foreach ($friend_following as $n)
                                        <a href="profile?q={{ $n->id }}">
                                            <div class="form-inline my-2">
                                                <img src='{{ asset('data_file/'.$n->id.'') }}' class="profile-sm">
                                                <div class="ml-3 uname">{{ $n->name }}</div>
                                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-danger"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="followersModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">{{ $user_describe->name }}'s followers</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="mx-3 my-3">
                                        @foreach ($friend_followers as $n)
                                        <a href="profile?q={{ $n->id }}">
                                            <div class="form-inline my-2">
                                                <img src='{{ asset('data_file/'.$n->id.'') }}' class="profile-sm">
                                                <div class="ml-3 uname">{{ $n->name }}</div>
                                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-danger"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Create Post</div>
                    <div class="row">
                        <table class="w-100 mx-4 my-2">
                            <tr>
                                <td rowspan="2">
                                    <img src="{{ asset('data_file/'.Auth::user()->id.'') }}" class="profile-sm mx-3">
                                </td>
                                <td class="w-100">
                                    <form action="store_content" method="post" enctype="multipart/form-data" class="mt-3">
                                        {{ csrf_field() }}

                                        <input type="hidden" class="form-control @error('id') is-invalid @enderror"
                                            name="id" value="{{ Auth::user()->id }}" required autocomplete="id"
                                            autofocus>

                                        <input type="text" class="form-control @error('content') is-invalid @enderror"
                                            name="content" value="{{ old('content') }}" required autocomplete="content"
                                            autofocus placeholder="Send message to {{ $user_describe->name }}">

                                        @error('content')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </form>
                                </td>
                                <td rowspan="2">
                                    <div class="dropdown">
                                        <div class="dropbtn fa fa-ellipsis-v silver">
                                            <div class="dropdown-content">
                                                <a class="show-file">Upload File</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="font-size: 12px;"></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="load">
                    @foreach($user_profile as $n)
                    <div class="card my-4">
                        <div class="row">
                            <table class="w-100 mx-4 my-2">
                                <tr>
                                    <td rowspan="2">
                                        <img src="{{ asset("data_file/".$n->id."") }}" class="profile-sm mx-3">
                                    </td>
                                    <td class="w-100">
                                        <a href="profile?q={{ $n->id }}">
                                            <div class="mt-3 uname">{{ $n->name }}</div>
                                        </a>
                                    </td>
                                    <td rowspan="2">
                                        @if ($n->id == Auth::user()->id)
                                        <div class="dropdown">
                                            <div class="dropbtn fa fa-ellipsis-v silver">
                                                <div class="dropdown-content">
                                                    <a data-toggle="modal" data-target="#delModal">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="font-size: 12px;">{{ Carbon\Carbon::parse($n->time)->diffForHumans() }}</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-content">
                            @if (!empty($n->file))
                                <img src="{{ asset("data_file/".$n->file."") }}" class="w-100">
                            @endif
                            <div class="mx-3 my-3">
                                {{ $n->content }}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-inline">
                                {{ $n->love }}
                                <form action="store_love" method="post">
                                    {{ csrf_field() }}
    
                                    <input type="hidden" class="form-control @error('content_id') is-invalid @enderror"
                                        name="content_id" value="{{ $n->content_id }}" required>
    
                                    <input type="hidden" class="form-control @error('id') is-invalid @enderror"
                                        name="id" value="{{ Auth::user()->id }}" required>
    
                                    <button type="submit" class="fa fa-thumbs-up transparent" >
                                    </button>
                                </form>
                            
                                {{ $n->comment }}
                                <a href="content?q={{ $n->content_id }}">
                                    <button class="fa fa-book transparent"></button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="delModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Are you sure to delete?</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="mx-3 my-3">
                                        <a href="home/delete/{{ $n->content_id }}" class="fg-red">Delete</a>
                                    </div>
                                    <div class="card-content">
                                        @if (!empty($n->file))
                                            <img src="{{ asset("data_file/".$n->file."") }}" class="w-100">
                                        @endif
                                        <div class="mx-3 my-3">
                                            {{ $n->content }}
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-danger"
                                        data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{ $user_profile->links() }}
            </div>
            <div class="col-md-3">
                    <div>
                        <h6>Informasi</h6>
                        <hr>
                        <div class="scrollable-menu">
                            <div id="loading">
                                <img src="{{ asset('data_file/load.gif') }}" class="load">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function () {
            setInterval(function () {
                $("#loading").load('api/json/home');
            }, 1000);   
        });
    </script>
    @endsection
    