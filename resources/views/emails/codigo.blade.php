<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de codigo!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color:rgba(156, 98, 202, 0.8); /* Indigo */
            margin-bottom: 10px;
        }
        p {
            color:rgb(98, 8, 116);
            font-size: 14px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: rgb(106, 48, 145);
        }
        input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        .button {
            padding: 12px;
            background-color:rgb(106, 48, 145);
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .button:hover {
            background-color:rgb(149, 166, 221);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡ingrese el codigo que se le proporciono!</h1>
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

        <div>
        <form method="POST" action="{{ route('verificar', ['id'=>$id]) }}">
                @csrf
                <label for="codigo">Código de verificación:</label>
                <input type="text" id="codigo" name="codigo" maxlength="6" pattern="[0-9]+" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                <button class="button" type="submit">Verificar</button>
            </form>
        </div>
    </div>
</body>
</html>