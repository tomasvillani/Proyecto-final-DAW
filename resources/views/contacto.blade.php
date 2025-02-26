<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Contacto</title>
</head>
<body>
    <h2>Nuevo mensaje de contacto</h2>
    <p><strong>Nombre:</strong> {{ $datos['name'] }}</p>
    <p><strong>Email:</strong> {{ $datos['email'] }}</p>
    <p><strong>Asunto:</strong> {{ $datos['subject'] }}</p>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $datos['message'] }}</p>
</body>
</html>
