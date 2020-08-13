@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" id="Comments">
            <div class="card">
                <div class="card-header">{{ $user->name }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
            @foreach ($comments as $comment)
                @if ($comment['delete'] == 0)
                <div class="card mt-3">
                    <div class="card-header">{{ $comment['username'] }} - {{ $comment['created_at'] }}</div>
                    <div class="card-body">
                        <div>{{ $comment['title'] }}</div>
                        <div>{{ $comment['theme'] }}</div>
                        <div>{{ $comment['text'] }}</div>
                        @auth
                            <form method="POST">
                                @csrf
                                <input type="hidden" name="com" value="{{ $comment['id'] }}">
                                @if ($comment['user_id'] == auth()->user()->id || $comment['page_id'] == auth()->user()->id)
                                <input type="submit" name="delcom" value="Удалить">
                                @endif
                                <input type="submit" name="replycom" value="Ответить">
                            </form>
                        @endauth
                    </div>
                </div>
                @else
                <div class="card mt-3">
                    <div class="card-header">DELETED</div>
                    <div class="card-body">Комментарий был удален пользователем.</div>
                </div>
                @endif
            @endforeach
            <button id="comments_more">Еще..</button>
        </div>
        @auth
        <form class="col-md-4" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">Оставить комментарий</div>
                <div class="card-body ">
                    <input class="form-control" name="title" placeholder="Заголовок" required>
                    <input class="form-control" name="theme" placeholder="Тема" required>
                    <input class="form-control" name="text" placeholder="Сообщение" required>
                    <input type="submit" name="submit">
                </div>
            </div>
        </form>
        @endauth
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () { 
        $('#comments_more').on('click', function() {
            $.ajax({
                url: "/getcomment",
                data: {
                    page: {{ $user->id }}
                },
                success: function( result ) {
                    $.each(result, function(i, val) {
                        if(result[i].delete == 0) {
                            let string = '';

                            if(result[i].user_id == result[i].auth || result[i].page_id == result[i].auth) {
                                string = '<input type="submit" name="delcom" value="Удалить">';
                            }

                            $('#Comments').append(`
                                <div class="card mt-3">
                                    <div class="card-header">${result[i].username} - ${result[i].created_at}</div>
                                    <div class="card-body">
                                        <div>${result[i].title}</div>
                                        <div>${result[i].theme}</div>
                                        <div>${result[i].text}</div>
                                        @auth
                                            <form method="POST">
                                                @csrf
                                                <input type="hidden" name="com" value="${result[i].id}">
                                                ${string}
                                                <input type="submit" name="replycom" value="Ответить">
                                            </form>
                                        @endauth
                                    </div>
                                </div>
                            `);
                        }
                        else {
                            $('#Comments').append(`
                                <div class="card mt-3">
                                    <div class="card-header">DELETED</div>
                                    <div class="card-body">Комментарий был удален пользователем.</div>
                                </div>
                            `);
                        }
                    });
                }
            });
            $(this).hide();
        });
    });
</script>
@endsection