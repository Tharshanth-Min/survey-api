<?php

namespace App\Http\Controllers\FileUpload;

use App\Exports\SurveysExport;
use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FileUploadController extends Controller {

    public function uploadSurveys(Request $request) {

        try {
            $file_names = explode(",",$request->fileNames);
            $user_id = $request->userId;
            $dis = $request->dis;
            $ds = $request->ds;
            $gn = $request->gn;
            $lan = $request->lan;

            foreach($file_names as $name) {

                $replaced_file = str_replace('.', '_', $name);
                $custom_file_name = $request->file($replaced_file)->getClientOriginalName();

                if (!Storage::exists('/surveys/'.$custom_file_name)) {
                    $totalSurvey = $this->findNumberOfSurveysByUser($user_id);
                    $request->file($replaced_file)->storeAs('surveys', $custom_file_name);
                    $unique_id = 'UID'. $user_id.'/'.$totalSurvey .'/'. date('Y-m-d') ;

                    Survey::create([
                        'user_id' => $user_id,
                        'unique_id' => $unique_id,
                        'name' => $custom_file_name,
                        'dis' => $dis,
                        'ds' => $ds,
                        'gn' => $gn,
                        'language' => $lan,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            return response()->json([
                'status' => '200',
                'fileNames' => $file_names
            ]);


       }catch(\Exception $exception) {
           return response()->json([
               'status' => '500',
               'error' => 'uploaded'
           ], 500);
       }
    }

    public function checkNullValue($response, $key) {
        if(isset($response[$key])) {
            return $response[$key];
        }else {
            return "Not given";
        }
    }

    public function saveJSONToExcel(Request $request) {
        try {
            $name = str_replace(',', ' ', $request->surveys);
            $files = explode(" ", $name);

            $finalResponse = [];
            $columns = array(
                "Unique Id", "Created at", "language","DIS", "DS", "GN" , "Respondent Name", "Respondent Contact",
                "Respondent Address"
            );
            $data = (object)[];


            foreach($files as $file) {
                $path = storage_path() . "/app/surveys/" .$file;
                $jsonData = json_decode(file_get_contents($path), true);

                $data->unique_id = $this->checkNullValue($jsonData[0], 'uniqueId');
                $data->created_at = $this->checkNullValue($jsonData[0], 'createdAt');
                $data->language = $this->checkNullValue($jsonData[0], 'LANGUAGE');
                $data->DIS = $this->checkNullValue($jsonData[0], 'DIS');
                $data->DS = $this->checkNullValue($jsonData[0], 'DS');
                $data->GN = $this->checkNullValue($jsonData[0], 'GN');
                $data->respondent_name = $this->checkNullValue($jsonData[0], 'respondentName');
                $data->contact_number = $this->checkNullValue($jsonData[0], 'respondentContact');
                $data->address = $this->checkNullValue($jsonData[0], 'respondentAddress');

                unset($jsonData[0]);

                foreach($jsonData as $ky=>$response) {
                    $keys =  $this->generateKyes();
                    foreach($keys as $key) {
                        $newKey = "Member". $ky .'/'. $key;
                        $data->$newKey = $this->checkNullValue($response, $key);
                        array_push($columns, $newKey);
                    }
                }
                array_push($finalResponse, $data);
                $data = (object)[];
            }

            $export = new SurveysExport($finalResponse, array_unique($columns));

            return Excel::download($export , 'all_surveys.xlsx');

       }catch(\Exception $error) {
           return response()->json([
               "status" => "500",
               "message" => "Something went wrong"
           ], 500);
       }

    }

    public function downloadAllFiles() {
        try {

            $files = Storage::allFiles("surveys");
            $finalResponse = [];
            $columns = array(
                "Unique Id", "Created at", "language","DIS", "DS", "GN" , "Respondent Name", "Respondent Contact",
                "Respondent Address"
            );
            $data = (object)[];


            foreach($files as $file) {
                $path = storage_path() . "/app/" .$file;
                $jsonData = json_decode(file_get_contents($path), true);

                $data->unique_id = $this->checkNullValue($jsonData[0], 'uniqueId');;
                $data->created_at = $this->checkNullValue($jsonData[0], 'createdAt');
                $data->language = $this->checkNullValue($jsonData[0], 'LANGUAGE');
                $data->DIS = $this->checkNullValue($jsonData[0], 'DIS');
                $data->DS = $this->checkNullValue($jsonData[0], 'DS');
                $data->GN = $this->checkNullValue($jsonData[0], 'GN');
                $data->respondent_name = $this->checkNullValue($jsonData[0], 'respondentName');
                $data->contact_number = $this->checkNullValue($jsonData[0], 'respondentContact');
                $data->address = $this->checkNullValue($jsonData[0], 'respondentAddress');

                unset($jsonData[0]);

                foreach($jsonData as $ky=>$response) {
                    $keys =  $this->generateKyes();
                    foreach($keys as $key) {
                        $newKey = "Member". $ky .'/'. $key;
                        $data->$newKey = $this->checkNullValue($response, $key);
                        array_push($columns, $newKey);
                    }
                }
                array_push($finalResponse, $data);
                $data = (object)[];
            }

            $export = new SurveysExport($finalResponse, array_unique($columns));

            return Excel::download($export , 'all_surveys.xlsx');

        }catch(\Exception $error) {
            return response()->json([
                "status" => "500",
                "message" => "Something went wrong"
            ], 500);
        }

    }

    private function generateKyes() {
        $keys =  [
            "id",
            "name",
            "2_1",
            "2_2",
            "2_3",
            "2_4",
            "2_5",
            "2_6",
        ];

        return $keys;
    }

    public function findNumberOfSurveysByUser($user_id) {
        if($user = User::select('id', 'number_of_surveys')
            ->where('id', $user_id)->first()) {
            $total = $user->number_of_surveys + 1;

            $user->update([
                'number_of_surveys' => $total
            ]);
            return $total;
        }
    }

}
