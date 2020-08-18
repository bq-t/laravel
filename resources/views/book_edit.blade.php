@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <form class="card col-md-8 p-0" method="POST">
            @csrf
            <div class="card-header">
                Название: <br>
                <input name="name" placeholder="Название" value="{{ $book->name }}" class="form-control">
            </div>
            <div class="card-body">
                Содержание: <br>
                <textarea name="text" placeholder="Содержание" class="form-control">{{ $book->text }}</textarea>
            </div>
            <input type="submit" name="submit" class="btn btn-primary m-3">
        </form>
    </div>
</div>
@endsection
