@extends('layouts.app') {{-- O layouts.app si usas la opción 2 --}}

@section('title', 'Gestión de Equipo - Cuánto Sabe')

@push('styles')
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
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .glow-effect {
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.1);
        }
        
        .glow-effect:hover {
            box-shadow: 0 0 30px rgba(0, 240, 255, 0.2);
        }
    </style>
@endpush

@section('content')
    <!-- Efectos de fondo adicionales -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-cyan-500/5 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
    </div>

    <div style="padding: 0.5rem 0 0.25rem;">
        <a href="{{ route('dashboard') }}" class="back-btn-admin">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M5 12l7-7M5 12l7 7"/></svg>
            Volver
        </a>
    </div>

    <!-- Contenedor principal con animación de entrada -->
    <div class="fade-in-up">
        @livewire('team-admin')
    </div>
    
    <!-- Scripts adicionales -->
    @push('scripts')
        <script>
            // Animaciones adicionales
            document.addEventListener('DOMContentLoaded', function() {
                // Efecto de particles para el fondo admin
                const createParticle = () => {
                    const particle = document.createElement('div');
                    particle.className = 'absolute w-2 h-2 bg-cyan-400/20 rounded-full animate-pulse';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.top = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 3 + 's';
                    document.querySelector('.fixed.inset-0').appendChild(particle);
                };
                
                // Crear algunas partículas
                for (let i = 0; i < 8; i++) {
                    createParticle();
                }
            });
            
            // Livewire hooks para mejor UX
            document.addEventListener('livewire:init', () => {
                Livewire.on('memberSaved', () => {
                    // Efecto de confeti o notificación
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    toast.textContent = '✅ Miembro guardado correctamente';
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                });
            });
        </script>
    @endpush
@endsection