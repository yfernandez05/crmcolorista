<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
    <style>


        /* //// */

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            color: #333;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0px;
            background-color: #5757da;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            margin: 10px auto;
        }
        h1, h2 {
            color: #2b52c2;
        }
        .qr-code {
            border-radius: 5px;
            display: block;
            margin: 0 auto;
            max-width: max-content;
            margin-bottom: 0.8em;
        }
        .qr-code img, .qr-code svg {
            border-radius: 10px;
            display: block;
            margin: 0 auto;
            max-width: 250px !important;
            /* padding: 15px; */
            background-color: white;
        }
        .qr-code h3{
            margin-bottom: 0px;
            color: white;
            text-shadow: 2px 2px 0px #ac604d;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }
        .footer a {
            color: #77DD77;
            text-decoration: none;
        }
        .cont-img img{
            width: 100%;
            max-width: 100%;
        }
        .cont-img{
            color: #00c6ff;
            font-family: system-ui;
            font-weight: 500;
            font-size: 2em;
        }
        .cont-img p{
            margin: 0;
        }
        .center{
            text-align: center;
        }
        
    </style>
</head>
<body>
<div class="container">
    <div class="cont-img">
        <img src="{{ $message->embed('images/mailing_bienvenida_go_1_head.png') }}" alt="Gracias por Registrarte" class="img-fluid">
        <span class="center"><p>¡Te esperamos en el {{ $cliente->colegio }}!</p></span>
        <img src="{{ $message->embed('images/mailing_bienvenida_go_1_body.png') }}" alt="Gracias por Registrarte" class="img-fluid">
        <div class="qr-code mb-2">
            <img src="{!! $message->embedData($qrCode, 'QR_Feria_2024.png', 'image/png') !!}" alt="QR Code"> <!-- prod --> 
        </div>
        <img src="{{ $message->embed('images/mailing_bienvenida_go_1_footer.png') }}" alt="Gracias por Registrarte" class="img-fluid">
    </div>
        
    </div>
    <div class="center">
        <!-- Pie de página -->
        <div class="footer">
            © Universidad Científica del Sur {{ date('Y') }} - Todos los Derechos Reservados
            <a href="https://www.cientifica.edu.pe/" target="_blank"><strong>Universidad Científica del Sur</strong></a>
        </div>
    </div>
</body>
</html>