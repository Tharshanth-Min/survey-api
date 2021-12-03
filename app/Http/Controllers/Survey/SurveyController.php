<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseCollection;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class SurveyController extends Controller {

    public function getAll(Request $request) {

        try {
            $files = Storage::allFiles("surveys");

            return response()->json([
                'status' => 200,
                'surveys' => count($files)
            ], 200);

//            if ($request->has('search_query')) {
//                $q = $request->input('search_query', 'null');
//                $surveys = Survey::where('name', 'LIKE', '%'.$q.'%')
//                    ->paginate($request->per_page);
//                return new ResponseCollection($surveys);
//            }
        }catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "Something went wrong "
            ], 500);
        }
    }
}
