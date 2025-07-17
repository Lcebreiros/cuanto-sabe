@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-[#00f0ff]']) }}>
    {{ $value ?? $slot }}
</label>
