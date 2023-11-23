<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RankController extends Controller
{
    protected function create(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'rank'=> 'required',
                'hour'=>'required|Integer',
                'tokens'=>'required',
            ]);

            if($validator->fails()){
                if($validator->errors()->has('rank')){
                    $errorMesage = $validator->errors()->first('rank');
                    return response(["status"=>false, "error"=>"rank", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('hour')){
                    $errorMesage = $validator->errors()->first('hour');
                    return response(["status"=>false, "error"=>"hour", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('tokens')){
                    $errorMesage = $validator->errors()->first('tokens');
                    Log::error($errorMesage);
                }
            }

            if($request->input('hour') < 10 || $request->input('hour') > 50  ){
                return response(["status"=>false, "error"=>"hour", "msg"=>"minimun 5 hours and maximun is 50hours"]);
            }

            $departments = $this->tokenDepartment($request->input('tokens'));

            $check = Rank::join("departments","rankDepartment","=","department")
            ->where(['rankName'=>$request->input('rank'), 'rankDepartment'=>$departments,"departmentSoftDelete"=>0])
            ->count();

            if($check > 0){
                return response()->json(['status'=>false, "error"=>"rank", "msg"=>"This ranks is already registered!"]);
            }

            $save = new Rank();
            $save->rankName = strtolower($request->input('rank'));
            $save->rankHour = $request->input('hour');
            $save->rankDepartment = $departments;
            $result = $save->save();

            if(!$result){
                Log::error("Failed to create ranks!");
            }

            return response()->json(["status"=>true,"msg"=>"Successfully Created!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read($tokens){
        try{

            $departments = $this->tokenDepartment($tokens);
            $fetch = Rank::join("departments","rankDepartment","=","department")
            ->where(['rankSoftDelete'=>0, 'rankDepartment'=>$departments,"departmentSoftDelete"=>0])
            ->select(['rankID as id', "rankName as rank", "rankHour as hour"])->get();

            return response()->json($fetch);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function update(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'id'=>'required',
                'rank'=> 'required',
                'hour'=>'required|Integer',
            ]);

            if($validator->fails()){

                if($validator->errors()->has('id')){
                    $errorMesage = $validator->errors()->first('id');
                    Log::error($errorMesage);
                }

                if($validator->errors()->has('rank')){
                    $errorMesage = $validator->errors()->first('rank');
                    return response(["status"=>false, "error"=>"rank", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('hour')){
                    $errorMesage = $validator->errors()->first('hour');
                    return response(["status"=>false, "error"=>"hour", "msg"=>$errorMesage]);
                }
            }

            Rank::join("departments","rankDepartment","=","department")->
            where(['rankID'=>$request->input('id'),"rankSoftDelete"=>0,"departmentSoftDelete"=>0])
            ->update(['rankName'=>strtolower($request->input('rank')),'rankHour'=>$request->input('hour')]);

            return response()->json(["status"=>true, "msg"=>"Successfully Updated!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($id){
        try{
            Rank::join("departments","rankDepartment","=","department")
            ->where(['rankID'=>$id])->update(['rankSoftDelete'=>1,"departmentSoftDelete"=>0]);
            return response()->json(["status"=>true, "msg"=>"Successfully Deleted!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
