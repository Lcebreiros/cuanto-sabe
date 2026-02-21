@extends('layouts.app')

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
</style>
@endpush

@section('content')
  <div style="padding: 0.5rem 0 0.25rem;">
      <a href="{{ route('dashboard') }}" class="back-btn-admin">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M5 12l7-7M5 12l7 7"/></svg>
          Volver
      </a>
  </div>
  @livewire('rule-admin')
@endsection
