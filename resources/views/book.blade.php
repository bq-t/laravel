@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card col-md-8 p-0">
            <div class="card-header">
                Название: <br>
                {{ $book->name }}
            </div>
            <div class="card-body">
                Содержание: <br>
                {{ $book->text }}
            </div>
        </div>
    </div>
</div>
@endsection
