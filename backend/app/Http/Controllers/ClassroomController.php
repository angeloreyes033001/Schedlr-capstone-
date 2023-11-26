<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    protected function create(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "room"=>"required",
                "type"=>"required|in:lecture,laboratory",
                "year"=>"required|integer",
                "overwrite"=>"required|in:0,1",
                "tokens"=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('room')){
                    $errorMessage = $validator->errors()->first('room');
                    return response()->json(['status'=>false, "error"=>"room", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('type')){
                    $errorMessage = $validator->errors()->first('type');
                    return response()->json(['status'=>false, "error"=>"type", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('overwrite')){
                    $errorMessage = $validator->errors()->first('overwrite');
                    return response()->json(['status'=>false, "error"=>"overwrite", "msg"=>$errorMessage]);
                }
            }

            $department = $this->tokenDepartment($request->input('tokens'));

            $check  = Classroom::join('departments','classroomDepartment','=','department')
            ->where([
                    'classroomName'=>strtolower($request->input('room')), 
                    "classroomDepartment"=>$department,
                    "classroomSoftDelete"=>0,
                    "departmentSoftDelete"=>0
                ])->count();

            if($check > 0){
                return response()->json(["status"=>false, "error"=>"room", "This room is already registered!"]);
            }
            
            $save = new Classroom();
            $save->classroomName = strtolower($request->input('room'));
            $save->classroomType = strtolower($request->input('type'));
            $save->classroomMulti = $request->input('overwrite');
            $save->classroomDepartment = strtolower($department);
            $result = $save->save();

            if(!$result){
                Log::error("Failed to create classrooms");
            }

            return response()->json(['status'=>true, "msg"=>"Successfully Created"]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read($tokens){
        try{
            $departments = $this->tokenDepartment($tokens);
            
            $classrooms = Classroom::join('departments','classroomDepartment','=','department')
            ->where([
                'classroomDepartment'=>$departments,
                "classroomSoftDelete"=>0,
                'departmentSoftDelete'=>0
            ])
            ->select(["classroomID as id", "classroomName as room", "classroomType as type","classroomMulti as multi"])->get();

            return response()->json($classrooms);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function update(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "id"=>"required|integer",
                "room"=>"required",
                "type"=>"required|in:lecture,laboratory",
                "overwrite"=>"required|in:0,1"
            ]);

            if($validator->fails()){
                
                if($validator->errors()->has('id')){
                    $errorMessage = $validator->errors()->first('id');
                    Log::error("ID not selected");
                }

                if($validator->errors()->has('room')){
                    $errorMessage = $validator->errors()->first('room');
                    return response()->json(['status'=>false, "error"=>"room", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('type')){
                    $errorMessage = $validator->errors()->first('type');
                    return response()->json(['status'=>false, "error"=>"type", "msg"=>$errorMessage]);
                }

                if($validator->errors()->has('overwrite')){
                    $errorMessage = $validator->errors()->first('overwrite');
                    return response()->json(['status'=>false, "error"=>"overwrite", "msg"=>$errorMessage]);
                }
            }

            Classroom::join('departments','classroomDepartment','=','department')
            ->where(["classroomID"=>$request->input('id'),"departmentSoftDelete"=>0])
            ->update([
                "classroomName"=>strtolower($request->input('room')),
                "classroomType"=>$request->input('type'),
                "classroomMulti"=>$request->input('overwrite'),
            ]);

            return response()->json(["status"=>true,"msg"=>"Successfully Updated!"]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($id){
        try{

            $check = Classroom::where(['classroomID'=>$id])->count();
            if($check > 0){
                Log::error("ID not Found!");
            }

            Classroom::join('departments','classroomDepartment','=','department')
            ->where(['classroomID'=>$id,"departmentSoftDelete"=>0])
            ->update(['classroomSoftDelete'=>1]);

            return response(["status"=>true,"msg"=>"Successfully Deleted"]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
