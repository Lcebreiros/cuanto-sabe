@php
    $hideNavigation = true;
    $hideFooter = true;
@endphp

@extends('layouts.app')

@section('content')
<style>
    main {
        padding: 0 !important;
    }

    html, body {
        overflow: hidden;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background: #080818;
    }

    .chat-wrapper {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100vh;
        height: 100dvh;
        background: #080818;
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
    }

    .chat-back-btn svg {
        width: 13px;
        height: 13px;
        flex-shrink: 0;
    }

    .chat-title {
        color: #00f0ff;
        font-size: 0.95rem;
        font-weight: 700;
        text-shadow: 0 0 8px rgba(0, 240, 255, 0.6);
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .chat-frame-container {
        flex: 1;
        position: relative;
        overflow: hidden;
    }

    .chat-frame-container iframe {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
        background: transparent;
    }
</style>

<div class="chat-wrapper">
    <div class="chat-topbar">
        <a href="{{ route('dashboard') }}" class="chat-back-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
        <span class="chat-title">Chat</span>
    </div>

    <div class="chat-frame-container">
        <iframe
            src="https://socialstream.ninja/dock.html?session=NDUiG3QvPP&transparent&scale=1&delaytime=10000000"
            allowtransparency="true"
            allow="autoplay"
            title="Chat"
        ></iframe>
    </div>
</div>
@endsection
