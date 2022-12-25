<p class="text-muted">
    {{ empty(trim($slot)) ? 'Added ' : $slot }}  {{ $date }}
     {{ $name ?  'by ' . $name : '' }}
</p>