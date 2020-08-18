@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    Список книг
                </div>
            </div>
            <div class="row pl-3 pr-3">
                @foreach ($books as $book)
                <div class="card col-md-5 p-0 m-1">
                    <div class="card-header">
                        Название: 
                        {{ $book->name }}
                    </div>
                    <div class="card-body">
                        Содержание:<br>
                        {{ $book->text }}
                        <div class="mt-3">
                            <a href="{{ route('book', ['id' => $book->id]) }}" class="btn btn-primary mb-2">Читать</a>
                            @if ($book->user_id == auth()->user()->id)
                            <form method="POST" action="{{ route('book_open') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $book->id }}">
                                <input type="submit" value="Открыть по ссылке" class="btn btn-primary mb-2">
                            </form>
                            <form method="POST" action="{{ route('book_edit', ['id' => $book->id]) }}">
                                @csrf
                                <input type="submit" name="edit" value="Редактировать" class="btn btn-primary mb-2">
                            </form>
                            <a href="{{ route('book_delete', ['id' => $book->id]) }}" class="btn btn-primary">Удалить</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @if ($book->user_id == auth()->user()->id)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Создать книгу:</div>
                <form class="card-body" action="{{ route('book_create') }}" method="POST">
                    @csrf
                    <input name="name" class="form-control mb-2" placeholder="Название">
                    <input name="text" class="form-control mb-2" placeholder="Текст">
                    <input type="submit" name="create" class="btn btn-primary">
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
