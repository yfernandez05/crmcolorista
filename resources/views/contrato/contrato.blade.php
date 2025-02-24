<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato</title>

    <style>
     body {
        margin: 0;
        font-family: Arial, sans-serif;
        justify-content: center;
        align-items: center;
        background-color: #fff;
        font-size: 0.8em;
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
        .text-center{
            text-align: center;
        }
        .col-12{
            width: 100%;
        }




  </style>
</head>
<body>

<div class="id-card">
    <div class="header" style="text-align: justify;">
        <h2 class="text-center">ACUERDO DE ESTUDIOS</h2>
        <h2>Términos de contrato</h2>
        <p style="text-align: justify;">Conste por el documento el acuerdo de estudios celebran, de una parte ESCUELA DE COLORISTAS, 
            con RUC Nº 20608066790, con domicilio en Av. Petittuars 2166, 2do nivel – Lince, Provincia y 
            Departamento de Lima y de la otra parte a <b style="text-transform: uppercase;">{{$contrato->alumno->nombrecompleto}}</b> identificado 
            con DNI Nº <b>{{$contrato->alumno->dni}}</b> y con domicilio en <b style="text-transform: uppercase;">{{$contrato->domicilio}}</b> a 
            quien en adelante se le denominara EL ESTUDIANTE en los siguientes términos y condiciones.</p>

        <h2 class="text-center" style="margin-top: 20px;">ESCUELA DE COLORISTAS</h2>
        <h2>CLÁUSULA PRIMERA.</h2>
        <p>Escuela de coloristas es una escuela especializada en color y diseño de mechas creativas de 
            tendencia brasileña, y estructura de corte capilar ;  cuya misión es formar a los alumnos  
            capaces de crear , diseñar color de tendencia, por ende, la escuela ofrece un staff de 
            docentes altamente capacitados logrando que el alumno sea muy competitivo.</p>


        <h2 class="text-center" style="margin-top: 20px;">PROGRAMA CONTRATADO</h2>   
        <h2>CLÁUSULA SEGUNDA.</h2>
        <p><b>EL ESTUDIANTE</b> con la fecha <b>{{$contrato->fecha_inscripcion}}</b> procede a la inscripción del programa <b>{{$contrato->matricula->carrera->nombre}}</b> 
        que tendrá una duración de <b>{{$contrato->duracion_modulo}}</b> modulos, los cuales constan de <b>{{$contrato->cantidadveces}}</b> veces a la semana, y tendrá un horario de <b>{{$contrato->duracionhoras}}</b> horas.
            </p>


        <h2 class="text-center" style="margin-top: 20px;">RETRIBUCIÓN Y FORMA DE PAGO</h2>
        <h2>CLÁUSULA TERCERA.</h2>
        <p>El programa de <b>{{$contrato->matricula->carrera->nombre}}</b> contratado tiene los montos siguientes y cronograma de pago:</p>
       

        <p>
            <strong>Matrícula:</strong>
            @if ($contrato->pagos->isNotEmpty())
                @foreach ($contrato->pagos as $pago)
                    @foreach ($pago->detalles as $detalle)
                        S/. {{ number_format($detalle->importe, 2) }}
                    @endforeach
                @endforeach
            @else
                Costo S/. 0.00
            @endif
        </p>



        @php
            function numeroEnPalabras($num) {
                $palabras = ['Primer', 'Segundo', 'Tercer', 'Cuarto', 'Quinto', 'Sexto', 'Séptimo', 'Octavo', 'Noveno', 'Décimo'];
                return $palabras[$num - 1] ?? $num . '°'; // Si el número es mayor a 10, usa "11° pago", "12° pago", etc.
            }
        @endphp

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;" border="1">
            <thead>
                <tr>
                    <th style="padding: 8px; text-align: left;">Pago</th>
                    <th style="padding: 8px; text-align: left;">Monto (S/)</th>
                    <th style="padding: 8px; text-align: left;">Fecha de Pago</th>
                </tr>
            </thead>
            <tbody>
                @php $contador = 1; @endphp
                @foreach(json_decode($contrato->detalles, true) as $detalle)
                <tr>
                    <td style="padding: 8px;">{{ numeroEnPalabras($contador) }} pago</td>
                    <td style="padding: 8px;">S/ {{ number_format($detalle['preciomes'], 2) }}</td>
                    <td style="padding: 8px;">{{ \Carbon\Carbon::parse($detalle['fechapago'])->format('d/m/Y') }}</td>
                </tr>
                @php $contador++; @endphp
                @endforeach
            </tbody>
        </table>

        <br>
        Si el <b>ESTUDIANTE</b> adquirió la promoción deberá pagar la totalidad promocional, 
        equivalente al monto de <b>{{$contrato->monto_promocional}}</b> - antes de iniciar las 
        clases, si en caso este monto no fuera cancelado en la fecha pactada, y existiera un monto 
        pre abonado, este importe será considerado para la modalidad de pago en cuotas.

        <p>Monto pre abonado: <b>{{$contrato->monto_preabonado}}</b></p>
        <p>Importe restante: <b>{{$contrato->importe_restante}}</b></p>
        <p>Fecha límite de pago: <b>{{$contrato->fecha_limitepago}}</b></p>
        <p>Los pagos realizados con tarjeta tendrán el recargo del 5% por comisión.</p>
        <p>Todo pago efectuado no es reembolsable</p>
        <p>Si se tuviera un pago promocional cancelado y el alumno(a) no asiste a clases, es responsabilidad 
            de este mismo, el monto no será reembolsable y será considerado como penalidad como uso de su vacante.</p>


        <h2 class="text-center" style="margin-top: 20px;">MORA</h2>
        <h2>CLÁUSULA CUARTA.</h2>
        <p>Los pagos se realizan antes de ingresar al aula y según el cronograma de pagos informado. La 
            falta de pago inhabilita al alumno el ingreso al aula y la pérdida de clases. La pérdida de 
            clases es responsabilidad del alumno, no de la escuela. Adicional se le cargara el monto de 50.00 
            nuevos soles como sanción por la mora del pago indicado, todos los pagos deberán ser validados en administración.</p>


        <h2 class="text-center" style="margin-top: 20px;">CONFIDENCIALIDAD</h2>
        <h2>CLÁUSULA QUINTA.</h2>
        <p><b>EL ESTUDIANTE</b> se compromete a la más absoluta confidencialidad de los documentos de clases que maneja por 
        encargo de la ESCUELA DE COLORISTAS, además de prestar con honestidad y responsabilidad la labor encomendada 
        desde su inicio hasta la culminación. El incumplimiento y/o violación de la obligación de confidencialidad 
        establecida en esta cláusula, constituye causal de una amonestación y llamada de atención y/o retiro de la institución.</p>


        <h2 class="text-center" style="margin-top: 20px;">TARDANZA</h2>
        <h2>CLÁUSULA SEXTA.</h2>
        <p><b>EL ESTUDIANTE </b> tendrá una tolerancia de 10 minutos despues de la hora de entrada. En el caso de 
        las clases práctica el alumno perderá la primera hora del tema introductorio a la técnica, si la tardanza 
        del alumno(a) supera 1 hora, perdería la clase por su propia responsabilidad.</p>
        <p><b>Es responsabilidad del alumno tener puntualidad y no ser perjudicado.</b></p>


        <h2 class="text-center" style="margin-top: 20px;">FALTAS</h2>
        <h2>CLÁUSULA SEPTIMA.</h2>
        <p><b>EL ESTUDIANTE </b> al acumular 3 tardanzas, equivaldrá a 1 falta. Las faltas que realice el alumno no serán recuperadas a excepción de algún caso fortuito.</p>
        <p>Las faltas solo serán justificadas por razones médicas y por ende serán libres de las sanciones.</p>


        <h2 class="text-center" style="margin-top: 20px;">RECUPERACIONES</h2>
        <h2>CLÁUSULA OCTAVA.</h2>
        <p>Todo sistema de recuperación sea escrito, virtual o práctico tendrá un valor de costo adicional, el cual deberá ser cancelado antes para poder hacer la programación y coordinación respectiva:</p>
        <p>-	Recuperación de clases prácticas – Mechas: S/.250.00</p>
        <p>-	Recuperación prácticas – Colorimetría: S/.120.00</p>
        <p>-	Recuperación teóricas virtuales – 3h: S/.100.00</p>


        <h2 class="text-center" style="margin-top: 20px;">UNIFORME</h2>
        <h2>CLÁUSULA NOVENA.</h2>
        <p>El uso de UNIFORME es OBLIGATORIO</p>
        <p>El Alumno deberá asistir con su uniforme correspondiente (MANDIL + POLO) adquirido en nuestra escuela.</p>
        <p>Se permite el uso de shorts y pantalones, pero estos deben ser de color negro o blanco.</p>
        <p>En las aulas prácticas se usará la CAPA DE LA ESCUELA.</p>


        <h2 class="text-center" style="margin-top: 20px;">MATERIALES</h2>
        <h2>CLÁUSULA DECIMA.</h2>
        <p><b>EL ESTUDIANTE</b> está obligado a traer sus materiales de uso y herramientas de trabajo para realizar el servicio a su modelo, es responsabilidad de este contar con todos ellos.
        <p>-	La Escuela no tiene la obligación de prestar dichas herramientas.</p>
        <p>-	La Escuela no se responsabiliza por perdidas  tiene la obligación de prestar dichas herramientas.</p>


        <h2 class="text-center" style="margin-top: 20px;">MODELOS</h2>
        <h2>CLÁUSULA DECIMA PRIMERA.</h2>
        <p><b>EL ESTUDIANTE</b>es responsable de traer a su modelo acorde a la técnica que se aplicara en curso. </p>
        <p>El Alumno debe dejar en claro a la modelo que no elige el color, ni el diseño de mechas. Si este fuera el caso en su cabello, ya que este será de acuerdo al diagnóstico y estado de su cabello. Las modelos deberán tener el tiempo disponible para que el alumno realice el término correcto y toma de fotos, para esto deberán de firmar la hoja de consentimiento.</p>


        <h2 class="text-center" style="margin-top: 20px;">AULAS</h2>
        <h2>CLÁUSULA DECIMA SEGUNDA.</h2>
        <p>-	Las aulas teóricas tendrán un aforo mínimo de 8 alumnos y un aforo máximo de 15 alumnos.</p>
        <p>-	La continuidad del aula, se mantendrá con la permanencia mínima de 8 alumnos en lista, durante el ciclo.</p>
        <p>-	Si durante el ciclo existiera reducción de alumnos y esta cantidad fuera menor a la de 6 alumnos, el grupo tomara una pausa y será reincorporado en un nuevo grupo, la fecha del nuevo inicio será informada por administración.</p>


        <h2 class="text-center" style="margin-top: 20px;">PRESENTACIÓN DEL TRABAJO FINAL</h2>
        <h2>CLÁUSULA DECIMA TERCERA.</h2>
        <p>El Alumno deberá plasmar lo aprendido en su modelo y debe ser responsable de ella hasta el último momento, esta debe de contar con:</p>
        <p>-	Técnica aprendida aplicada.</p>
        <p>-	Styling.</p>
        <p>-	Maquillada.</p>


        <h2 class="text-center" style="margin-top: 20px;">SOBRE LA CERTIFICACIÓN</h2>
        <h2>CLÁUSULA DECIMA CUARTA.</h2>
        <p>-	La certificación dada a nombre de la escuela, solo será entregada a los alumnos que hayan culminado “<b>COMPLETA Y SATISFACTORIAMENTE</b>” su carrera o especialización según corresponda.</p>
        <p>-	Si el alumno tuviera clases o notas pendientes, este deberá realizar las recuperaciones correspondientes asumiendo el pago respectivo. Estas recuperaciones son <b>OBLIGATORIAS</b> para obtener la certificación de su curso.</p>
     


        <h2 class="text-center" style="margin-top: 20px;">SOBRE EL PROCESO DE CERTIFICACIÓN</h2>
        <h2>CLÁUSULA DÉCIMA QUINTA.</h2>
        <p>-	En el caso de la carrera de colorimetría, el alumno tendra que tener sus 2 exámenes finales prácticos y teóricos aprobados, asi como la entrega de su carta de color.</p>
        <p>-	En el caso de los alumnos de especialización, la certificación se hará con un trabajo final realizado por el alumno de manera externa, donde tendrá que presentar una modelo a la escuela de forma presencial o tambien podrá presentar fotos y videos de todo el proceso realizado.</p>
     


        <h2 class="text-center" style="margin-top: 20px;">SOBRE EL COSTO DEL CERTIFICADO</h2>
        <h2>CLÁUSULA DECIMA SEXTA.</h2>
        <p>La escuela brinda 2 tipos de certificado:</p>
        <p>-	A nombre de la Escuela de Coloristas S/. 120.00</p>
        <p>-	A nombre de Nación S/. 150.00(El tiempo de trámite de este es de 7 a 15 dias hábiles despues de haberse hecho el abono correspondiente)</p>
     


        <h2 class="text-center" style="margin-top: 20px;">FIRMA DE LAS PARTES</h2>
        <h2>CLÁUSULA DECIMA SEPTIMA.</h2>
        <p>Considerando que, a la fecha de suscripción del presente documento el representante de la <b>ESCUELA DE COLORISTAS</b> y <b>EL ESTUDIANTE</b> declaran que será posible dar su conformidad al contenido del presente <b>ACUERDO DE ESTUDIO</b> en señal de aceptación.</p>
        <p>Se firma este contrato por duplicado con la previa ratificación de las partes de todas y cada una de las cláusulas del mismo.</p>


        <table style="width: 100%; margin-top: 50px; text-align: center; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; padding: 20px;">
                    <img src="{{ public_path('images/firma-colorista.png') }}" alt="Firma del empleado" class="img-fluid" style="max-width: 200px; height: auto; margin-bottom:0px">
                    <hr style="width: 80%; border: 1px solid black;">
                    <strong>ESCUELA DE COLORISTAS</strong>
                
                    
                </td>
                <td style="width: 50%; padding: 20px;">
                    @if(!empty($contrato->file))
                        <img src="{{ public_path('storage/firmas/' . $contrato->file->nombre) }}" alt="Firma del empleado" class="img-fluid" style="max-width: 305px; height: auto; margin-bottom:0px;margin-top:107px">
                    @endif                
                    <hr style="width: 80%; border: 1px solid black;">
                    <strong style="text-transform: uppercase;">{{$contrato->alumno->nombrecompleto}}</strong>
                </td>
            </tr>
        </table>



        

        
</div>
    
</body>
</html>