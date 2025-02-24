<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Contrato;
use App\Models\DetalleContrato;
use App\Models\File;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContratoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Contrato());
        $perpage = $this->getLimitPagination($request);

        $contrato = Contrato::where($filters)
            ->with('alumno','matricula','file')
            ->orderBy('id', 'DESC')
            ->paginate($perpage);

        return $contrato;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return dd($request);
        $result = "";
        try {

            $contrato = $this->setModel(new Contrato(), $request);
            $contrato->created_usr  = $this->user->email;
            $contrato->save();

            if (isset($request->detalles) && is_array($request->detalles)) {
                foreach ($request->detalles as $detalle) {
                    $detalleContrato = new DetalleContrato();
                    $detalleContrato->contrato_id = $contrato->id; // Relacionar con el contrato
                    $detalleContrato->preciomes = $detalle['preciomes'];
                    $detalleContrato->fechapago = $detalle['fechapago'];
                    $detalleContrato->save();
                }
            }

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::errorMessage('Es posible que haya un contrato de pago registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Contrato::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $contrato = $this->setModel(Contrato::findOrFail($id), $request);
            $contrato->updated_usr = $this->user->email;
            $contrato->update();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que hay una contrato registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contrato $contrato)
    {
        //
    }

    public function selectsearch(Request $request)
    {
        $search = $request->input('search');

        $alumnos = Alumno::where('estado', 'A')
            ->select('id', 'nombre', 'apellido', 'dni', 'direccion', 'distrito', 'estado')
            ->whereRaw("concat(nombre, ' ', apellido, ' ', dni) like ?", ["%{$search}%"])
            ->with(['matriculas','matriculas.carrera','matriculas.ciclo','matriculas.detalles']) // Carga las matrículas y sus detalles
            ->orderBy('id', 'DESC')
            ->limit(15)
            ->get();

        return $alumnos;
    }

    private function setModel(Contrato $contrato, Request $request): Contrato
    {
        $contrato->alumno_id = $request->alumno_id;
        $contrato->matricula_id = $request->matricula_id;
        $contrato->domicilio = $request->domicilio;
        $contrato->fecha_inscripcion = Carbon::createFromFormat('d-m-Y', $request->fecha_inscripcion);
        $contrato->monto_promocional = $request->monto_promocional;
        $contrato->monto_preabonado = $request->monto_preabonado;
        $contrato->importe_restante = $request->importe_restante;
        $contrato->fecha_limitepago = Carbon::createFromFormat('d-m-Y', $request->fecha_limitepago);
        $contrato->duracion_modulo = $request->duracion_modulo;
        $contrato->cantidadveces = $request->cantidadveces;
        $contrato->duracionhoras = $request->duracionhoras;
        return $contrato;
    }


    public function pdfcontrato($id){
        $contrato = Contrato::with(['alumno','matricula','detalles','file',
        'pagos' => function ($query) {
            $query->whereHas('detalles', function ($q) {
                $q->where('concepto_id', 1);
            })->with(['detalles' => function ($q) {
                $q->where('concepto_id', 1);
            }]);
        }
        ])->find($id);
        /* return dd($contrato); */
        $pdf = Pdf::loadView('contrato.contrato', ['contrato' => $contrato]);
        return $pdf->stream('Contrato.pdf');
    }

    public function guardarfirma($id, Request $request)
    {
        $result = '';
        try {
            $contraro = Contrato::find($id);

            if (!$contraro) {
                return ResultManager::warningMessage('Contrato no encontrado');
            }

            if (!$request->has('datafirma') || empty($request->input('datafirma'))) {
                return ResultManager::warningMessage('No se ha proporcionado la firma');
            }

            // Guardar la firma y obtener el file_id
            $file_id = $this->saveFileMetadata($request->datafirma);

            // Asignar el file_id al empleado
            $contraro->file_id = $file_id;
            $contraro->save();

            $result = ResultManager::successMessage('Firma agregada correctamente al contrato');

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function saveFileMetadata($archivoBase64, $folder = 'public/firmas')
    {
        $image = str_replace('data:image/png;base64,', '', $archivoBase64);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '.png';

        // Guardar la imagen en la carpeta especificada
        $path = Storage::put($folder . '/' . $imageName, base64_decode($image));

        // Crear un nuevo registro en la tabla files
        $file = new File();
        $file->nombre = $imageName;
        $file->url_relative = 'public/firmas' . '/' . $imageName; // Ruta relativa del archivo
        $file->url_patch = url('storage/firmas/' . $imageName);  // URL completa del archivo
        $file->fecharegistro = now();
        $file->estado = 'A';
        $file->save();

        // Retornar el ID del archivo guardado
        return $file->file_id;
    }

    public function deletefirma($id)
    {
        $result = '';
        try {
            $contrato = Contrato::find($id);

            if (!$contrato) {
                return ResultManager::warningMessage('Empleado no encontrado');
            }

            $fileId = $contrato->file_id;
            //return dd($fileId);

            // Verificar si el empleado tiene un archivo asignado
            if ($fileId) {
                $this->removeFile($fileId);
            }

            $contrato->file_id = null;
            $contrato->save();

            $result = ResultManager::successMessage('Firma eliminada correctamente');

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    public function removeFile($fileId)
    {
        try {
            $file = File::find($fileId);

            if ($file) {
                // Ajustar la URL para eliminar el archivo desde el storage
                $url_remove = $file->url_relative;

                if (Storage::exists($url_remove)) {
                    Storage::delete($url_remove);
                }

                $file->delete();
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

}
