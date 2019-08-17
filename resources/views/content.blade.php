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
                <div id="load">
                    <div class="card">
                        <div class="row">
                            <table class="w-100 mx-4 my-2">
                                <tr>
                                    <td rowspan="2">
                                        <img src="{{ asset("data_file/".$content->id."") }}" class="profile-sm mx-3">
                                    </td>
                                    <td class="w-100">
                                        <a href="profile?q={{ $content->id }}">
                                            <div class="mt-3 uname">{{ $content->name }}</div>
                                        </a>
                                    </td>
                                    <td rowspan="2">
                                        @if ($content->id == Auth::user()->id)
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
                                        <p style="font-size: 12px;">{{ Carbon\Carbon::parse($content->time)->diffForHumans() }}</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-content">
                            @if (!empty($content->file))
                                <img src="{{ asset("data_file/".$content->file."") }}" class="w-100">
                            @endif
                            <div class="mx-3 my-3">
                                {{ $content->content }}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-inline">
                                {{ $content->love }}
                                <form action="store_love" method="post">
                                    {{ csrf_field() }}
    
                                    <input type="hidden" class="form-control @error('content_id') is-invalid @enderror"
                                        name="content_id" value="{{ $content->content_id }}" required>
    
                                    <input type="hidden" class="form-control @error('id') is-invalid @enderror"
                                        name="id" value="{{ Auth::user()->id }}" required>
    
                                    <button type="submit" class="fa fa-thumbs-up transparent {{ $btn }}" >
                                    </button>
                                </form>
                            
                                {{ $content->comment }}
                                <button class="fa fa-book transparent" onclick="likes()"></button>
                                <a data-toggle="modal" data-target="#loveModal">Love by</a>
                            </div>
                            <br>
                            <form action="store_comment" method="post" class="mt-3">
                                {{ csrf_field() }}

                                <input type="hidden" class="form-control @error('content_id') is-invalid @enderror"
                                    name="content_id" value="{{ $content->content_id }}" >

                                <input type="hidden" class="form-control @error('id') is-invalid @enderror"
                                    name="id" value="{{ Auth::user()->id }}" >

                                <input type="text" class="form-control @error('comment') is-invalid @enderror"
                                    name="comment" value="{{ old('comment') }}" required autocomplete="comment"
                                    autofocus placeholder="Comment as {{ Auth::user()->name }}">

                                @error('comment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </form>
                            <br>
                            @foreach ($comment as $n)
                                {{ $n->name }} - 
                                {{ $n->comment }}<br>
                            @endforeach
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
                                        <a href="home/delete/{{ $content->content_id }}" class="fg-red">Delete</a>
                                    </div>
                                    <div class="card-content">
                                        @if (!empty($content->file))
                                            <img src="{{ asset("data_file/".$content->file."") }}" class="w-100">
                                        @endif
                                        <div class="mx-3 my-3">
                                            {{ $content->content }}
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

                    <div class="modal fade" id="loveModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Love {{ $content->name }}'s post</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="mx-3 my-3">
                                        @foreach ($love as $n)
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
            <div class="col-md-3">
                <div>
                    <h6>Informasi</h6>
                    <hr>
                    <div class="scrollable-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    $('.input-file').hide();
        $('.show-file').on('click', function () {
            $('.input-file').slideDown();
        });
});
</script>

@endsection
