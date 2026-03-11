<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factor de Autenticación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; 
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            margin: 20px auto;
            padding: 30px;
            border-radius: 15px; 
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            text-align: center;
        }
        h1 {
            color: rgba(61, 13, 100, 0.8); 
            margin-bottom: 20px;
        }
        p {
            color: rgb(0, 0, 0);
            font-size: 16px;
            line-height: 1.5;
        }
        .codigo-box {
            background-color: #f8f9fa;
            border: 2px dashed rgb(106, 48, 145);
            padding: 15px;
            margin: 20px 0;
            border-radius: 10px;
        }
        .codigo-num {
            font-size: 32px;
            font-weight: bold;
            color: rgb(106, 48, 145);
            letter-spacing: 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: rgb(149, 106, 177); 
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 10px;
        }
        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hola {{ $name }}</h1>
        <p>Has intentado iniciar sesión. Utiliza el siguiente botón y el código para completar tu acceso:</p>
        
        <div class="codigo-box">
            <p style="margin: 0; font-size: 14px;">TU CÓDIGO DE VERIFICACIÓN</p>
            <span class="codigo-num">{{ $codigo }}</span>
        </div>

        <p>Haz clic en el botón de abajo e ingresa el código:</p>
        
        <a href="{{ $link }}" class="button">Inicia Sesión</a>

        <div class="footer">
            <p>Este enlace expirará en 15 minutos.</p>
        </div>
    </div>
</body>
</html>