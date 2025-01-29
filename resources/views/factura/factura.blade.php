<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

     <title>{{$factura->comprobante->nombrecomprobante}} - {{$factura->comprobante->serie}} - {{$factura->numero}}</title>
     <style type="text/css" media="all">
        html{font-family:sans-serif}body{font-size:11px}
        .container:after,.row:after{clear:both}.container{margin-right:0;margin-left:0}
        .col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,
        .col-xs-9{position:relative;min-height:1px;padding-right:15px;padding-left:15px}
        .col-xs-12{width:100%}.col-xs-11{width:91.66666667%}.col-xs-10{width:83.33333333%}.col-xs-9{width:75%}.col-xs-8{width:66.66666667%}.col-xs-7{width:58.33333333%}.col-xs-6{width:50%}.col-xs-5{width:41.66666667%}.col-xs-4{width:33.33333333%}.col-xs-3{width:25%}.col-xs-2{width:16.66666667%}.col-xs-1{width:8.33333333%}.table{width:100%;max-width:100%;margin-bottom:20px}.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th{padding:8px;line-height:1.42857143;vertical-align:top;border-top:1px solid #ddd}.table>caption+thead>tr:first-child>td,.table>caption+thead>tr:first-child>th,.table>colgroup+thead>tr:first-child>td,.table>colgroup+thead>tr:first-child>th,.table>thead:first-child>tr:first-child>td,.table>thead:first-child>tr:first-child>th{border-top:0;border-bottom:0}.table-bordered,.table-detalles{border:1px solid #ddd}.table .table{background-color:#fff}.table-condensed>tbody>tr>td,.table-condensed>tbody>tr>th,.table-condensed>tfoot>tr>td,.table-condensed>tfoot>tr>th,.table-condensed>thead>tr>td,.table-condensed>thead>tr>th{padding:5px}.table-bordered>thead>tr>td,.table-bordered>thead>tr>th{background:#fff}.table-detalles>thead>tr>td,.table-detalles>thead>tr>th{background:#F4F4F4;font-weight:700}p{margin:0 0 2px}.text-rigth{text-align:right}.titulo-empresa{font-size:18px;font-weight:600}.detalle-empresa{font-size:10px;font-weight:400}.contenedor-ruc{border:1px solid #343434;text-align:center;display:block;padding:5px 0;font-size:14px}.contenedor-ruc p{margin-bottom:4px}.pt-20{padding-top:20px}.pt-10{padding-top:10px}.mb-0{margin:0 0 2px}.pl-20{padding-left:20px}
        .titulo { font-size: 16px; text-align: center; }
        .titulo-empresa { font-size: 16px; font-family:sans-serif }

     </style>
</head>
<body>

    <div class="container">
        <div class="row pt-10">
            <div class="col-xs-7">
                <table>
                    <tr>
                        <td>
                            <img src="{{public_path('/images/comprobante/logo.png')}}" alt="Logo">
                            {{-- <img src="{{asset('images/logo-comprobante.png')}}" alt="Logo" > --}}
                        </td>

                    </tr>
                </table>
            </div>
        </div>
        <div>
            <p class="titulo-empresa">Dirección: Av. Petit Thouars 2166 Lima, Peru</p>
            <p class="titulo-empresa">Celular: 987525611</p>
        </div>
        <div class="row pt-10">
            <div class="col-xs-12">
                <div class="">
                    <p class="titulo">COMPROBANTE DE PAGO DE MATRICULA</p>
                </div>
            </div>
        </div>

        <div class="row pt-20" style="display: flex;
    flex-wrap: wrap;
    margin-right: -5px;
    margin-left: -5px;">
            <div class="col-xs-7">
                <table>
                    <tr>
                        <td><strong>Nombres y Apellidos</strong></td><td>: </td>
                        <td>{{$factura->matricula->alumno->nombrecompleto}}</td>
                    </tr>
                    <tr>
                        <td><strong>DNI</strong></td><td>: </td>
                        <td>{{$factura->matricula->alumno->dni}}</td>
                    </tr>
                    <tr>
                        <td><strong>Correo</strong></td><td>: </td>
                        <td>
                            {{$factura->matricula->alumno->correo}}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Celular</strong></td><td>: </td>
                        <td>
                            {{$factura->matricula->alumno->celular}}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Carrera</strong></td><td>: </td>
                        <td>
                            {{$factura->matricula->carrera->nombre}}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Ciclo</strong></td><td>: </td>
                        <td>
                            {{$factura->matricula->ciclo->nombre}}
                        </td>
                    </tr>

                </table>
            </div>

        </div>

        <div class="row pt-20">
            <div class="col-xs-12">
                <table class="table table-condensed table-detalles mb-0">
                    <thead>
                        <tr>

                            <td>DESCRIPCION</td>
                            <td class="text-rigth">P. UNITARIO</td>
                            <td class="text-rigth">IMPORTE</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($factura->detalles as $key=> $detalle)
                        <tr>
                            <td>{{$detalle->conceptopago->nombre}}</td>

                            <td class="text-rigth">{{$detalle->precio_unitario}}</td>
                            <td class="text-rigth">{{$detalle->importe}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

       <div class="row">
            <div class="col-xs-7">
            </div>
            <div class="col-xs-4">
                <div class="pl-20">
                    <table class="table table-condensed table-bordered" style="width: 100%; position: relative; left: 43em;">

                    @if($factura->comprobante->agregarigv)
                        <thead>
                            <tr>
                                <td class="text-rigth">Sub Total: S/</td>
                                <td class="text-rigth">{{$factura->detalles->subtotal}}</td>
                            </tr>

                            <tr>
                                <td class="text-rigth">Descuento</td>
                                <td class="text-rigth">{{$factura->descuento}}</td>
                            </tr>

                            <tr>
                                <td class="text-rigth" style="position: absolute; left: 502em;">Total: S/</td>
                                <td class="text-rigth" style="position: absolute; left: 502em;">{{$factura->subtotal}}</td>
                            </tr>
                        </thead>
                    @else
                        <thead>
                            <tr >
                                <td class="text-rigth">Sub Total: S/</td>
                                <td class="text-rigth">{{$factura->subtotal}}</td>
                            </tr>

                            <tr >
                                <td class="text-rigth" >Total: S/</td>
                                <td class="text-rigth" style="position: absolute; left: 502em;">{{$factura->subtotal}}</td>
                            </tr>
                        </thead>
                    @endif
                    </table>
                </div>

            </div>
        </div>

    </div>



</body>
</html>
