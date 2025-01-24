<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnet de Acceso - {{$alumno->nombre}} {{$alumno->apellido}}</title>

    <style>
      @page {
        size: 54mm 85.6mm ; 
        margin: 0; 
    }
     body {
        width: 54mm;
        height: 85.6mm;
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #fff;
    }

        .id-card {
            background-color: #fff;
            /* border-radius: 5mm; */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            /* padding: 5mm; */
            box-sizing: border-box;
        }

        .header img {
          width: 100%;
          position: relative;
        }

        .photo {
          width: 20mm;
          height: 20mm;
          border-radius: 50%;
          overflow: hidden;
          border: 1mm solid #007bff;
          position: absolute;
          left: 45%;
          top: 5.5em; 
          transform: translate(-50%, -50%);
          border: 3mm solid white;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info {
            text-align: center;
            margin-top: 11mm;
            margin-bottom: 4mm;
        }

        .info h2 {
            margin: 0;
            font-size: 10pt;
        }

        .info p {
            margin: 0;
            font-size: 8pt;
            color: #555;
        }

        .qrcode {
            width: 23mm;
            height: 23mm;
            margin: 0mm auto;
        }

        .qrcode img {
            width: 100%;
            height: 100%;
        }
  </style>
</head>
<body>

<div class="id-card">
    <div class="header">
      <img src="{{ public_path('images/card/header-card.png') }}" alt="Fondo imagen"> 
      {{-- <img src="{{ asset('images/card/header-card.png') }}" alt="Fondo imagen"> --}} 
    </div>
    <div class="photo">
        <img src="{{public_path('images/card/logo-card.jpg')}}" alt="Logo" class="light-logo" />
        {{-- <img src="{{asset('images/card/logo-card.jpg')}}" alt="Logo" class="light-logo" /> --}} 
    </div>
    <div class="info">
        <h2>{{$alumno->nombre}}</h2>
        <h2>{{$alumno->apellido}}</h2>
        {{-- <h6 style="margin-top: 3em;">{{$alumno->correo}}</h6> --}}
    </div>
    <div class="qrcode">
        <img src="{{ $qryf }}" alt="QR Code">
    </div>
</div>
    
</body>
</html>