<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    protected function read (){
        try{
            $department =  Department::where(['departmentSoftDelete'=>0])
            ->select('department')
            ->get();
            return response()->json($department);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function create(Request $request){
        
        $validator = Validator::make($request->all(),[
            "department"=>"required",
        ]);

        if($validator->fails()){
            if($validator->errors()->has('department')){
                $errorMessage = $validator->errors()->first('department');
                return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"department"]);
            }
        }

        $check = Department::where(['department' => $request->input('department'), "departmentSoftDelete"=>0])->count();
        if($check > 0){
            return response()->json(["status"=>false, "msg"=>"The department has already been taken"]);
        }

        $checkDeleted = Department::where(['department' => $request->input('department'), "departmentSoftDelete"=>1])->count();
        if($checkDeleted > 0){
            Department::where(['department'=>$request->input('department')])->update(["departmentSoftDelete"=>0]);
        }
        else{
            $save = new Department();
            $save->department = strtolower($request->input('department'));
            $save->departmentSoftDelete =  0;
            $result = $save->save();

            if(!$result){
                Log::error("Failed to create departent");
            }
        }

        return response()->json(["status"=>true,"msg"=>"Successfully Created."]);

    }
}
