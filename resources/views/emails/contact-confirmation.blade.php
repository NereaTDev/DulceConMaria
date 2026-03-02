<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Hemos recibido tu mensaje · DulceConMaría</title>
</head>
<body style="margin:0;padding:0;background-color:#fff5fb;font-family:system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#fff5fb;padding:24px 0;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:520px;background-color:#ffffff;border-radius:24px;border:1px solid #f7d2e4;box-shadow:0 10px 30px rgba(15,23,42,0.08);padding:24px 28px;">
                <tr>
                    <td align="center" style="padding-bottom:16px;">
                        <img src="https://dulceconmaria.com/assets/Logo.png" alt="DulceConMaría" style="height:40px;width:auto;display:block;">
                    </td>
                </tr>
                <tr>
                    <td style="font-size:18px;font-weight:600;color:#2b1a22;padding-bottom:8px;">
                        Hemos recibido tu mensaje
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;color:#5b4a54;line-height:1.5;padding-bottom:16px;">
                        @if(!empty($name))
                            Hola {{ $name }},<br><br>
                        @else
                            Hola,<br><br>
                        @endif
                        Muchas gracias por escribirnos desde el formulario de contacto de DulceConMaría.
                        <br><br>
                        He recibido tu mensaje correctamente y te responderé lo antes posible para ayudarte con tus dudas
                        sobre el curso o el campus.
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px;color:#7b6b75;line-height:1.5;padding-top:8px;">
                        Si este mensaje no lo has enviado tú, puedes ignorarlo.
                        <br><br>
                        Con cariño,<br>
                        DulceConMaría
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
