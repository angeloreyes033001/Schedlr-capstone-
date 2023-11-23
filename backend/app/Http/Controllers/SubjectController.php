<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{

    protected function create(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "code"=>"required",
                "subject"=>"required",
                "semester" => "required|in:1st semester,2nd semester",
                "laboratory"=>"required|between:0,5",
                "lecture"=>"required|between:1,5",
                "specialization"=>"required",
                "year"=>"required|integer",
                "tokens"=>"required",
            ]);
            
            if($validator->fails()){
                if($validator->errors()->has('code')){
                    $errorMessage = $validator->errors()->first('code');
                    return response()->json(['status'=>false, "error"=>"code", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('subject')){
                    $errorMessage = $validator->errors()->first('subject');
                    return response()->json(['status'=>false, "error"=>"subject", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('semester')){
                    $errorMessage = $validator->errors()->first('semester');
                    return response()->json(['status'=>false, "error"=>"semester", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('laboratory')){
                    $errorMessage = $validator->errors()->first('laboratory');
                    return response()->json(['status'=>false, "error"=>"laboratory", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('lecture')){
                    $errorMessage = $validator->errors()->first('lecture');
                    return response()->json(['status'=>false, "error"=>"lecture", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('specialization')){
                    $errorMessage = $validator->errors()->first('specialization');
                    return response()->json(['status'=>false, "error"=>"specialization", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('year')){
                    $errorMessage = $validator->errors()->first('year');
                    return response()->json(['status'=>false, "error"=>"year", "msg"=>$errorMessage]);
                }
            }

            $departments = $this->tokenDepartment($request->input('tokens'));

            $check = Subject::
            join("departments",'subjectDepartment','=','department')
            ->join("year_levels","subjectYearlevel",'=','yearlevelID')
            ->join("specializations","subjectSpecialization",'=','subjectSpecialization')
            ->where([
                    "subjectCode"=>strtolower($request->input('code')),
                    "subjectSoftDelete"=> 0,
                    "yearlevelSoftDelete"=>0,
                    "specializationSoftDelete"=>0,
                    "departmentSoftDelete"=>0
                ])
            ->count();
            if($check > 0){
                return response()->json(["status"=>false, "error"=>"code","msg"=>"This code is already registered."]);
            }

            $checkDeleted = Subject::
            join("departments",'subjectDepartment','=','department')
            ->join("year_levels","subjectYearlevel",'=','yearlevelID')
            ->join("specializations","subjectSpecialization",'=','subjectSpecialization')
            ->where([
                "subjectCode"=>strtolower($request->input('code')),
                "subjectSoftDelete"=> 1,
                "yearlevelSoftDelete"=>0,
                "specializationSoftDelete"=>0,
                "departmentSoftDelete"=>0
            ])
            ->count();
            if($checkDeleted > 0){
                Subject::
                join("departments",'subjectDepartment','=','department')
                ->join("year_levels","subjectYearlevel",'=','yearlevelID')
                ->join("specializations","subjectSpecialization",'=','subjectSpecialization')
                ->where([
                        'subjectCode'=>strtolower($request->input('code')),
                        "yearlevelSoftDelete"=>0,
                        "specializationSoftDelete"=>0,
                        "departmentSoftDelete"=>0
                        ])
                    ->update([
                        "subjectName"=>strtolower($request->input('subject')),
                        "subjectSemester"=>$request->input('semester'),
                        "subjectYearlevel"=>$request->input('year'),
                        "subjectLabHour"=>$request->input('laboratory'),
                        "subjectLecHour"=>$request->input('lecture'),
                        "subjectSpecialization"=>$request->input('specialization'),
                        "subjectDepartment"=>$departments,
                        "subjectSoftDelete"=> 0,
                    ]);
            }
            else{
                $save = new Subject();
                $save->subjectCode = strtolower($request->input('code'));
                $save->subjectName = strtolower($request->input('subject'));
                $save->subjectSemester = strtolower($request->input('semester'));
                $save->subjectYearlevel = $request->input('year');
                $save->subjectLabHour = $request->input('laboratory');
                $save->subjectLecHour = $request->input('lecture');
                $save->subjectSpecialization = $request->input('specialization');
                $save->subjectDepartment = $departments;
                $result = $save->save();

                if(!$result){
                    Log::error("Failed to create subjects");
                }
            }

            return response()->json(['status'=>true,'msg'=>"Successfully Created!"]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read($tokens){
        try{
            $department = $this->tokenDepartment($tokens);
            $subjects = Subject::
            join("departments",'subjectDepartment','=','department')
            ->join("year_levels","subjectYearlevel",'=','yearlevelID')
            ->join("specializations","subjectSpecialization",'=','specializationID')
            ->where([
                'subjectDepartment'=>$department,
                "subjectSoftDelete"=>0,
                "yearlevelSoftDelete"=>0,
                "specializationSoftDelete"=>0,
                "departmentSoftDelete"=>0
                ])
            ->select("subjectCode as code", "subjectName as subject", "subjectSemester as semester", "subjectLecHour as lecture","subjectLabHour as laboratory" ,'yearlevelID as yearID' ,'yearlevel as year','specializationID','specializationName as specialization')
            ->get();

            return response()->json($subjects);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function update(Request $request){

        try{
            $validator = Validator::make($request->all(),[
                "code"=>"required",
                "subject"=>"required",
                "semester" => "required|in:1st semester,2nd semester",
                "laboratory"=>"required|between:0,5",
                "lecture"=>"required|between:1,5",
                "specialization"=>"required",
                "year"=>"required|integer",
            ]);
            
            if($validator->fails()){
                if($validator->errors()->has('code')){
                    $errorMessage = $validator->errors()->first('code');
                    Log::error($errorMessage);
                }

                if($validator->errors()->has('subject')){
                    $errorMessage = $validator->errors()->first('subject');
                    return response()->json(['status'=>false, "error"=>"subject", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('semester')){
                    $errorMessage = $validator->errors()->first('semester');
                    return response()->json(['status'=>false, "error"=>"semester", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('laboratory')){
                    $errorMessage = $validator->errors()->first('laboratory');
                    return response()->json(['status'=>false, "error"=>"laboratory", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('lecture')){
                    $errorMessage = $validator->errors()->first('lecture');
                    return response()->json(['status'=>false, "error"=>"lecture", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('specialization')){
                    $errorMessage = $validator->errors()->first('specialization');
                    return response()->json(['status'=>false, "error"=>"specialization", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('year')){
                    $errorMessage = $validator->errors()->first('year');
                    return response()->json(['status'=>false, "error"=>"year", "msg"=>$errorMessage]);
                }
            }

             Subject::where([
                'subjectCode'=>$request->input('code'),
                ])
            ->update([
                "subjectName"=>$request->input('subject'),
                "subjectSemester"=>$request->input('semester'),
                "subjectLabHour"=>$request->input('laboratory'),
                "subjectLecHour"=>$request->input('lecture'),
                "subjectSpecialization"=>$request->input('specialization'),
                "subjectYearlevel"=>$request->input('year'),
            ]);

            return response()->json(['status'=>true, 'msg'=>"Successfully Updated!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($code){
        try{
            Subject::where([
                'subjectCode'=>$code,
                ])
            ->update(['subjectSoftDelete'=>1]);

            return response()->json(["status"=>true,"msg"=>"Successfully Updated!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
