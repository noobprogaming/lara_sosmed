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
                                <div class="form-inline px-2 py-2 my-3">
                                    <img src="{{ asset('data_file/'.Auth::user()->id.'') }}" class="profile-sm">
                                    <div class="ml-3 uname">{{ Auth::user()->name }}</div>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <div class="scrollable-menu">
                                <div id="chat_list">
                                    @foreach($message_list as $n)
                                    <a href="message?q={{ $n->id }}">
                                        @if ( $n->id == $user_describe->id )
                                            <div class="form-inline conv-list px-2 py-2 silver-soft">
                                        @else
                                            <div class="form-inline conv-list px-2 py-2">
                                        @endif
                                        <img src='{{ asset('data_file/'.$n->id.'') }}' class="profile-sm">
                                            <div class="ml-3">
                                                {{ $n->name }}<br>
                                            </div>
                                        </div>
                                    </a>     
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                <div class="card-header">Chat with <a href="profile?q={{ $user_describe->id }}">{{ $user_describe->name }}</a></div>
                    <div class="row px-4 py-4">
                        <div class="scrollable-menu" id="conv">
                            <div id="chat">
                                @foreach($message_chat as $n)
                                    <div class="my-1 px-5" style="width: 100%; float: right;" data-toggle="tooltip" data-placement="top" title="{{ Carbon\Carbon::parse($n->time)->diffForHumans() }}">
    
                                    @if($n->id == Auth::user()->id)
                                        <div class="row" style="float: right">
                                    @else
                                        <div class="row" style="float: left">
                                    @endif
                                        <table class="card conv px-1 py-1" style="max-width: 350px;">  
                                            <tr>
                                                <td>
                                                    <img src="{{ asset("data_file/".$n->id."") }}" class="profile-xs">
                                                </td>
                                                <td style="word-wrap: break-word;">
                                                    <div class="mx-2">
                                                        {{ $n->message }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>   
                    </div>
                    <div class="card-footer form-inline">
                        <form id="store_message">
                            {{ csrf_field() }}

                            <input type="hidden" class="form-control @error('sender_id') is-invalid @enderror" name="sender_id" value="{{ Auth::user()->id }}" required>

                            <input type="hidden" class="form-control @error('receiver_id') is-invalid @enderror" name="receiver_id" value="{{ $user_describe->id }}" required>

                            <input type="text" class="form-control @error('message') is-invalid @enderror" name="message" value="{{ old('message') }}" required autocomplete="message" autofocus placeholder="Send message to {{ $user_describe->name }}">

                            @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            
                        </form>

                        <button class="btn btn-primary ml-1" onclick="send_msg()">
                            <i class="fa fa-paper-plane"></i>
                        </button>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div>
                    <h6>Informasi</h6>
                    <hr>
                    <div class="scrollable-menu">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        setInterval(function () {
            $("#chat_list").load( '' + ' #chat_list');
            $("#chat").load( '' + ' #chat');
        }, 2000);  
        scroll_down();
    });

    function scroll_down() {
        setTimeout(function () {
            var messageBody = document.querySelector('#conv');
            messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
        }, 1000);
    };

    $(document).keypress(function(event) {
        if(event.which == '13') {
        event.preventDefault();
        send_msg();
        }
    });

    function send_msg() {
        $.ajax({
            type: "POST",
            url: "store_message",
            data: $('#store_message').serialize(),
        }).done(function () {
            $(".form-control").val('');
            scroll_down();
        });
    };
</script>
@endsection
