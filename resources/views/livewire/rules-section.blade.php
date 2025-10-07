<div class="relative z-10">
    <!-- Efectos de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 -right-20 w-60 h-60 bg-cyan-500/10 rounded-full blur-3xl animate-float-slow"></div>
        <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-purple-500/10 rounded-full blur-3xl animate-float-slow delay-2000"></div>
    </div>

    <div class="relative">
        @if(count($rules) > 0)
            <!-- Grid de reglas estilo Express -->
            <div class="rules-grid">
                @foreach($rules as $index => $rule)
                    <div class="rule-card card animate-fade-in" style="animation-delay: {{ $index * 0.1 }}s">
                        <!-- Icono de la regla -->
                        <div class="rule-icon">
                            {{ $rule['icon'] ?? '🎯' }}
                        </div>
                        
                        <!-- Contenido de la regla -->
                        <div class="rule-content">
                            <h3 class="rule-title">
                                {{ $rule['title'] }}
                            </h3>
                            <p class="rule-description">
                                {{ $rule['content'] }}
                            </p>
                        </div>

                        <!-- Badge de categoría -->
                        <div class="rule-category">
                            <span class="category-badge">
                                {{ $categories[$rule['category'] ?? 'general'] ?? 'General' }}
                            </span>
                        </div>

                        <!-- Efecto de brillo al hover -->
                        <div class="card-glow"></div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Estado vacío mejorado -->
            <div class="text-center py-16">
                <div class="empty-state">
                    <div class="empty-icon">🎮</div>
                    <h3 class="empty-title">Reglas no configuradas</h3>
                    <p class="empty-description">
                        Las reglas del juego aún no han sido configuradas.
                    </p>
                </div>
            </div>
        @endif
    </div>

    <style>
        .rules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .rule-card {
            background: rgba(15, 6, 43, 0.85);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid rgba(0, 240, 255, 0.2);
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1),
                        0 0 60px rgba(133, 4, 236, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            min-height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .rule-card::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, 
                      rgba(0, 240, 255, 0.05) 0%, 
                      transparent 70%);
            z-index: -1;
            animation: rotate 20s linear infinite;
        }

        .rule-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 240, 255, 0.2),
                        0 0 80px rgba(133, 4, 236, 0.1);
            border-color: rgba(0, 240, 255, 0.4);
        }

        .rule-icon {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: #00ffd1;
            filter: drop-shadow(0 0 10px #00ffd1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .rule-card:hover .rule-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .rule-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .rule-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #00f0ff;
            text-align: center;
            line-height: 1.3;
        }

        .rule-description {
            color: #e6f7ff;
            line-height: 1.6;
            opacity: 0.9;
            text-align: center;
            font-size: 0.95rem;
        }

        .rule-category {
            margin-top: 1.5rem;
            text-align: center;
        }

        .category-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            background: rgba(0, 240, 255, 0.1);
            color: #00f0ff;
            border: 1px solid rgba(0, 240, 255, 0.3);
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            backdrop-filter: blur(5px);
        }

        .card-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, 
                      rgba(0, 240, 255, 0.1) 0%, 
                      transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            border-radius: 20px;
        }

        .rule-card:hover .card-glow {
            opacity: 1;
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-float-slow {
            animation: float 12s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Estado vacío */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.6;
        }

        .empty-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            color: #00f0ff;
            margin-bottom: 1rem;
        }

        .empty-description {
            color: #e6f7ff;
            opacity: 0.8;
            font-size: 1.1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .rules-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .rule-card {
                padding: 2rem;
                min-height: 250px;
            }

            .rule-icon {
                font-size: 3rem;
            }

            .rule-title {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .rule-card {
                padding: 1.5rem;
                min-height: 220px;
            }

            .rule-icon {
                font-size: 2.5rem;
            }

            .rule-title {
                font-size: 1.1rem;
            }

            .rule-description {
                font-size: 0.9rem;
            }
        }

        /* Efectos de animación escalonada para las tarjetas */
        .rule-card:nth-child(1) { animation-delay: 0.1s; }
        .rule-card:nth-child(2) { animation-delay: 0.2s; }
        .rule-card:nth-child(3) { animation-delay: 0.3s; }
        .rule-card:nth-child(4) { animation-delay: 0.4s; }
        .rule-card:nth-child(5) { animation-delay: 0.5s; }
        .rule-card:nth-child(6) { animation-delay: 0.6s; }
    </style>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animación de entrada con Intersection Observer
        const ruleCards = document.querySelectorAll('.rule-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = entry.target.style.animationDelay || '0.1s';
                    entry.target.style.animation = `fadeInUp 0.6s ease-out ${delay} forwards`;
                }
            });
        }, { threshold: 0.1 });

        ruleCards.forEach(card => {
            observer.observe(card);
        });

        // Efecto de parallax en el hover
        ruleCards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateY = (x - centerX) / 25;
                const rotateX = (centerY - y) / 25;
                
                card.style.transform = `translateY(-10px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(-10px) rotateX(0deg) rotateY(0deg)';
            });
        });
    });
</script>