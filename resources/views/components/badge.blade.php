@if ($visible)
    <span class='badge badge-{{ $type }}'>
        {{ $slot }}
    </span>
@else
    <span>not visible</span>
@endif


