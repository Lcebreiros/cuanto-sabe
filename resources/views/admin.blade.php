@php $hideNavigation = true; $hideFooter = true; @endphp
@extends('layouts.app')

@section('content')
<style>
    main { padding: 0 !important; }

    .admin-wrapper {
        display: flex;
        flex-direction: column;
        width: 100%;
        min-height: 100vh;
    }

    .chat-topbar {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 16px;
        background: rgba(10, 14, 35, 0.98);
        border-bottom: 1px solid rgba(0, 240, 255, 0.2);
        flex-shrink: 0;
        height: 44px;
    }

    .chat-back-btn {
        background: rgba(0, 240, 255, 0.1);
        color: #00f0ff;
        border: 1.5px solid rgba(0, 240, 255, 0.35);
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .chat-back-btn:hover {
        background: rgba(0, 240, 255, 0.22);
        border-color: #00f0ff;
        box-shadow: 0 0 12px rgba(0, 240, 255, 0.35);
        color: #00f0ff;
    }
    .chat-back-btn svg { width: 13px; height: 13px; flex-shrink: 0; }

    .chat-title {
        color: #00f0ff;
        font-size: 0.95rem;
        font-weight: 700;
        text-shadow: 0 0 8px rgba(0, 240, 255, 0.6);
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .admin-body {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
        padding: 40px 20px;
    }

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
        text-decoration: none;
    }
    .btn:hover {
        background-color: #00f0ff;
        color: #000;
        box-shadow: 0 0 15px #00f0ff;
    }
</style>

<div class="admin-wrapper">
    <div class="chat-topbar">
        <a href="{{ route('dashboard') }}" class="chat-back-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
        <span class="chat-title">Administración</span>
    </div>

    <div class="admin-body">
        <div class="container">
            <a href="{{ route('users') }}" class="btn">Usuarios</a>
            <a href="{{ route('questions') }}" class="btn">Preguntas</a>
        </div>
    </div>
</div>
@endsection
