@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset('images/Logo PPKPT 2025 Square Black.png') }}" alt="{{ config('app.name') }} Logo"
                style="max-width: 180px;">
            {!! $slot !!}

        </a>
    </td>
</tr>
