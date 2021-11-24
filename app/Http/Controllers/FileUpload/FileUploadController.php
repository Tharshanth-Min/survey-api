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
        // try {
        $token = $request->token;

        if($token) {


            $name = str_replace(',', ' ', $request->surveys);
            $files = explode(" ", $name);

            $finalResponse = [];
            $columns = array(
                "Unique Id", "Created at", "language", "DIS", "DS", "GN", "Respondent Name", "Respondent Contact",
                "Respondent Address", "Total Members"
            );
            $data = (object)[];


            foreach ($files as $file) {
                $path = storage_path() . "/app/surveys/" . $file;
                $jsonData = json_decode(file_get_contents($path), true);

                $surveyLength = count($jsonData);

                if($surveyLength < 9) {
                    $array2 = array();
                    $length = 9 - $surveyLength;
                    for($i = 0; $i < $length ; $i ++) {
                        array_push($array2, ["" => ""]);
                    }
                    $result = array_merge($jsonData, $array2);

                    $jsonData =  $result;
                }

                $data->unique_id = $this->checkNullValue($jsonData[0], 'uniqueId');
                $data->created_at = $this->checkNullValue($jsonData[0], 'createdAt');
                $data->language = $this->checkNullValue($jsonData[0], 'LANGUAGE');
                $data->DIS = $this->checkNullValue($jsonData[0], 'DIS');
                $data->DS = $this->checkNullValue($jsonData[0], 'DS');
                $data->GN = $this->checkNullValue($jsonData[0], 'GN');
                $data->respondent_name = $this->checkNullValue($jsonData[0], 'respondentName');
                $data->contact_number = $this->checkNullValue($jsonData[0], 'respondentContact');
                $data->address = $this->checkNullValue($jsonData[0], 'respondentAddress');
                $data->total_members = $surveyLength - 1;

                unset($jsonData[0]);
                $keys = $this->generateKyes();

                foreach ($keys as $key) {

                    foreach ($jsonData as $ky => $response) {
                        if (isset($response["3C_1_10_PR"])) {
                            $response["3C_1_10_other"] = $response["3C_1_10_PR"];
                        }
                        if (isset($response["3B2a_FW"])) {
                            $response["3B1_2a_FW"] = $response["3B2a_FW"];
                        }
                        if (isset($response["3B2a_SW"])) {
                            $response["3B1_2a_SW"] = $response["3B2a_SW"];
                        }
                        if (isset($response["3B2a_TW"])) {
                            $response["3B1_2a_TW"] = $response["3B2a_TW"];
                        }
                        if (isset($response["3B2b_FW"])) {
                            $response["3B1_2b_FW"] = $response["3B2b_FW"];
                        }
                        if (isset($response["3B2b_SW"])) {
                            $response["3B1_2b_SW"] = $response["3B2b_SW"];
                        }
                        if (isset($response["3B2b_TW"])) {
                            $response["3B1_2b_TW"] = $response["3B2b_TW"];
                        }

                        if (isset($response["3B2c_FW"])) {
                            $response["3B1_2c_FW"] = $response["3B2c_FW"];
                        }
                        if (isset($response["3B2c_SW"])) {
                            $response["3B1_2c_SW"] = $response["3B2c_SW"];
                        }
                        if (isset($response["3B2c_TW"])) {
                            $response["3B1_2c_TW"] = $response["3B2c_TW"];
                        }

                        if (isset($response["3B2d_FW"])) {
                            $response["3B1_2d_FW"] = $response["3B2d_FW"];
                        }
                        if (isset($response["3B2d_SW"])) {
                            $response["3B1_2d_SW"] = $response["3B2d_SW"];
                        }
                        if (isset($response["3B2d_TW"])) {
                            $response["3B1_2d_TW"] = $response["3B2d_TW"];
                        }

                        if (isset($response["3B2e_FW"])) {
                            $response["3B1_2e_FW"] = $response["3B2e_FW"];
                        }
                        if (isset($response["3B2e_SW"])) {
                            $response["3B1_2e_SW"] = $response["3B2e_SW"];
                        }
                        if (isset($response["3B2e_TW"])) {
                            $response["3B1_2e_TW"] = $response["3B2e_TW"];
                        }

                        if (isset($response["3B2f_FW"])) {
                            $response["3B1_2f_FW"] = $response["3B2f_FW"];
                        }
                        if (isset($response["3B2f_SW"])) {
                            $response["3B1_2f_SW"] = $response["3B2f_SW"];
                        }
                        if (isset($response["3B2f_TW"])) {
                            $response["3B1_2f_TW"] = $response["3B2f_TW"];
                        }

                        foreach ($key as $col) {
                            $newKey = $col . '_' . $ky;
                            $data->$newKey = $this->checkNullValue($response, $col);
                            array_push($columns, $newKey);
                        }
                    }
                }
                array_push($finalResponse, $data);
                $data = (object)[];
            }

            $export = new SurveysExport($finalResponse, array_unique($columns));

            return Excel::download($export, 'all_surveys.xlsx');
        }

//        }catch(\Exception $error) {
//            return response()->json([
//                "status" => "500",
//                "message" => "Something went wrong"
//            ], 500);
//        }

    }

    public function downloadAllFiles(Request $request) {
        try {

            $token = $request->token;

           // if($token) {

                $files = Storage::allFiles("surveys");
                $finalResponse = [];
                $columns = array(
                    "Unique Id", "Interviewer Id", "Created at", "language", "DIS", "DS", "GN", "Respondent Name", "Respondent Contact",
                    "Respondent Address", "Total Members"
                );

                $data = (object)[];


                foreach ($files as $file) {
                    $path = storage_path() . "/app/" . $file;
                    $jsonData = json_decode(file_get_contents($path), true);

                    $surveyLength = count($jsonData);
                    if($surveyLength < 9) {
                        $array2 = array();
                        $length = 9 - $surveyLength;
                        for($i = 0; $i < $length ; $i ++) {
                            array_push($array2, ["" => ""]);
                        }
                        $result = array_merge($jsonData, $array2);

                        $jsonData =  $result;
                    }

                    $userId = substr($this->checkNullValue($jsonData[0], 'uniqueId'), 3, 2);

                    $data->unique_id = $this->checkNullValue($jsonData[0], 'uniqueId');
                    $data->user_id = 'INT'. str_replace('/', '', $userId);
                    $data->created_at = $this->checkNullValue($jsonData[0], 'createdAt');
                    $data->language = $this->checkNullValue($jsonData[0], 'LANGUAGE');
                    $data->DIS = $this->checkNullValue($jsonData[0], 'DIS');
                    $data->DS = $this->checkNullValue($jsonData[0], 'DS');
                    $data->GN = $this->checkNullValue($jsonData[0], 'GN');
                    $data->respondent_name = $this->checkNullValue($jsonData[0], 'respondentName');
                    $data->contact_number = $this->checkNullValue($jsonData[0], 'respondentContact');
                    $data->address = $this->checkNullValue($jsonData[0], 'respondentAddress');
                    $data->total_members = $surveyLength - 1;

                    if($jsonData[1]['id'] != 1) {
                        $jsonData[1]['id'] = 1;
                        $jsonData[1]['name'] = $this->checkNullValue($jsonData[0], 'respondentName');

                        if($data->language === "si") {
                            $jsonData[1]['2_1'] = "ගෘහ මූලිකයා";
                            $jsonData[1]['2_2'] = "පිරිමි";
                            $jsonData[1]['2_3'] = rand(41,60);
                            $jsonData[1]['2_4'] = "විවාහක";
                        }

                        if($data->language === "ta") {
                            $jsonData[1]['2_1'] = "வீட்டுத் தலைவர்";
                            $jsonData[1]['2_2'] = "ஆண்";
                            $jsonData[1]['2_3'] = rand(41,60);
                            $jsonData[1]['2_4'] = "திருமணமானவர்";
                        }

                        if($data->language === "en") {
                            $jsonData[1]['2_1'] = "Head of the household";
                            $jsonData[1]['2_2'] = "Male";
                            $jsonData[1]['2_3'] = rand(41,60);
                            $jsonData[1]['2_4'] = "Married";
                        }
                    }

                    unset($jsonData[0]);

                    $keys = $this->generateKyes();
                    foreach ($keys as $key) {
                        foreach ($jsonData as $ky => $response) {


                            if (isset($response["3C_1_10_PR"])) {
                                $response["3C_1_10_other"] = $response["3C_1_10_PR"];
                            }
                            if (isset($response["3B2a_FW"])) {
                                $response["3B1_2a_FW"] = $response["3B2a_FW"];
                            }
                            if (isset($response["3B2a_SW"])) {
                                $response["3B1_2a_SW"] = $response["3B2a_SW"];
                            }
                            if (isset($response["3B2a_TW"])) {
                                $response["3B1_2a_TW"] = $response["3B2a_TW"];
                            }
                            if (isset($response["3B2b_FW"])) {
                                $response["3B1_2b_FW"] = $response["3B2b_FW"];
                            }
                            if (isset($response["3B2b_SW"])) {
                                $response["3B1_2b_SW"] = $response["3B2b_SW"];
                            }
                            if (isset($response["3B2b_TW"])) {
                                $response["3B1_2b_TW"] = $response["3B2b_TW"];
                            }

                            if (isset($response["3B2c_FW"])) {
                                $response["3B1_2c_FW"] = $response["3B2c_FW"];
                            }
                            if (isset($response["3B2c_SW"])) {
                                $response["3B1_2c_SW"] = $response["3B2c_SW"];
                            }
                            if (isset($response["3B2c_TW"])) {
                                $response["3B1_2c_TW"] = $response["3B2c_TW"];
                            }

                            if (isset($response["3B2d_FW"])) {
                                $response["3B1_2d_FW"] = $response["3B2d_FW"];
                            }
                            if (isset($response["3B2d_SW"])) {
                                $response["3B1_2d_SW"] = $response["3B2d_SW"];
                            }
                            if (isset($response["3B2d_TW"])) {
                                $response["3B1_2d_TW"] = $response["3B2d_TW"];
                            }

                            if (isset($response["3B2e_FW"])) {
                                $response["3B1_2e_FW"] = $response["3B2e_FW"];
                            }
                            if (isset($response["3B2e_SW"])) {
                                $response["3B1_2e_SW"] = $response["3B2e_SW"];
                            }
                            if (isset($response["3B2e_TW"])) {
                                $response["3B1_2e_TW"] = $response["3B2e_TW"];
                            }

                            if (isset($response["3B2f_FW"])) {
                                $response["3B1_2f_FW"] = $response["3B2f_FW"];
                            }
                            if (isset($response["3B2f_SW"])) {
                                $response["3B1_2f_SW"] = $response["3B2f_SW"];
                            }
                            if (isset($response["3B2f_TW"])) {
                                $response["3B1_2f_TW"] = $response["3B2f_TW"];
                            }

                            if (isset($response["3D_1_AOINFO"])) {
                                $response["3D_AOINFO"] = $response["3D_1_AOINFO"];
                            }


                            foreach ($key as $col) {
                                $newKey = $col . '_' . $ky;
                                $data->$newKey = $this->checkNullValue($response, $col);
                                array_push($columns, $newKey);
                            }
                        }
                    }
                    array_push($finalResponse, $data);
                    $data = (object)[];
                }

                $export = new SurveysExport($finalResponse, array_unique($columns));
                return Excel::download($export, 'all_surveys.xlsx');
            //}

        }catch(\Exception $error) {
            return response()->json([
                "status" => "500",
                "message" => "Something went wrong"
            ], 500);
        }

    }

    private function generateKyes() {
        $keys = [
            [
                "id",
                "name",
                "2_1",
                "2_2",
                "2_3",
                "2_4",
                "2_5",
                "2_6",
            ],
            [
                "2_7_1",
                "2_7_2",
                "2_7_3",
                "2_7_4",
            ],
            [
                "3A1",
                "3A1_1"
            ],
            [
                "3A1_1a_SW",
                "3A1_1a_TW",

                "3A1_1b_FW",
                "3A1_1b_SW",
                "3A1_1b_TW",

                "3A1_1c_FW",
                "3A1_1c_SW",
                "3A1_1c_TW",

                "3A1_1d_FW",
                "3A1_1d_SW",
                "3A1_1d_TW",

                "3A1_1e",
                "3A1_1f",
                "3A1_1_AOINFO"
            ],
            [
                "3A1_2a",
                "3A1_2b",
                "3A1_2c",
                "3A1_2d",
                "3A1_2f",
                "3A1_2g",
                "3A1_2h",
                "3A1_2i",
                "3A1_2j",
                "3A1_2_AOINFO"
            ],
            [
                "3A1_3a",
                "3A1_3b",
                "3A1_3c",
                "3A1_3d",
                "3A1_3e",
                "3A1_3f",
                "3A1_3g",
                "3A1_3h",
                "3A1_3i",
                "3A1_3j",
                "3A1_3k",
                "3A1_3l",
                "3A1_3m",
                "3A1_3_AOINFO",
            ],
            [
                "3A2_1a_FW",
                "3A2_1a_SW",
                "3A2_1a_TW",
                "3A2_1b",
                "3A2_1c_FW",
                "3A2_1c_SW",
                "3A2_1c_TW",
                "3A2_1d",
                "3A2_1e_FW",
                "3A2_1e_SW",
                "3A2_1e_TW",
                "3A2_1f_FW",
                "3A2_1f_SW",
                "3A2_1f_TW",
            ],
            [
                "3A2_2a_FW",
                "3A2_2a_SW",
                "3A2_2a_TW",
                "3A2_2b_FW",
                "3A2_2b_SW",
                "3A2_2b_TW",
                "3A2_2c_FW",
                "3A2_2c_SW",
                "3A2_2c_TW",
                "3A2_2d_FW",
                "3A2_2d_SW",
                "3A2_2d_TW",
                "3A2_2_AOINFO"
            ],
            [
                "3A2_3a",
                "3A2_3b",
                "3A2_3c",
                "3A2_3d",
                "3A2_3_AOINFO"
            ],
            [
                "3A3_1a",

                //YES
                "3A3_1b",
                "3A3_1c",
                "3A3_1d",
                "3A3_1e",

                "3A3_1_AOINFO",

                //NO
                "3A3_2a",
                "3A3_2b",
                "3A3_2c",
                "3A3_2d",
                "3A3_2e",

                "3A3_2_AOINFO"
            ],
            [
                "3B1_1PP",
                "3B1_1FW",
                "3B1_1SW",
                "3B1_1TW"
            ],
            [
                "3B1_2a_FW",
                "3B1_2a_SW",
                "3B1_2a_TW",

                "3B1_2b_FW",
                "3B1_2b_SW",
                "3B1_2b_TW",

                "3B1_2c_FW",
                "3B1_2c_SW",
                "3B1_2c_TW",

                "3B1_2d_FW",
                "3B1_2d_SW",
                "3B1_2d_TW",

                "3B1_2e_FW",
                "3B1_2e_SW",
                "3B1_2e_TW",

                "3B1_2f_FW",
                "3B1_2f_SW",
                "3B1_2f_TW"
            ],
            [
                "3B1_3_PP",
                "3B1_3_FW",
                "3B1_3_SW",
                "3B1_3_TW",
                "3B1_3_ATOAMC",
                "3B1_3_AOINFO"
            ],
            [
                "3B2_1_FW",
                "3B2_1_FW_3B2_2_FW",
                "3B2_1_FW_3B2_2_SW",
                "3B2_1_FW_3B2_2_TW",

                "3B2_1_SW",
                "3B2_1_SW_3B2_2_FW",
                "3B2_1_SW_3B2_2_SW",
                "3B2_1_SW_3B2_2_TW",

                "3B2_1_TW",
                "3B2_1_TW_3B2_2_FW",
                "3B2_1_TW_3B2_2_SW",
                "3B2_1_TW_3B2_2_TW",

                "3B2_2_AOINFO"

            ],
            [
                "3C_1_1_PR",
                "3C_1_1_FW",
                "3C_1_1_SW",
                "3C_1_1_TW",

                "3C_1_2_PR",
                "3C_1_2_FW",
                "3C_1_2_SW",
                "3C_1_2_TW",

                "3C_1_3_PR",
                "3C_1_3_FW",
                "3C_1_3_SW",
                "3C_1_3_TW",

                "3C_1_4_PR",
                "3C_1_4_FW",
                "3C_1_4_SW",
                "3C_1_4_TW",

                "3C_1_5_PR",
                "3C_1_5_FW",
                "3C_1_5_SW",
                "3C_1_5_TW",

                "3C_1_6_PR",
                "3C_1_6_FW",
                "3C_1_6_SW",
                "3C_1_6_TW",

                "3C_1_7_PR",
                "3C_1_7_FW",
                "3C_1_7_SW",
                "3C_1_7_TW",

                "3C_1_8_PR",
                "3C_1_8_FW",
                "3C_1_8_SW",
                "3C_1_8_TW",

                "3C_1_9_PR",
                "3C_1_9_FW",
                "3C_1_9_SW",
                "3C_1_9_TW",

                "3C_1_10_other",
            ],
            [
                "3C_2_1_PR",
                "3C_2_1_FW",
                "3C_2_1_SW",
                "3C_2_1_TW",

                "3C_2_2_PR",
                "3C_2_2_FW",
                "3C_2_2_SW",
                "3C_2_2_TW",

                "3C_2_3_PR",
                "3C_2_3_FW",
                "3C_2_3_SW",
                "3C_2_3_TW",

                "3C_2_4_PR",
                "3C_2_4_FW",
                "3C_2_4_SW",
                "3C_2_4_TW",

                "3C_2_5_PR",
                "3C_2_5_FW",
                "3C_2_5_SW",
                "3C_2_5_TW",

                "3C_2_6_PR",
                "3C_2_6_FW",
                "3C_2_6_SW",
                "3C_2_6_TW",

                "3C_2_7_PR",
                "3C_2_7_FW",
                "3C_2_7_SW",
                "3C_2_7_TW",

                "3C_2_8_other"
            ],
            [
                "3C_3_1_PR",
                "3C_3_1_FW",
                "3C_3_1_SW",
                "3C_3_1_TW",

                "3C_3_2_PR",
                "3C_3_2_FW",
                "3C_3_2_SW",
                "3C_3_2_TW",

                "3C_3_3_PR",
                "3C_3_3_FW",
                "3C_3_3_SW",
                "3C_3_3_TW",

                "3C_3_4_other",
            ],
            [
                "3C_4_1_PR",
                "3C_4_1_FW",
                "3C_4_1_SW",
                "3C_4_1_TW",

                "3C_4_2_PR",
                "3C_4_2_FW",
                "3C_4_2_SW",
                "3C_4_2_TW",

                "3C_4_3_PR",
                "3C_4_3_FW",
                "3C_4_3_SW",
                "3C_4_3_TW",

                "3C_4_4_PR",
                "3C_4_4_FW",
                "3C_4_4_SW",
                "3C_4_4_TW",

                "3C_4_5_PR",
                "3C_4_5_FW",
                "3C_4_5_SW",
                "3C_4_5_TW",

                "3C_4_6_PR",
                "3C_4_6_FW",
                "3C_4_6_SW",
                "3C_4_6_TW",

                "3C_4_7_other"

            ],
            [
                "3C_5_1_PR",
                "3C_5_1_FW",
                "3C_5_1_SW",
                "3C_5_1_TW",

                "3C_5_2_PR",
                "3C_5_2_FW",
                "3C_5_2_SW",
                "3C_5_2_TW",

                "3C_5_3_PR",
                "3C_5_3_FW",
                "3C_5_3_SW",
                "3C_5_3_TW",

                "3C_5_4_PR",
                "3C_5_4_FW",
                "3C_5_4_SW",
                "3C_5_4_TW",

                "3C_5_5_other",
            ],
            [
                "3C_5_2_1_PR",
                "3C_5_2_1_FW",
                "3C_5_2_1_SW",
                "3C_5_2_1_TW",

                "3C_5_2_2_PR",
                "3C_5_2_2_FW",
                "3C_5_2_2_SW",
                "3C_5_2_2_TW",

                "3C_5_2_3_PR",
                "3C_5_2_3_FW",
                "3C_5_2_3_SW",
                "3C_5_2_3_TW",

                "3C_5_2_4_PR",
                "3C_5_2_4_FW",
                "3C_5_2_4_SW",
                "3C_5_2_4_TW",

                "3C_5_2_5_PR",
                "3C_5_2_5_FW",
                "3C_5_2_5_SW",
                "3C_5_2_5_TW",

                "3C_5_2_6_PR",
                "3C_5_2_6_FW",
                "3C_5_2_6_SW",
                "3C_5_2_6_TW",

                "3C_5_2_7_other"
            ],
            [
                "3C_5_3_1_PR",
                "3C_5_3_1_FW",
                "3C_5_3_1_SW",
                "3C_5_3_1_TW",

                "3C_5_3_2_PR",
                "3C_5_3_2_FW",
                "3C_5_3_2_SW",
                "3C_5_3_2_TW",

                "3C_5_3_3_PR",
                "3C_5_3_3_FW",
                "3C_5_3_3_SW",
                "3C_5_3_3_TW",

                "3C_5_3_4_PR",
                "3C_5_3_4_FW",
                "3C_5_3_4_SW",
                "3C_5_3_4_TW",

                "3C_5_3_5_PR",
                "3C_5_3_5_FW",
                "3C_5_3_5_SW",
                "3C_5_3_5_TW",

                "3C_5_3_6_other",
            ],
            [
                "3D_1",
                "3D_2",
                "3D_3",
                "3D_4",
                "3D_5",
                "3D_1_AOINFO"
            ],
            [
                "4D_1_1",
                "4D_1_2",
                "4D_1_3",
                "4D_1_4",
                "4D_1_5",
                "4D_1_6",
                "4D_1_7",
                "4D_1_8",
                "4D_1_9",
                "4D_1_AOINFO",
            ],
            [
                "4D_2_1",
                "4D_2_2",
                "4D_2_3",
                "4D_2_AOINFO"
            ],
            [
                "5E_1_FW",
                "5E_1_SW",
                "5E_1_TW",

                "5E_2_FW",
                "5E_2_SW",
                "5E_2_TW",

                "5E_3_FW",
                "5E_3_SW",
                "5E_3_TW",
                "5E_3_AOINFO"
            ],
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
