<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Bienvenida a DulceConMaría · Confirma tu correo</title>
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
                        Bienvenida al campus
                    </td>
                </tr>
                <tr>
                    <td style="font-size:20px;font-weight:600;color:#2b1a22;padding-bottom:12px;text-align:center;">
                        Confirma tu correo para acceder
                    </td>
                </tr>
                <tr>
                    <td style="font-size:13px;line-height:1.5;color:#5b4a54;padding-bottom:16px;">
                        @if(!empty($user->name))
                            Hola {{ $user->name }},
                            <br><br>
                        @else
                            Hola,
                            <br><br>
                        @endif
                        ¡Gracias por registrarte en el campus de DulceConMaría!<br>
                        Antes de entrar a tus contenidos, necesitamos que confirmes que este correo es tuyo.
                        Usa este código de <strong>6 dígitos</strong> en la página de verificación del campus:
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding-bottom:20px;">
                        <span style="display:inline-block;margin:8px 0;font-size:22px;font-weight:700;letter-spacing:0.25em;color:#2b1a22;">
                            {{ $verificationCode ?? '------' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px;line-height:1.5;color:#7b6b75;padding-bottom:16px;">
                        También puedes hacer clic en este botón para ir a la página de verificación del campus
                        (si no has iniciado sesión, te pedirá que entres primero):
                        <br><br>
                        <a href="{{ $verificationUrl }}" style="display:inline-block;background-color:#f990b7;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;padding:10px 22px;border-radius:9999px;">
                            Ir a la página para introducir el código
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:11px;line-height:1.5;color:#a08f98;padding-top:16px;border-top:1px solid #f7d2e4;margin-top:16px;">
                        Si no has creado esta cuenta, puedes ignorar este mensaje.
                        <br><br>
                        Con cariño,<br>
                        El equipo de DulceConMaría
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
