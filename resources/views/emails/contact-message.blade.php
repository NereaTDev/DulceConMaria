<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Nuevo mensaje de contacto · DulceConMaría</title>
</head>
<body style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background-color:#fff5fb; padding:24px;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px;margin:0 auto;background-color:#ffffff;border-radius:16px;border:1px solid #f7d2e4;padding:20px;">
        <tr>
            <td style="text-align:center;padding-bottom:12px;">
                <img src="https://dulceconmaria.com/assets/Logo.png" alt="DulceConMaría" style="height:40px;width:auto;">
            </td>
        </tr>
        <tr>
            <td style="font-size:18px;font-weight:600;color:#2b1a22;padding-bottom:8px;">
                Nuevo mensaje de contacto desde la web
            </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#5b4a54;padding-bottom:16px;">
                Has recibido un nuevo mensaje a través del formulario de contacto de DulceConMaría.
            </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#5b4a54;padding-bottom:8px;">
                <strong>Nombre:</strong> {{ $name }}
            </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#5b4a54;padding-bottom:8px;">
                <strong>Email:</strong> {{ $email }}
            </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#5b4a54;padding-bottom:16px;">
                <strong>Mensaje:</strong><br>
                <span style="white-space:pre-line;">{{ $messageText ?: '— (sin mensaje adicional)' }}</span>
            </td>
        </tr>
    </table>
</body>
</html>
