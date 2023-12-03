<?php
namespace App\Services;

use Illuminate\Http\Request;

class FilterService {
    static $operators = [
        'eq' => '=',
        'gt' => '>',
        'lt' => '<',
        'gte' => '>=',
        'lte' => '<=',
        'like' => 'like'
    ];

    public static function makeFilter(Request $request, array $params, bool $transform = false) {
        $eloquentQuery = [];
        $queryParams = [];

        if($transform) {
            foreach($request->query() as $field => $val) {
                $newField = explode("_",$field);
                if(count($newField) <= 1){
                    $queryParams[$field] = $val;
                } else {
                    $queryParams[$newField[1]] = $val;
                }
            }
        } else {
            $queryParams = $request->query();
        }

        foreach($params as $param) {
            if(!isset($queryParams[$param])){
                continue;
            }

            $queryFilters = $queryParams[$param];

            if(!isset($queryFilters)){
                continue;
            }

            foreach($queryFilters as $filter => $value) {
                if(!isset(self::$operators[$filter])){
                    continue;
                }
                
                $eloquentQuery[] = [$param, self::$operators[$filter], $value]; 
            }
        }

        return $eloquentQuery;
    }
}