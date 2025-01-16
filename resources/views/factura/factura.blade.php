<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

     <title>{{$factura->comprobante->nombrecomprobante}} - {{$factura->serie}} - {{$factura->numero}}</title>
     <style type="text/css" media="all">
        html{font-family:sans-serif}body{font-size:11px}.container:after,.row:after{clear:both}.container{margin-right:0;margin-left:0}.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{float:left;position:relative;min-height:1px;padding-right:15px;padding-left:15px}.col-xs-12{width:100%}.col-xs-11{width:91.66666667%}.col-xs-10{width:83.33333333%}.col-xs-9{width:75%}.col-xs-8{width:66.66666667%}.col-xs-7{width:58.33333333%}.col-xs-6{width:50%}.col-xs-5{width:41.66666667%}.col-xs-4{width:33.33333333%}.col-xs-3{width:25%}.col-xs-2{width:16.66666667%}.col-xs-1{width:8.33333333%}.table{width:100%;max-width:100%;margin-bottom:20px}.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th{padding:8px;line-height:1.42857143;vertical-align:top;border-top:1px solid #ddd}.table>caption+thead>tr:first-child>td,.table>caption+thead>tr:first-child>th,.table>colgroup+thead>tr:first-child>td,.table>colgroup+thead>tr:first-child>th,.table>thead:first-child>tr:first-child>td,.table>thead:first-child>tr:first-child>th{border-top:0;border-bottom:0}.table-bordered,.table-detalles{border:1px solid #ddd}.table .table{background-color:#fff}.table-condensed>tbody>tr>td,.table-condensed>tbody>tr>th,.table-condensed>tfoot>tr>td,.table-condensed>tfoot>tr>th,.table-condensed>thead>tr>td,.table-condensed>thead>tr>th{padding:5px}.table-bordered>thead>tr>td,.table-bordered>thead>tr>th{background:#fff}.table-detalles>thead>tr>td,.table-detalles>thead>tr>th{background:#C4D79B;font-weight:700}p{margin:0 0 2px}.text-rigth{text-align:right}.titulo-empresa{font-size:18px;font-weight:600}.detalle-empresa{font-size:10px;font-weight:400}.contenedor-ruc{border:1px solid #343434;text-align:center;display:block;padding:5px 0;font-size:14px}.contenedor-ruc p{margin-bottom:4px}.pt-20{padding-top:20px}.pt-10{padding-top:10px}.mb-0{margin:0 0 2px}
        .pl-20{padding-left:20px}
        .pt-20{
            padding-top: 20px;
        }
        .titulo{
            font-size: 16px;
        }
        .pt-10{
            padding-top: 10px;
        }
        .pt-30{
            padding-top: 30px;
        }
        .colorp{
            color:  #229954;
            font-size: 10px;
        }
        .colorred{
            color: red;
            text-align: right;
            padding-right:20%
        }
        .text-center{
            text-align: center;
        }
        .fondsized{
            font-size:9px;
        }
        .subtitulo{
            font-size: 13px;
        }

        .mr{
            padding-right: 20px;
        }

        /*herader - footer*/
@page{
    margin: 2cm 0.5cm 3cm 0.5cm;
}

        #header{
            position: fixed;
            top: -2cm;
            left: 0cm;
        }
        #footer{
            position: fixed;
            bottom: 0cm;
            left: 0cm;
        }

        .saltopagina{
            page-break-after:always;
        }

     </style>
</head>
<body>

    <div id="header">
        <p>Coloristas</p>
        <!-- <img style="position:absolute;top:-0.50in;left:-0.50in;width:8.30in; " src="{{public_path('/images/cabecera.png')}}"> -->
        {{-- <img style="position:absolute;top:-0.50in;left:-0.50in;width:8.30in;" src="{{asset('/images/factura/cabecera.png')}}"> --}}

    </div>

</body>
</html>
