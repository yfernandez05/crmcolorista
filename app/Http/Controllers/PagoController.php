<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Pago;
use App\Models\File;
use Barryvdh\DomPDF\Facade as PDF;
use App\Util\RuleManager;
use App\Models\DetallePago;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class PagoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Pago());
        $perpage = $this->getLimitPagination($request);

        $pago = Pago::where($filters)
            ->with('matricula','matricula.alumno','comprobante','files')
            ->orderBy('id', 'DESC')
            ->paginate($perpage);

        return $pago;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = "";
        DB::beginTransaction();
        try {

            $pago = $this->setModel(new Pago(), $request);
            $pago->created_usr  = $this->user->email;
            $pago->codcomprobante = $request->codcomprobante;
            $pago->serie = $request->serie;
            $pago->numero = $request->numero;
            $pago->save();

            $this->saveAttachments($request, $pago);

            $detallePagos = [];

            foreach ($request->detalles as $detalle) {
                $detallePago = new DetallePago();
                $detallePago->concepto_id = $detalle['concepto_id'];
                $detallePago->precio_unitario = $detalle['precio_unitario'];
                $detallePago->descuento = isset($detalle['descuento']) ? $detalle['descuento'] : 0.00;
                $detallePago->importe = $detalle['importe'];
                $detallePago->nombre_numero_mensualidad = $detalle['nombre_numero_mensualidad'];
                $detallePago->id_detalle_matricula = isset($detalle['id_detalle_matricula']) && !empty($detalle['id_detalle_matricula']) ? $detalle['id_detalle_matricula'] : null;
                $detallePago->created_usr = $this->user->email;

                $detallePago->pago_id = $pago->id;
                $detallePagos[] = $detallePago;
            }

            $pago->detalles()->saveMany($detallePagos);

            DB::commit();
            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            //dd($e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            //dd($e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function saveAttachments($request, $pago)
    {

        if ($request->hasFile('selectedFiles')) {

            $nameFolder = 'public/Pagos';

            foreach ($request->file('selectedFiles') as $archivo) {
                // Obtener el nombre original del archivo y la extension
                $nombreArchivo = $archivo->getClientOriginalName();
                $extension = $archivo->getClientOriginalExtension();
                $nombreLimpio = preg_replace('/[^\w\-\.]/', '', pathinfo($nombreArchivo, PATHINFO_FILENAME));
                $nombreGuardado =  $nombreGuardado = $nombreLimpio . '_' . time() . '.' . $extension;


                // Guardar el archivo en una carpeta específica dentro de storage/app/public
                $archivo->storeAs($nameFolder, $nombreGuardado);


                // Crear un nuevo registro en la tabla de archivos
                $file = new File();
                $file->nombre = $nombreArchivo;
                $file->url_relative = 'storage/Pagos/' . $nombreGuardado; // Ruta relativa del archivo
                $file->url_patch = url($file->url_relative); // URL completa del archivo
                $file->fecharegistro = now();
                $file->estado = 'A';
                $file->save();

                // Asociar el archivo con el producto
                $pago->files()->attach($file->file_id);
            }
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       /* $pago = Pago::with('matricula','detalles','alumno')->find($id);
        return $pago;*/
        $pago = Pago::with(['matricula.alumno', 'detalles','matricula.detalles','matricula.carrera','matricula.ciclo','comprobante','files'])->find($id);
        return $pago;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        DB::beginTransaction();
        try {
            $pago = Pago::findOrFail($id);
            $pago->updated_usr = $this->user->email;
            $pago->subtotal = $request->subtotal;
            $pago->matricula_id = $request->matricula_id;
            $pago->codcomprobante = $request->codcomprobante;
            $pago->serie = $request->serie;
            if ($request->fileIdsRemove != '[]') { $this->removeFiles($request->fileIdsRemove); }
            if ($request->hasFile('selectedFiles')) { $this->saveAttachments($request, $pago); }
            $pago->update();

            $detallesExistentes = $pago->detalles()->get();

            $detallesRecibidosIds = collect($request->detalles)->pluck('id')->filter();

            foreach ($detallesExistentes as $detalleExistente) {
                if (!in_array($detalleExistente->id, $detallesRecibidosIds->toArray())) {
                    $detalleExistente->delete();
                }
            }

            foreach ($request->detalles as $detalle) {
                DetallePago::updateOrCreate(
                    ['id' => $detalle['id'] ?? null],
                    [
                        'concepto_id' => $detalle['concepto_id'],
                        'precio_unitario' => $detalle['precio_unitario'],
                        'descuento' => $detalle['descuento'] ?? 0.00,
                        'importe' => $detalle['importe'],
                        'nombre_numero_mensualidad' => $detalle['nombre_numero_mensualidad'],
                        'updated_usr' => $this->user->email,
                        'pago_id' => $pago->id,
                    ]
                );
            }

            DB::commit();
            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        } catch (Exception $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    public function removeFiles($fileId)
    {
        try {
            $fileIdsArray = json_decode($fileId);

            foreach ($fileIdsArray as $fileId) {
                $file = File::find($fileId);
                if ($file) {
                    $url_remove = str_replace('storage','public', $file->url_relative); //local link-storage
                    Storage::delete($url_remove);

                    $file->delete();
                }
            }
        } catch (Exception $e) {
            return dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        try {

            $pago = Pago::findOrFail($id);
            $pago->estado = RuleManager::DISABLED_STATE;
            $pago->update();

            $result = ResultManager::successMessage('Pago eliminado correctamente.');

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Pago $pago, Request $request): pago
    {
        $pago->matricula_id = $request->matricula_id;
        $pago->subtotal = $request->subtotal;
        $pago->detalle = $request->detalle;
        $pago->user_id = Auth()->user()->id;
        return $pago;
    }

    public function comprobante($id)
    {
        if (empty($id)) {
            return redirect()->route('spa');
        }

        $comprobante = Pago::with(['matricula.alumno', 'detalles','matricula.carrera','matricula.ciclo','comprobante'])->find($id);

        $pdf = PDF::loadView('factura.factura', ['factura' => $comprobante]);

        $nombrearchivo= "COMPROBANTE DE PAGO DE MATRICULA ". $comprobante->numero.".pdf";
        return $pdf->stream($nombrearchivo);
       // return $pdf->stream();
    }


    public function updateAttachment(Request $request, $id)
    {
        $result = "";
        DB::beginTransaction();
        try {

            $pago = Pago::findOrFail($id);

            // Eliminar archivos si es necesario
            if ($request->fileIdsRemove != '[]') { $this->removeFiles($request->fileIdsRemove); }
            // Guardar archivos
            if ($request->hasFile('selectedFiles')) { $this->saveAttachments($request, $pago); }
            $pago->update();

            DB::commit();
            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        } catch (Exception $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }
        return response()->json(['status' => true, 'message' => $result]);
    }

}
