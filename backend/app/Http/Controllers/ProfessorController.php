<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfessorController extends Controller
{
    protected function create(Request $request){
        try{

            $validator = Validator::make($request->all(),[
                "id"=>"required|unique:professors,professorID",
                "fullname"=>"required",
                "status"=>"required|in:regular,part-time",
                "rank"=>"required",
                "designation"=>"required",
                "tokens"=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('id')){
                    $errorMesage = $validator->errors()->first('id');
                    return response(["status"=>false, "error"=>"id", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('fullname')){
                    $errorMesage = $validator->errors()->first('fullname');
                    return response(["status"=>false, "error"=>"fullname", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('status')){
                    $errorMesage = $validator->errors()->first('status');
                    return response(["status"=>false, "error"=>"status", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('rank')){
                    $errorMesage = $validator->errors()->first('rank');
                    return response(["status"=>false, "error"=>"rank", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('designation')){
                    $errorMesage = $validator->errors()->first('designation');
                    return response(["status"=>false, "error"=>"designation", "msg"=>$errorMesage]);
                }
            }

            $departments = $this->tokenDepartment($request->input('tokens'));

            $save = new Professor();
            $save->professorID = strtolower($request->input('id'));
            $save->professorFullname = strtolower($request->input('fullname')) ;
            $save->professorStatus = strtolower($request->input('status'));
            $save->professorRank = $request->input('rank');
            $save->professorDesignation = $request->input("designation");
            $save->professorDepartment = $departments;
            $result = $save->save();
            if(!$result){
                Log::error("Failed to create professors");
            }
            return response()->json(["status"=>true,"msg"=>"Successfully Created"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read($tokens){
        try{
            $departments = $this->tokenDepartment($tokens);

            $fetch = Professor::
            join("departments","professorDepartment","=","department")
            ->join("ranks","professorRank","=","rankID")
            ->where([
                "professorDepartment"=>$departments,
                "professorSoftDelete"=>0,
                "rankSoftDelete"=>0,
                "departmentSoftDelete"=>0
                ])
            ->select("professorID", "professorFullname as fullname", "professorStatus as status", "rankID as rankID" ,"rankName as rank", "professorDesignation as designation" )
            ->get();

            return response()->json($fetch);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function update(Request $request){
        try{
            
            $validator = Validator::make($request->all(),[
                "id"=>"required",
                "fullname"=>"required",
                "status"=>"required|in:regular,part-time",
                "rank"=>"required",
                "designation"=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('id')){
                    $errorMesage = $validator->errors()->first('id');
                    Log::error($errorMesage);
                }

                if($validator->errors()->has('fullname')){
                    $errorMesage = $validator->errors()->first('fullname');
                    return response(["status"=>false, "error"=>"fullname", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('status')){
                    $errorMesage = $validator->errors()->first('status');
                    return response(["status"=>false, "error"=>"status", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('rank')){
                    $errorMesage = $validator->errors()->first('rank');
                    return response(["status"=>false, "error"=>"rank", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('designation')){
                    $errorMesage = $validator->errors()->first('designation');
                    return response(["status"=>false, "error"=>"designation", "msg"=>$errorMesage]);
                }
            }

            Professor::where([
                'professorID'=>$request->input('id'),
                "professorSoftDelete"=>0,
            ])
            ->update([
                "professorFullname"=>strtoLower($request->input('fullname')),
                "professorStatus"=>strtoLower($request->input('status')),
                "professorRank"=>strtoLower($request->input('rank')),
                "professorDesignation"=>strtolower($request->input('designation')) ]);

            return response()->json(["status"=>true,"msg"=>"Successfully Updated"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($id){
        try{
            Professor::where(["professorID"=>$id])->update([
                "professorSoftDelete"=>1,
            ]);
            return response()->json(["status"=>true, "msg"=>"Successfully Deleted!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
