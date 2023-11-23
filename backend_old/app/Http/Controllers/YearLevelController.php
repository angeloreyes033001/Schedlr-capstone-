<?php

namespace App\Http\Controllers;

use App\Models\YearLevel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class YearLevelController extends Controller
{

    protected function create(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "yearlevel"=>"required",
                "tokens"=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('yearlevel')){
                    $errorMessage = $validator->errors()->first('yearlevel');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"yearlevel"]);
                }

                if($validator->errors()->has('tokens')){
                    $errorMessage = $validator->errors()->first('tokens');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"tokens"]);
                }

            }

            $department = $this->tokenDepartment($request->input('tokens'));

            $check = YearLevel::join('departments','yearlevelDepartment','=','department')
            ->where(['yearlevel'=>$request->input('yearlevel'), "yearlevelDepartment"=>$department,"yearlevelSoftDelete"=>0,"departmentSoftDelete"=>0])
            ->count();

            if($check > 0){
                return response()->json(["status"=>false, "msg"=>"This Year Level is already registered.", "error"=>"yearlevel", "error"=>"yearlevel"]);
            }
            
            $save = new YearLevel();
            $save->yearlevel = strtolower($request->input('yearlevel'));
            $save->yearlevelDepartment = $department;
            $result = $save->save();
            if(!$result){
                Log::error("Failed to create yearlevels");
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
            $years = YearLevel::join('departments','yearlevelDepartment','=','department')
            ->where([ 'yearlevelDepartment'=>$department ,'yearlevelSoftDelete'=>0,"departmentSoftDelete"=>0])
            ->select('yearlevelID as id', 'yearlevelID as year', 'yearlevel as yearName')
            ->get();
            return response()->json($years);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function update(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "id"=>"required",
                "yearlevel"=>"required",
                "tokens"=>"required",
            ]);

            if($validator->fails()){

                if($validator->errors()->has('id')){
                    $errorMessage = $validator->errors()->first('id');
                    Log::error("Failed Year Level ID was not selected");
                }

                if($validator->errors()->has('yearlevel')){
                    $errorMessage = $validator->errors()->first('yearlevel');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"yearlevel"]);
                }

                if($validator->errors()->has('tokens')){
                    $errorMessage = $validator->errors()->first('tokens');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"tokens"]);
                }

            }
            $department = $this->tokenDepartment($request->input('tokens'));

            $check = YearLevel::join('departments','yearlevelDepartment','=','department')
            ->where(['yearlevel'=>$request->input('yearlevel'), "yearlevelDepartment"=>$department,"yearlevelSoftDelete"=>0,"departmentSoftDelete"=>0])->count();

            if($check > 0){
                return response()->json(["status"=>false, "msg"=>"This Year Level is already registered.", "error"=>"yearlevel"]);
            }

            YearLevel::join('departments','yearlevelDepartment','=','department')
            ->where(['yearlevelID'=>$request->input('id') ,'yearlevelDepartment'=>$department,'yearlevelSoftDelete'=>0,'departmentSoftDelete'=>0])
            ->update(['yearlevel'=>strtolower($request->input('yearlevel'))]);

            return response()->json(["status"=>true,"msg"=>"Successfully Updated!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($id){
        try{
            YearLevel::join('departments','yearlevelDepartment','=','department')
            ->where(['yearlevelID'=>$id])->update(['yearlevelSoftDelete'=>1,"departmentSoftDelete"=>0]);
            return response()->json(["status"=>true,"msg"=>"Successfully Deleted!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
