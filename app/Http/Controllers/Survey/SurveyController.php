<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseCollection;
use App\Models\Survey;
use Illuminate\Http\Request;


class SurveyController extends Controller {

    public function getAll(Request $request) {

        try {
            if ($request->has('search_query')) {
                $q = $request->input('search_query', 'null');
                $surveys = Survey::where('name', 'LIKE', '%'.$q.'%')
                    ->paginate($request->per_page);
                return new ResponseCollection($surveys);
            }
        }catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "Something went wrong "
            ], 500);
        }
    }
}
