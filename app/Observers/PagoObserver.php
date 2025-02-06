<?php

namespace App\Observers;

use App\Models\DetalleMatricula;
use App\Models\DetallePago;
use App\Models\Pago;
use App\Util\LogErrorManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class PagoObserver
{
    /**
     * Handle the pago "created" event.
     *
     * @param  \App\Pago  $pago
     * @return void
     */
    public function created(Pago $pago)
    {
        try {
            $matricula_id = $pago->matricula_id;
            $conceptopago = $pago->concepto_id;

            $fecha_actual = Carbon::now();

            if ($conceptopago = 2) {
                DetalleMatricula::where('matricula_id', $matricula_id)
                    ->update([
                        'pagado' => 1,
                        'fecha_confirmacion_pago' => $fecha_actual,
                    ]);
            } else {
                $ids_detalles_matricula = Request::instance()->input('ids_detalles_matricula');

                DetalleMatricula::whereIn('id', $ids_detalles_matricula)
                    ->where('matricula_id', $matricula_id)
                    ->update([
                        'pagado' => 1,
                        'fecha_confirmacion_pago' => $fecha_actual,
                    ]);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            //dd($e);
        } catch (Exception $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            //dd($e);
        }
    }

    /**
     * Handle the pago "updated" event.
     *
     * @param  \App\Pago  $pago
     * @return void
     */
    public function updated(Pago $pago)
    {
        DB::beginTransaction();
        try {
            $matricula_id = $pago->matricula_id;

            // Verificamos si existe el array 'idsDetalleMatriculaDelete' y si no está vacío
            $idsDetalleMatriculaDelete = Request::instance()->input('idsDetalleMatriculaDelete', []);

            // Si el array no está vacío, actualizamos solo esos registros
            if (!empty($idsDetalleMatriculaDelete)) {
                $this->updateDetalleMatricula($matricula_id, $idsDetalleMatriculaDelete);
            } else {
                // Si el array está vacío, actualizamos todos los registros relacionados con el pago
                $this->updateAllDetalleMatricula($matricula_id, $pago->id);
            }

            DB::commit();

        } catch (QueryException $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
        } catch (Exception $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
        }
    }

    protected function updateDetalleMatricula($matricula_id, array $idsDetalleMatriculaDelete)
    {
        DetalleMatricula::where('matricula_id', $matricula_id)
            ->whereIn('id', $idsDetalleMatriculaDelete)
            ->update([
                'pagado' => 0,
                'fecha_confirmacion_pago' => null,
            ]);
    }

    /**
     * Actualiza todos los detalles de matrícula relacionados con el pago
     */
    protected function updateAllDetalleMatricula($matricula_id, $pago_id)
    {
        $ids_pago_detalle_matricula = DetallePago::where('pago_id', $pago_id)->pluck('id_detalle_matricula');

        DetalleMatricula::where('matricula_id', $matricula_id)
            ->whereIn('id', $ids_pago_detalle_matricula)
            ->update([
                'pagado' => 0,
                'fecha_confirmacion_pago' => null,
            ]);
    }

    /**
     * Handle the pago "deleted" event.
     *
     * @param  \App\Pago  $pago
     * @return void
     */
    public function deleted(Pago $pago)
    {
        //
    }

    /**
     * Handle the pago "restored" event.
     *
     * @param  \App\Pago  $pago
     * @return void
     */
    public function restored(Pago $pago)
    {
        //
    }

    /**
     * Handle the pago "force deleted" event.
     *
     * @param  \App\Pago  $pago
     * @return void
     */
    public function forceDeleted(Pago $pago)
    {
        //
    }
}
