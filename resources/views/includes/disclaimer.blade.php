<table class="subcopy" width="100%" cellpadding="0" cellspacing="0"
       role="presentation">
    <tr>
        <td>
            <div style="font-size: 80%; font-family: 'Times New Roman',sans-serif">
                <p style="font-family: 'Consolas',sans-serif; border-radius:4px; border:1px solid yellow; text-align: center; padding: 4px; width: 100%; background-color: yellow; color: red; font-weight: bolder">
                    <code>
                        This is an auto generated notification email. Please do not
                        reply to this email.
                    </code>
                </p>
                <span style="color: #500050">
                    This email and any attachments are confidential and may also be privileged. <br>
                    If you are not the intended recipient, please delete all copies and notify the sender immediately.<br>
                    For any queries, please forward to {{ config('app.name') }} 24x7 Customer support center through below
                channels:<br>
                Phone: <a href="tel:{{ $phone }}" target="_blank">{{ $phone }}</a> (from anywhere in the world), Email:
                <a
                    href="mailto:{{ $email }}" target="_blank">{{ $email }}</a>.<br>
                <br>
                For more information on {{ config('app.name') }} products and services, please
                visit {{ config('app.name') }} website: <a href="mailto:{{ $website }}"
                                                           target="_blank">{{ $website }}</a>.
                </span>
            </div>
        </td>
    </tr>
</table>
