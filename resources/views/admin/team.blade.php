@php $hideNavigation = true; $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', 'Gestión de Equipo - Cuánto Sabe')

@push('styles')
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
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .glow-effect      { box-shadow: 0 0 20px rgba(0, 240, 255, 0.1); }
        .glow-effect:hover { box-shadow: 0 0 30px rgba(0, 240, 255, 0.2); }
    </style>
@endpush

@section('content')
    <div class="chat-topbar">
        <a href="{{ route('dashboard') }}" class="chat-back-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
        <span class="chat-title">Equipo</span>
    </div>

    <!-- Efectos de fondo adicionales -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-cyan-500/5 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
    </div>

    <div class="fade-in-up">
        @livewire('team-admin')
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('memberSaved', () => {
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    toast.textContent = '✅ Miembro guardado correctamente';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 3000);
                });
            });
        </script>
    @endpush
@endsection
