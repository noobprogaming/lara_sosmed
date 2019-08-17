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
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <a href="profile?q={{ Auth::user()->id }}">
                                <div class="form-inline my-3">
                                    <img src="{{ asset('data_file/'.Auth::user()->id.'') }}" class="profile-sm">
                                    <div class="ml-3 uname">{{ Auth::user()->name }}</div>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="home">
                                <div class="fa fa-home mx-3"></div> Home
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="profile?q={{ Auth::user()->id }}">
                                <div class="fa fa-address-book mx-3"></div> Profile
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="message?q={{ Auth::user()->id }}">
                                <div class="fa fa-envelope mx-3"></div> Message
                            </a>
                        </li>
                    </ul>
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
                                            autofocus placeholder="What's your mind?">

                                        <input type="file" name="file" class="input-file my-2">

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
                    @foreach($user as $n)
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
                {{ $user->links() }}
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
        setTimeout(function () {
            scroll_down();
        }, 1050);        
        
        $('.input-file').hide();
        $('.show-file').on('click', function () {
            $('.input-file').slideDown();
        });
    });

    function scroll_down() {
        setTimeout(function () {
            var messageBody = document.querySelector('#conv');
            messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
        }, 500);
    };
</script>
@endsection

