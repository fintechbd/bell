<tr>
    <td class="header">
        <a href="#" style="display: inline-block;">
            @if (isset($slot) && trim($slot) === 'Laravel')
                <img src="https://laravel.com/img/notification-logo.png" class="logo"
                     alt="Laravel Logo">
            @else
                {{ config('app.name') }}
            @endif
        </a>
    </td>
</tr>
