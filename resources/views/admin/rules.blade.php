@php $hideNavigation = true; $hideFooter = true; @endphp
@extends('layouts.app')

@section('content')
<style>
    main { padding: 0 !important; }
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
</style>

<div class="chat-topbar">
    <a href="{{ route('dashboard') }}" class="chat-back-btn">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Volver
    </a>
    <span class="chat-title">Reglas</span>
</div>

@livewire('rule-admin')
@endsection
