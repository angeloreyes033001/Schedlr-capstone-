<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{

    protected function create(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "section"=>"required",
                "yearlevel"=>"required|integer",
                "specialization"=>"required",
                "tokens"=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('section')){
                    $errorMessage = $validator->errors()->first('section');
                    return response()->json(["status"=>false, "error"=>"section", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('specialization')){
                    $errorMessage = $validator->errors()->first('specialization');
                    return response()->json(["status"=>false, "error"=>"specialization", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('yearlevel')){
                    $errorMessage = $validator->errors()->first('yearlevel');
                    return response()->json(["status"=>false, "error"=>"yearlevel", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('tokens')){
                    $errorMessage = $validator->errors()->first('tokens');
                    return response()->json(["status"=>false, "error"=>"tokens", "msg"=>$errorMessage]);
                }
            }

            $department = $this->tokenDepartment($request->input('tokens'));

            $check = Section::join('departments','sectionDepartment','=','department')
            ->where(['sectionName'=>$request->input('section'),"sectionSoftDelete"=>0, "departmentSoftDelete"=>0] )
            ->count();

            if($check > 0){
                return  response()->json(["status"=>false, "error"=>"section", "msg"=>"This section is already registered!"]);
            }

            $save = new Section();
            $save->sectionName = strtolower($request->input('section'));
            $save->sectionYearlevel = $request->input('yearlevel');
            $save->sectionSpecialization = $request->input('specialization');
            $save->sectionDepartment = $department;
            $result = $save->save();

            if(!$result){
                Log::error("Failed to create sections!");
            }

            return response()->json(["status"=>true,"msg"=>"Successfully Created!"]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
    
    protected function read($tokens){
        try{
            $department = $this->tokenDepartment($tokens);
            $sections = Section::join('departments','sectionDepartment','=','department')
            ->join("year_levels","sectionYearlevel","=","yearlevelID")
            ->join("specializations",'sectionSpecialization','=','specializationID')
            ->select("sections.sectionID as id", "sections.sectionName as section","sectionSpecialization as specializationID","specializationName as specialization","year_levels.yearlevelID as yearID", "year_levels.yearlevel as year")
            ->where([
                'sectionDepartment'=>$department,
                'sectionSoftDelete'=>0,
                "specializationSoftDelete"=>0,
                "yearlevelSoftDelete"=>0,
                "departmentSoftDelete"=>0
            ])
            ->get();
           
            return response()->json($sections);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function update(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "id"=>"required",
                "section"=>"required",
                "yearlevel"=>"required",
                "specialization"=>"required"
            ]);

            if($validator->fails()){
                if($validator->errors()->has('section')){
                    $errorMessage = $validator->errors()->first('section');
                    return response()->json(["status"=>false, "error"=>"section", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('yearlevel')){
                    $errorMessage = $validator->errors()->first('yearlevel');
                    return response()->json(["status"=>false, "error"=>"yearlevel", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('specialization')){
                    $errorMessage = $validator->errors()->first('specialization');
                    return response()->json(["status"=>false, "error"=>"specialization", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('tokens')){
                    $errorMessage = $validator->errors()->first('tokens');
                    return response()->json(["status"=>false, "error"=>"tokens", "msg"=>$errorMessage]);
                }
            }

            Section::join('departments','sectionDepartment','=','department')
            ->join("year_levels","sectionYearlevel","=","yearlevelID")
            ->join("specializations",'sectionSpecialization','=','specializationID')
            ->where([
                'sectionID'=>$request->input('id'),
                'sectionSoftDelete'=>0,
                'yearlevelSoftDelete'=>0,
                'specializationSoftDelete'=>0,
                'departmentSoftDelete'=>0
                ])
            ->update([
                "sectionName"=>$request->input('section'),
                "sectionYearlevel"=>$request->input('yearlevel'),
                "sectionSpecialization"=>$request->input('specialization')
            ]);

            return response()->json(["status"=>true,"msg"=>"Successfully Updated!"]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($id){
        try{
            Section::join('departments','sectionDepartment','=','department')
            ->join("year_levels","sectionYearlevel","=","yearlevelID")
            ->join("specializations",'sectionSpecialization','=','specializationID')
            ->where(['sectionID'=>$id])->update([
                "sectionSoftDelete"=>1,
                'yearlevelSoftDelete'=>0,
                'specializationSoftDelete'=>0,
                'departmentSoftDelete'=>0
            ]);
            return response()->json(["status"=>true,"msg"=>"Successfully Deleted!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
