@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Список всех пользователей:</div>
            </div>
            @foreach ($users as $user)
            <div class="card mt-3">
                <div class="card-body">
                    Name: {{ $user->name }}<br>
                    ID: {{ $user->id }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
