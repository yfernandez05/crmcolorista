<?php

namespace App\Http\Controllers;

use Auth;
use App\Util\FilterManager;
use Illuminate\Http\Request;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class BaseController extends Controller
{

    protected $user;
   
    public function __construct(array $exceptOptions = [])
    {
        $this->middleware('auth')->except($exceptOptions);

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function getLimitPagination(Request $request, $defaultPaginate = 10)
    {
        $limit = $defaultPaginate;

        if ($request->exists('perpage')) {
            $perpage = $request->input('perpage');
            if (is_numeric($perpage)) {
                $limit = (int) $perpage;
            }
        }

        return $limit;
    }

    public function getFilters(Request $request, Model $model = null, $defaultFilters = [RuleManager::FIELD_NAME_STATE, '=', RuleManager::ACTIVE_STATE])
    {
       return FilterManager::getFilters($request, $model, $defaultFilters);
    }

}
