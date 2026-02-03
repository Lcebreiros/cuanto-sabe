<div style="max-width: 1200px; margin: 0 auto; padding: 4rem 1.5rem;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 style="font-size: 1.875rem; font-weight: bold;">Conocé Nuestro Equipo</h2>

    @can('edit pages')
      <a href="{{ route('admin.team.index') }}" style="padding: 0.5rem 1rem; border: 1px solid currentColor; border-radius: 0.375rem; text-decoration: none;">Administrar Equipo</a>
    @endcan
  </div>

  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
    @foreach($members as $member)
        <div style="background: rgba(255, 255, 255, 0.05); border-radius: 1rem; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; transition: all 0.3s ease;">

            <!-- CONTENEDOR DE IMAGEN - TAMAÑO FIJO -->
            <div style="width: 100%; height: 16rem; background: rgba(0, 0, 0, 0.2); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                @if(!empty($member['photo_url']))
                    <img
                        src="{{ $member['photo_url'] }}"
                        alt="{{ $member['name'] }}"
                        style="width: 100%; height: 100%; object-fit: cover;"
                        loading="lazy"
                    >
                @else
                    <div style="color: #9ca3af; font-size: 0.875rem;">Sin foto</div>
                @endif
            </div>

            <!-- CONTENIDO -->
            <div style="padding: 1rem; flex: 1; display: flex; flex-direction: column;">
                <h3 style="font-weight: 600; font-size: 1.125rem;">{{ $member['name'] }}</h3>

                @if(!empty($member['role']))
                    <p style="font-size: 0.875rem; color: #9ca3af; margin-top: 0.25rem;">{{ $member['role'] }}</p>
                @endif

                <p style="margin-top: 0.5rem; font-size: 0.875rem; opacity: 0.9;">
                    {!! nl2br(e($member['description'])) !!}
                </p>
            </div>

        </div>
    @endforeach
</div>
