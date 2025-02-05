<?php

namespace App\Observers;

use App\Models\DetalleMatricula;
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

            $ids_detalles_matricula = Request::instance()->input('ids_detalles_matricula');
    
            $fecha_actual = Carbon::now();

            DetalleMatricula::whereIn('id', $ids_detalles_matricula)
            ->where('matricula_id', $matricula_id)
            ->update([
                'pagado' => 1,
                'fecha_confirmacion_pago' => $fecha_actual,
            ]);

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
        //
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
