@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Все комментарии пользователя - {{ $user->name }}</div>
            </div>
            @foreach ($comments as $comment)
            <div class="card mt-3">
                <div class="card-header">
                    {{ $comment->title }}
                </div>
                <div class="card-body">
                    {{ $comment->text }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
