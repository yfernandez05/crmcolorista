<?php

namespace App\Util;

use Illuminate\Http\Request;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class FilterManager {
    
    public static function getFilters(Request $request, Model $model = null, $defaultFilters = [RuleManager::FIELD_NAME_STATE, '=', RuleManager::ACTIVE_STATE])
    {
        $filters = [];
        $showDeletedItems = false;

        if ($request->exists(RuleManager::FIELD_NAME_STATE)) {
            
            $valueState = $request->input(RuleManager::FIELD_NAME_STATE);

            if(is_bool($valueState))
                $showDeletedItems = $valueState;
            else 
                $showDeletedItems = !RuleManager::getIsActive($valueState);

        }
        

        foreach ($defaultFilters as $key => $value) {
            if (is_array($value)) {
                if (count($value) == 3) {

                    if($value[0] == RuleManager::FIELD_NAME_STATE && $showDeletedItems) 
                        continue;

                    $filters[] = $value;
                }

            } else {

                if($showDeletedItems) break;

                $filters[] = $defaultFilters;
                break;
            }
        }


        if (!is_null($model)) {

            $filterRules = $model->getFillable();

            foreach ($filterRules as $key => $value) {
                if ($request->exists($value)) {

                    $filterValue = $request->input($value);

                    if($value == RuleManager::FIELD_NAME_STATE) continue;


                    if (is_numeric($filterValue)) {

                        if ($filterValue > 0) {
                            $filters[] = [$value, '=', $filterValue];
                        }

                    } elseif (is_bool($filterValue)) {

                        $filters[] = [$value, '=', $filterValue];

                    } elseif (is_string($filterValue)) {

                        $strinValue = trim($filterValue);

                        if (strlen($strinValue) > 0) {
                            $filters[] = [$value, 'LIKE', '%' . $strinValue . '%'];
                        }
                    }

                }
            }
        }

        return $filters;
    }
}