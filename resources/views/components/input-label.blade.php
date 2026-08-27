@props(['value'])

<label {{ $attributes->merge(['class' => 'label font-medium text-sm']) }}>
    {{ $value ?? $slot }}
</label>
