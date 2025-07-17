<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuanto Sabe - Invitado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at center, #1e0047, #0c0125);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }

        .content-container {
            background: rgba(5, 5, 20, 0.85);
            border-radius: 15px;
            padding: 40px 60px;
            box-shadow:
                0 0 21px rgba(0, 240, 255, 0.35),
                0 0 42px rgba(0, 240, 255, 0.25),
                0 0 63px rgba(0, 240, 255, 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 623px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }

        img.logo {
            width: 250px;
            height: auto;
            margin-bottom: 0;
            filter: drop-shadow(0 0 10px #ff00ff);
            max-width: 100%;
        }

        h3.center-title {
            font-size: 1.8rem;
            color: #00f0ff;
            text-shadow: 0 0 5px #00f0ff;
            margin-bottom: 2rem;
            margin-top: 0.5rem;
            text-align: center;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            width: 100%;
        }

        .btn-glow {
            background-color: #000;
            border: 2px solid #00f0ff;
            color: white;
            padding: 30px 0;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            box-shadow: 0 0 10px #00f0ff;
            transition: all 0.3s ease;
            user-select: none;
            height: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
            box-sizing: border-box;
        }

                .btn-jugar {
            background-color: #000;
            border: 2px solid #00f0ff;
            color: white;
            padding: 30px 0;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            box-shadow: 0 0 10px #00f0ff;
            transition: all 0.3s ease;
            user-select: none;
            height: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
            box-sizing: border-box;
        }

        .btn-glow:hover {
            background-color: #001f2f;
            transform: scale(1.05);
            box-shadow: 0 0 20px #00f0ff;
        }

        .btn-logo img {
            height: 40px;
            margin-left: 10px;
            filter: drop-shadow(0 0 10px #ff00ff);
            max-width: none;
        }

        /* Tablets */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .content-container {
                padding: 30px 40px;
                max-width: 550px;
            }
            
            img.logo {
                width: 200px;
            }
            
            h3.center-title {
                font-size: 1.6rem;
                margin-bottom: 1.8rem;
            }
            
            .btn-glow {
                height: 100px;
                font-size: 1.1rem;
                padding: 20px 0;
            }
            
            .btn-logo img {
                height: 35px;
                margin-left: 8px;
            }
        }

        /* Móviles */
        @media (max-width: 580px) {
            body {
                padding: 10px;
                justify-content: flex-start;
                padding-top: 40px;
            }
            
            .content-container {
                padding: 25px 30px;
                max-width: 450px;
                border-radius: 12px;
            }
            
            img.logo {
                width: 170px;
            }
            
            h3.center-title {
                font-size: 1.4rem;
                margin-bottom: 1.5rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .btn-glow {
                height: 80px;
                font-size: 1rem;
                padding: 15px 0;
            }

            .btn-jugar {
                height: 160px;
                font-size: 1rem;
                padding: 15px 0;
            }
            
            .btn-logo img {
                height: 30px;
                margin-left: 6px;
            }
        }

        /* Móviles pequeños */
        @media (max-width: 420px) {
            .content-container {
                padding: 20px 25px;
                max-width: 350px;
            }
            
            img.logo {
                width: 140px;
            }
            
            h3.center-title {
                font-size: 1.2rem;
                margin-bottom: 1.2rem;
            }
            
            .btn-glow {
                height: 70px;
                font-size: 0.9rem;
                padding: 10px 0;
            }
            
            .btn-logo img {
                height: 25px;
                margin-left: 5px;
            }
        }

        /* Móviles muy pequeños */
        @media (max-width: 320px) {
            .content-container {
                padding: 15px 20px;
                max-width: 280px;
            }
            
            img.logo {
                width: 120px;
            }
            
            h3.center-title {
                font-size: 1.1rem;
                margin-bottom: 1rem;
            }
            
            .btn-glow {
                height: 60px;
                font-size: 0.8rem;
                padding: 8px 0;
            }
            
            .btn-logo img {
                height: 20px;
                margin-left: 4px;
            }
        }
.participar-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 98%;
    max-width: 500px;
    min-width: 140px;
    margin: 30px auto 10px auto;
    padding: 22px 0;
    border-radius: 11px;
    border: 2.5px solid #05ff9e;
    background: #20df84;
    color: #fff;
    font-size: 1.35rem;
    font-family: 'Orbitron', sans-serif;
    font-weight: bold;
    box-shadow: 0 0 14px #00ffb4b2;
    letter-spacing: 2px;
    text-align: center;
    text-decoration: none;
    transition: background 0.18s, color 0.15s, border-color 0.18s, box-shadow 0.18s;
    user-select: none;
    outline: none;
}
.participar-btn:hover,
.participar-btn:focus {
    background: #2affb3;
    color: #00361e;
    border-color: #12ffcb;
    box-shadow: 0 0 28px #05ff9e99, 0 0 4px #fff2;
}
.participar-btn.disabled,
.participar-btn[disabled] {
    background: #26314a !important;
    color: #aaa !important;
    border-color: #333 !important;
    box-shadow: none !important;
    cursor: not-allowed !important;
    pointer-events: none;
}
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif


</head>
<body>
        @php
    $activeSession = \App\Models\GameSession::where('status', 'active')->latest()->first();
@endphp

    <div class="content-container">
        <img src="/images/logo.png" alt="Logo Cuanto Sabe" class="logo" />
        <h3 class="center-title">Centro de Control</h3>

        <div class="grid">
            <a href="#" class="btn-glow btn-logo">
                <span>Conoce</span>
                <img src="/images/logo.png" alt="Logo">
            </a>
            <a href="#" class="btn-glow">Repeticiones</a>
            <a href="#" class="btn-jugar">Jugar</a>
        </div>
        <div style="width:100%; display:flex; justify-content:center;">
    @if($activeSession)
        <a href="{{ route('game.participate') }}"
           class="participar-btn"
           style="pointer-events:auto;">
            Participar
        </a>
    @else
        <a href="#"
           class="participar-btn disabled"
           style="pointer-events:none;">
            Participar
        </a>
    @endif
</div>

    </div>

</body>
</html>