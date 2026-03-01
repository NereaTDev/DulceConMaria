<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Restablece tu contraseña · DulceConMaría</title>
</head>
<body style="margin:0;padding:0;background-color:#fff5fb;font-family:system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#fff5fb;padding:24px 0;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:520px;background-color:#ffffff;border-radius:24px;border:1px solid #f7d2e4;box-shadow:0 10px 30px rgba(15,23,42,0.08);padding:24px 28px;">
                <tr>
                    <td align="center" style="padding-bottom:16px;">
                        <img src="https://dulceconmaria.com/assets/Logo.png" alt="DulceConMaría" style="height:48px;width:auto;display:block;">
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px;letter-spacing:0.18em;text-transform:uppercase;color:#f990b7;padding-bottom:4px;text-align:center;">
                        Recuperar acceso al campus
                    </td>
                </tr>
                <tr>
                    <td style="font-size:20px;font-weight:600;color:#2b1a22;padding-bottom:12px;text-align:center;">
                        Restablece tu contraseña
                    </td>
                </tr>
                <tr>
                    <td style="font-size:13px;line-height:1.5;color:#5b4a54;padding-bottom:20px;">
                        @if(!empty($user->name))
                            Hola {{ $user->name }},
                            <br><br>
                        @else
                            Hola,
                            <br><br>
                        @endif
                        Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en el campus de DulceConMaría.
                        Si has sido tú, haz clic en el botón de abajo para crear una nueva contraseña.
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding-bottom:20px;">
                        <a href="{{ $resetUrl }}" style="display:inline-block;background-color:#f990b7;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;padding:10px 22px;border-radius:9999px;">
                            Crear nueva contraseña
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px;line-height:1.5;color:#7b6b75;padding-bottom:16px;">
                        Si el botón no funciona, copia y pega este enlace en tu navegador:
                        <br>
                        <span style="word-break:break-all;color:#f973a6;">
                            {{ $resetUrl }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:11px;line-height:1.5;color:#7b6b75;padding-top:4px;">
                        Si no has solicitado este cambio, puedes ignorar este mensaje. Tu contraseña seguirá siendo la misma.
                    </td>
                </tr>
                <tr>
                    <td style="font-size:11px;line-height:1.5;color:#a08f98;padding-top:16px;border-top:1px solid #f7d2e4;margin-top:16px;">
                        Con cariño,
                        <br>
                        El equipo de DulceConMaría
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
