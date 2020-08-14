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
                        <div class="mb-3">{{ $comment['theme'] }}</div>
                        <div class="mb-2">
                            @if (isset($comment['quote_name']))
                                @if (!$comment['quote_del'])
                                    <i>В ответ на:</i>
                                    <div class="card mt-3 mb-2">
                                        <div class="card-header">{{ $comment['quote_name'] }} - {{ $comment['quote_date'] }}</div>
                                        <i class="card-body">"{{ $comment['quote_text'] }}"</i>
                                    </div>
                                @else
                                    <div class="card mt-3">
                                        <div class="card-header">DELETED</div>
                                        <div class="card-body">Комментарий был удален пользователем.</div>
                                    </div>
                                @endif
                            @endif
                            {{ $comment['text'] }}
                        </div>
                        @auth
                            <form method="POST">
                                @csrf
                                <input type="hidden" name="com" value="{{ $comment['id'] }}">
                                @if ($comment['user_id'] == $comment['auth'] || $comment['page_id'] == $comment['auth'])
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
        <div class="col-md-4">
            @auth
            <form method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">Оставить комментарий</div>
                    <div class="card-body ">
                        @if ($reply != null)
                        <b>В ответ {{ $reply['user'] }}</b>
                        <input type="hidden" name="reply_id" value="{{ $reply['id'] }}">
                        @endif
                        <input class="form-control" name="title" placeholder="Заголовок" required>
                        @if ($reply == null)
                        <input class="form-control" name="theme" placeholder="Тема" required>
                        @endif
                        <input class="form-control" name="text" placeholder="Сообщение" required>
                        <input type="submit" name="submit">
                    </div>
                </div>
            </form>
            @endauth
            <div class="card mt-3">
                <div class="card-header">Все комментарии пользователя:</div>
                <div class="card-body">
                    <a href="{{ URL('comments/'.$user->id) }}">Открыть</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    let
        userid = {{ $user->id }};

    $(document).ready(function () { 
        $('#comments_more').on('click', function() {
            $.ajax({
                url: "/getcomment",
                data: {
                    page: userid
                },
                success: function( result ) {
                    $.each(result, function(i, val) {
                        if(result[i].delete == 0) {
                            let 
                                string = '',
                                quote = '';

                            if(result[i].user_id == result[i].auth || result[i].page_id == result[i].auth) {
                                string = '<input type="submit" name="delcom" value="Удалить">';
                            }
                            if(typeof result[i].quote_name !== "undefined") {
                                if(!result[i].quote_del) {
                                    quote = `
                                        <i>В ответ на:</i>
                                        <div class="card mt-3 mb-2">
                                            <div class="card-header">${result[i].quote_name} - ${result[i].quote_date}</div>
                                            <i class="card-body">"${result[i].quote_text}"</i>
                                        </div>
                                    `;
                                }
                                else {
                                    quote = `
                                        <div class="card mt-3">
                                            <div class="card-header">DELETED</div>
                                            <div class="card-body">Комментарий был удален пользователем.</div>
                                        </div>
                                    `;
                                }
                            }

                            $('#Comments').append(`
                                <div class="card mt-3">
                                    <div class="card-header">${result[i].username} - ${result[i].created_at}</div>
                                    <div class="card-body">
                                        <div>${result[i].title}</div>
                                        <div>${result[i].theme}</div>
                                        <div>${quote} ${result[i].text}</div>
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