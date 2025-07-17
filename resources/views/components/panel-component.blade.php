<div class="content-container">
   <img src="/images/logo.png" alt="Logo Cuanto Sabe" class="logo" /> 
    <h3 class="center-title">Centro de Control</h3>

    <div class="grid">
        <a href="/preguntas" class="btn-glow">Preguntas</a>
<a href="{{ route('juego.panel') }}" class="btn-glow">Juego</a>

        <a href="/chat" class="btn-glow">Chat</a>
@auth
    @if (auth()->user()->role === 'admin')
        <a href="{{ route('admin') }}" class="btn-glow">Admin</a>
    @endif
@endauth    </div>
</div>

<style>
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
        margin: 0 auto; /* Centrar horizontal */
    }

    img.logo {
        width: 250px;
        height: auto;
        margin-bottom: 0;
        filter: drop-shadow(0 0 10px #ff00ff);
    }

    h3.center-title {
        font-size: 1.8rem;
        color: #00f0ff;
        text-shadow: 0 0 5px #00f0ff;
        margin-bottom: 2rem;
        margin-top: 0.5rem;
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
        padding: 20px 25px;
        text-align: center;
        text-decoration: none;
        font-weight: bold;
        border-radius: 6px;
        box-shadow: 0 0 10px #00f0ff;
        transition: all 0.3s ease;
        user-select: none;
        display: inline-block;
    }

    .btn-glow:hover {
        background-color: #001f2f;
        transform: scale(1.05);
        box-shadow: 0 0 20px #00f0ff;
    }
</style>
