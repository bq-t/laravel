@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
        </div>
        @foreach ($comments as $comment)
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $comment['username'] }} - {{ $comment['created_at'] }}</div>
                <div class="card-body">
                    <div>{{ $comment['title'] }}</div>
                    <div>{{ $comment['theme'] }}</div>
                    <div>{{ $comment['text'] }}</div>
                </div>
            </div>
        </div>
        @endforeach;
        <form class="col-md-8" method="POST">
            {{ csrf_field() }}
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
    </div>
</div>
@endsection
