<table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td>
            @isset($actionText)
                <p style="text-align: left; font-size: 12px; line-height: 1.5em; margin-top: 0;" align="left">
                    {{-- Ganti teks bahasa Inggris dengan bahasa Indonesia --}}
                    Jika Anda mengalami kesulitan saat mengklik tombol "{{ $actionText }}", salin dan tempel URL di bawah
                    ini ke browser web Anda:
                    <span class="break-all"><a href="{{ $actionUrl }}">{{ $actionUrl }}</a></span>
                </p>
            @endisset
        </td>
    </tr>
</table>
