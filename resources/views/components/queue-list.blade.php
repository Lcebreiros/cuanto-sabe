{{-- resources/views/components/queue-list.blade.php --}}
<div class="neon-queue-box" id="queue-container">
    <div class="queue-title">
        Participantes
    </div>
    @if(count($participants))
        <ol class="neon-queue-list">
            @foreach($participants->sortBy('order') as $p)
                <li @if($loop->first) class="first" @endif>
                    <span class="neon-queue-num">#{{ $loop->iteration }}</span>
                    <span class="neon-queue-username">{{ $p->username }}</span>
                    <span class="neon-queue-dni">({{ $p->dni_last4 }})</span>
                    @if($p->status === 'active' || $p->status === 'playing')
                        <span class="neon-queue-playing">(jugando)</span>
                    @endif
                </li>
            @endforeach
        </ol>
    @else
        <div class="neon-queue-empty">Cola vacía</div>
    @endif
</div>

<style>
.neon-queue-box {
    background: #111b2b;
    border-radius: 13px;
    border: 1.3px solid #00f0ff44;
    padding: 20px 25px 14px 25px;
    margin-bottom: 22px;
    box-shadow: 0 0 13px #19faff32;
    max-width: 370px;
    width: 100%;
    box-sizing: border-box;
}
.queue-title {
    color: #00f0ff;
    text-shadow: 0 0 7px #00f0ffbb;
    font-size: 1.2rem;
    font-weight: bold;
    text-align: center;
    margin-bottom: 13px;
    letter-spacing: 1px;
}
.neon-queue-list {
    padding-left: 20px;
    margin-bottom: 0;
}
.neon-queue-list li {
    margin-bottom: 7px;
    font-weight: 500;
    font-size: 1.10em;
    color: #ffe47a;
    display: flex;
    align-items: center;
    border-bottom: 1px dashed #19faff22;
    padding-bottom: 2px;
}
.neon-queue-list li.first {
    color: #00f0ff;
    font-weight: 700;
    text-shadow: 0 0 8px #19faff80;
}
.neon-queue-num {
    color: #00f0ff;
    font-size: 1.03em;
    font-weight: bold;
    margin-right: 8px;
}
.neon-queue-username {
    color: #ffe47a;
    font-weight: bold;
    margin-right: 10px;
}
.neon-queue-dni {
    color: #aee;
    font-size: 0.94em;
    margin-right: 6px;
}
.neon-queue-playing {
    color: #19ff8c;
    font-weight: bold;
    font-size: 0.97em;
    margin-left: 11px;
    text-shadow: 0 0 8px #1affd2a5;
}
.neon-queue-empty {
    color: #ccc;
    text-align: center;
    font-size: 1.07rem;
    margin: 12px 0 7px 0;
}
@media (max-width: 700px) {
    .neon-queue-box {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        padding: 13px 4px 10px 4px !important; /* Usa 4px si ves que 8px suma mucho */
        margin: 0 0 22px 0 !important;
        box-sizing: border-box !important;
        overflow-x: auto !important;
    }
}

</style>

<style>
@keyframes updatePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); box-shadow: 0 0 20px #19faff66; }
    100% { transform: scale(1); }
}
</style>