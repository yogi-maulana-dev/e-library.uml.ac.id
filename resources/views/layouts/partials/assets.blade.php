@php
    $manifestPath = public_path('build/manifest.json');
    $manifest = is_file($manifestPath) ? json_decode((string) file_get_contents($manifestPath), true) : [];

    $assetEntry = function (string $entry) use ($manifest) {
        if (isset($manifest[$entry])) {
            return $manifest[$entry];
        }

        foreach ($manifest as $key => $value) {
            if (str_ends_with(str_replace('\\', '/', $key), $entry)) {
                return $value;
            }
        }

        return null;
    };

    $cssEntry = $assetEntry('resources/css/app.css');
    $jsEntry = $assetEntry('resources/js/app.js');
@endphp

@if(is_file(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    @if($cssEntry)
        <link rel="stylesheet" href="{{ asset('build/'.$cssEntry['file']) }}">
    @endif

    @if($jsEntry)
        <script type="module" src="{{ asset('build/'.$jsEntry['file']) }}"></script>
    @endif
@endif
