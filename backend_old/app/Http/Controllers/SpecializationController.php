<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SpecializationController extends Controller
{
    protected function AutoCreateCommon($tokens){
        try{
            $department = $this->tokenDepartment($tokens);

            $check = Specialization::join('departments','specializationDepartment','=','department')
            ->where(['specializationDepartment'=>$department,"specializationName"=>"common","specializationSoftDelete"=>0,'departmentSoftDelete'=>0])
            ->count();

            if($check == 0){

                $checkDeleted = Specialization::join('departments','specializationDepartment','=','department')
                ->where(['specializationDepartment'=>$department,"specializationName"=>"common","specializationSoftDelete"=>1,'departmentSoftDelete'=>0])
                ->count();

                if($checkDeleted > 0){
                    Specialization::join('departments','specializationDepartment','=','department')
                    ->where(['specializationDepartment'=>$department,"specializationName"=>"common","specializationSoftDelete"=>1,'departmentSoftDelete'=>0])
                    ->update(['specializationSoftDelete'=>0]);
                }
                else{
                    $save = new Specialization();
                    $save->specializationName = strtolower("common");
                    $save->specializationDepartment = $department;
                    $save->specializationSoftDelete = false;
                    $save->save();
                }
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function create(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'specialization'=>"required",
                'tokens'=>"required",
            ]);

            $department = $this->tokenDepartment($request->input('tokens'));

            if($validator->fails()){
                if($validator->errors()->has('specialization')){
                    $errorMessage = $validator->errors()->first('specialization');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"specialization"]);
                }
            }

            if(strtolower($request->input('specialization'))=='common'){
                return response()->json(['status'=>false,'msg'=>'Common word is not allowed!']);
            }

            $check = Specialization::join('departments','specializationDepartment','=','department')
            ->where(['specializationName'=>$request->input('specialization'),'specializationDepartment'=> $department,'specializationSoftDelete'=>0,"departmentSoftDelete"=>0])
            ->count();

            if($check > 0){
                return response()->json(["status"=>false, "msg"=>"The Specialization has already been taken"]);
            }

            $checkDeleted = Specialization::join('departments','specializationDepartment','=','department')
            ->where(['specializationName'=>$request->input('specialization'),'specializationDepartment'=> $department,'specializationSoftDelete'=>1,"departmentSoftDelete"=>0])
            ->count();

            if($checkDeleted > 0){
                Specialization::join('departments','specializationDepartment','=','department')
                ->where(['specializationName'=>$request->input('specialization'),"departmentSoftDelete"=>0])->update(["specializationSoftDelete"=>0]);
                return response()->json(["status"=>true,"msg"=>"Successfuly Created."]);
            }
            else{
                $save = new Specialization();
                $save->specializationName = strtolower($request->input('specialization'));
                $save->specializationDepartment = $department;
                $save->specializationSoftDelete = false;
                $result = $save->save();

                return response()->json(["status"=>true,"msg"=>"Successfully Created."]);

            if(!$result){
                Log::error("Failed to create departent");
            }
        }
        return response()->json(["status"=>true,"msg"=>"Successfully Created"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read($tokens){
        try{
            $department = $this->tokenDepartment($tokens);

            $specializations = Specialization::join('departments','specializationDepartment','=','department')
            ->where(['specializationDepartment'=>$department,'specializationSoftDelete'=>0,"departmentSoftDelete"=>0])
            ->select('specializationID as id', 'specializationName as specialization','specializationDepartment as department')
            ->get();

            return response()->json($specializations);


        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }


    }

    protected function delete(Request $request){
        try{

            $validator = Validator::make($request->all(),[
                'id'=>'required',
                'tokens'=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('id')){
                    $errorMessage = $validator->errors()->first('id');
                    Log::error($errorMessage);
                }
            }

            $department = $this->tokenDepartment($request->input('tokens'));

            $check = Specialization::join('departments','specializationDepartment','=','department')
            ->where(['specializationID'=>$request->input('id'),'specializationDepartment'=>$department, 'specializationSoftDelete'=>0,'departmentSoftDelete'=>0])
            ->count();

            if($check ==  0){
                Log::error("User error, Changing value in inspect element!");
            }
            else{

                $delete = Specialization::join('departments','specializationDepartment','=','department')
                ->where(['specializationID'=>$request->input('id'),'specializationDepartment'=>$department,'specializationSoftDelete'=>0,"departmentSoftDelete"=>0])
                ->update(['specializationSoftDelete'=>1]);

                if($delete){
                    return response()->json(["status"=>true,"msg"=>"Successfully Deleted."]);
                }
                else{
                    Log::error('Something went wrong in deleting Specialization!');
                }
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
