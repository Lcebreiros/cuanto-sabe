@extends('layouts.app')

@section('header')
    <h1>Panel de Administración</h1>
@endsection

@section('content')
<style>
    .container {
        background: rgba(5, 5, 20, 0.85);
        border-radius: 15px;
        padding: 30px 40px;
        max-width: 900px;
        width: 100%;
        box-shadow:
            0 0 21px rgba(0, 240, 255, 0.35),
            0 0 42px rgba(0, 240, 255, 0.25),
            0 0 63px rgba(0, 240, 255, 0.15);
        margin: auto;
        text-align: center;
    }

    .btn {
        background-color: transparent;
        border: 2px solid #00f0ff;
        color: #00f0ff;
        padding: 15px 35px;
        font-weight: bold;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 20px 15px;
        font-size: 1.2rem;
        user-select: none;
        display: inline-block;
        min-width: 160px;
    }

    .btn:hover {
        background-color: #00f0ff;
        color: #000;
        box-shadow: 0 0 15px #00f0ff;
    }
</style>

<div class="container">
    <a href="{{ route('users') }}" class="btn">Usuarios</a>
    <a href="{{ route('questions') }}" class="btn">Preguntas</a>
</div>
@endsection
