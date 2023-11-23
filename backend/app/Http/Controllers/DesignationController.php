<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    protected function create(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "position"=> "required",
                "hour"=>'required|numeric|min:0|max:30',
            ]);

            if($validator->fails()){
                if($validator->errors()->has('position')){
                    $errorMesage = $validator->errors()->first('position');
                    return response(["status"=>false, "error"=>"position", "msg"=>$errorMesage]);
                }

                if($validator->errors()->has('hour')){
                    $errorMesage = $validator->errors()->first('hour');
                    return response(["status"=>false, "error"=>"hour", "msg"=>$errorMesage]);
                }
            }

            $check =  Designation::where(['designationPosition'=>$request->input('position'),'designationSoftDelete'=>0])->count();
            if($check > 0){
                return response()->json(["status"=>false,"msg"=>"This position is already registered."]);
            }

            $checkDeleted = Designation::where(['designationPosition'=>$request->input('position'),'designationSoftDelete'=>1])->count();
            if($checkDeleted > 0){
                Designation::where(['designationPosition'=>$request->input('position'),'designationSoftDelete'=>1])->update(["designationSoftDelete"=>0, "designationHour"=>$request->input('hour')]);
            }
            else{
                $save = new Designation();
                $save->designationPosition = strtolower($request->input('position'));
                $save->designationHour = $request->input('hour');
                $save->save();
            }

            return response()->json(["status"=>true, "msg"=>"Successfully Created!"]);

        }
        catch(Exception $e){    
            Log::error($e->getMessage());
        }
    }

    protected function read(){
        try{
            $fetch = Designation::where(['designationSoftDelete'=>0])
            ->select("designationPosition as position", "designationHour as hour")
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
                "position"=> "required",
                "hour"=>'required|numeric|min:0|max:30',
            ]);

            if($validator->fails()){
                if($validator->errors()->has('hour')){
                    $errorMesage = $validator->errors()->first('hour');
                    return response(["status"=>false, "error"=>"hour", "msg"=>$errorMesage]);
                }
            }

            Designation::where(["DesignationPosition"=>strtolower($request->input('position'))])->update(["designationHour"=>$request->input('hour')]);
            return response()->json(["status"=>true,"msg"=>"Successfully Updated"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($position){
        try{
            Designation::where(["designationPosition"=>$position])->update(["designationSoftDelete"=>1]);
            return response()->json(["status"=>true,"msg"=>"Successfully Deleted"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
