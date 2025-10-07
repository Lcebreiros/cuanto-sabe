@extends('layouts.app') {{-- O layouts.app si usas la opción 2 --}}

@section('title', 'Gestión de Equipo - Cuánto Sabe')

@push('styles')
    <style>
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