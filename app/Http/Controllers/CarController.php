<?php

namespace App\Http\Controllers;

use App\Models\CarGeneration;
use App\Models\CarMark;
use App\Models\CarModel;
use App\Models\CarType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends AppController
{

    public function carMarkList(Request $request) {

        if (isset($request->type_id) && is_numeric($request->type_id)) {
            $carMarks = null;
            if ($request->type_id == 1) {
                $carMarks = CarMark::where([
                    ['car_marks.is_active', 1],
                    ['car_marks.car_type_id', $request->type_id],
                    ['car_generations.year_begin', '>=', 1990],
                    ['car_generations.year_end', '<=', 2020],
                ])
                    ->leftJoin('car_models', 'car_marks.id', '=', 'car_models.car_mark_id')
                    ->leftJoin('car_generations', 'car_models.id', '=', 'car_generations.car_model_id')
                    ->select('car_marks.id as id', 'car_marks.name as name')
                    ->groupBy('car_marks.id', 'car_marks.name')
                    ->orderBy('car_marks.rank', 'asc')->orderBy('car_marks.name', 'ASC')
                    ->get();
            }
            else if ($request->type_id == 2 || $request->type_id == 6 || $request->type_id == 7 || $request->type_id == 8 ) {
                $carMarks = CarMark::where([
                    ['car_marks.is_active', 1],
                    ['car_marks.car_type_id', $request->type_id],
                ])
                    ->select('car_marks.id as id', 'car_marks.name as name')
                    ->groupBy('car_marks.id', 'car_marks.name')
                    ->orderBy('car_marks.rank', 'asc')->orderBy('car_marks.name', 'ASC')
                    ->get();
            }

            if (count($carMarks) > 0) {
                $carMarks = CarMark::setLogo($carMarks);
                return $carMarks;
            }
            else {
                return ['id' => 0,'name' => __('Unknown Mark')];
            }

        }
        else {
            abort(422, 'Unprocessable Entity.');
        }
    }

    public function carModelList(Request $request) {
        if (isset($request->mark_id) && is_numeric($request->mark_id)) {

            $carModels = CarModel::where(['car_mark_id' => $request->mark_id])
                    ->select('id','name')
                    ->orderBy('rank', 'asc')->orderBy('name', 'ASC')->get();

            if (count($carModels) > 0) {
                return $carModels ;
            }
            else {
                return ['id' => 0,'name' => __('Unknown Model')];
            }
        }
        else {
            abort(422, 'Unprocessable Entity.');
        }
    }

    public function carYearList(Request $request) {
        if (isset($request->model_id) && is_numeric($request->model_id)) {

            $carYears = CarGeneration::where(['car_model_id' => $request->model_id])
                ->select(DB::raw("min(year_begin) as year_begin,max(year_end) as year_end") )
                ->groupBy('car_model_id')
                ->first();

            return isset($carYears->year_begin) ? $carYears : ['year_begin' => 1950, 'year_end' => Carbon::now()->year];
        }
        else {
            abort(422, 'Unprocessable Entity.');
        }

    }
}
