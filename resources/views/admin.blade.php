@extends('layouts.app')

@section('header')
    <h1>Panel de Administración</h1>
@endsection

@section('content')
<style>
    .back-btn-admin {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Orbitron', Arial, sans-serif;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        color: #00f0ff;
        text-decoration: none;
        padding: 7px 14px;
        border: 1px solid rgba(0,240,255,0.3);
        border-radius: 999px;
        background: rgba(0,240,255,0.05);
        transition: background 0.2s, border-color 0.2s, box-shadow 0.2s;
        white-space: nowrap;
        margin-bottom: 1.5rem;
    }
    .back-btn-admin:hover {
        background: rgba(0,240,255,0.12);
        border-color: #00f0ff;
        box-shadow: 0 0 10px rgba(0,240,255,0.3);
        color: #00f0ff;
    }
    .back-btn-admin svg { flex-shrink: 0; }
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

<div style="max-width:900px;margin:0 auto 0;padding:0 0 0;">
    <a href="{{ route('dashboard') }}" class="back-btn-admin">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M5 12l7-7M5 12l7 7"/></svg>
        Volver
    </a>
</div>
<div class="container">
    <a href="{{ route('users') }}" class="btn">Usuarios</a>
    <a href="{{ route('questions') }}" class="btn">Preguntas</a>
</div>
@endsection
