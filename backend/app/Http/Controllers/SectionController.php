<?php

namespace App\Http\Controllers;

use App\Models\section_opened;
use App\Models\sections;
use App\Models\YearLevel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{

    protected function auto_generated_slot($tokens){
        try{

            $semesters = ["1st semester","2nd semester"];

            $department = $this->tokenDepartment($tokens);

            $read_yearlevel = YearLevel::where(['yearlevelDepartment'=>$department, "yearlevelSoftDelete"=>0])
            ->select("yearlevelID as id")
            ->get();

            if(count($read_yearlevel) > 0){
                foreach($read_yearlevel as $yearlevel){
                    $check = sections::where(["sectionYearlevel"=>$yearlevel['id']])->count();
                    if($check === 0){
                        foreach($semesters as $semester){
                            $save = new sections();
                            $save->sectionSlot = 0;
                            $save->sectionSemester = $semester;
                            $save->sectionYearlevel = $yearlevel['id'];
                            $save->sectionDepartment = $department;
                            $save->sectionSoftDelete = false;
                            $save->save();
                        }
                    }
                }
            }

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read_slot(Request $request){
        try{

            $validator = Validator::make($request->all(),[
                'year'=>"required",
                'semester'=>"required|in:1st semester,2nd semester"
            ]);

            $read = sections::where(['sectionYearlevel'=>$request->input('year'),'sectionSemester'=>$request->input('semester')])
            ->select("sectionSlot as slot")
            ->first();

            return response()->json($read);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function update_slot(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'year'=>"required",
                'semester'=>"required|in:1st semester,2nd semester",
                'slot'=>"required|numeric|between:0,30",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('slot')){
                    $errorMessage = $validator->errors()->first('slot');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"slot"]);
                }
            }

            $sections  = sections::where(['sectionYearlevel'=>$request->input('year'),'sectionSemester'=>$request->input('semester')])
            ->select("sectionID as id")
            ->get();

            foreach($sections as $section){
                section_opened::where(['sectionopenedID'=>$section['id']])->update(['sectionopenedSoftDelete'=>true]);
            }

            sections::where(['sectionYearlevel'=>$request->input('year'),'sectionSemester'=>$request->input('semester')])
            ->update(['sectionSlot'=>$request->input('slot')]);
            
            return response()->json(['status'=>true,'msg'=>"Successfully Updated."]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function create_section(Request $request){
        try {
            
            $validator = Validator::make($request->all(),[
                'year'=>"required",
                'semester'=>"required|in:1st semester,2nd semester",
                'section'=>"required",
                'specialization'=>"required"
            ]);

            if($validator->fails()){
                if($validator->errors()->has('section')){
                    $errorMessage = $validator->errors()->first('section');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"section"]);
                }

                if($validator->errors()->has('specialization')){
                    $errorMessage = $validator->errors()->first('specialization');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"specialization"]);
                }
            }

            $section = sections::where(['sectionYearlevel'=>$request->input('year'),"sectionSemester"=>$request->input('semester'), "sectionSoftDelete"=>0])
            ->select("sectionID as id", "sectionSlot as slot")
            ->first();

            $id = $section->id;
            $slot = $section->slot;

            $totalSection = section_opened::where(["sectionopenedID"=>$id,'sectionopenedSoftDelete'=>0])
            ->select("sectionopenedName as section")
            ->get();
            if( count($totalSection)  >= $slot){
                return response()->json(['status'=>false, "msg"=>"You reach maximum slot."]);
            }
            else{

                if(count($totalSection) > 0){
                    foreach($totalSection as $section){
                        if($section['section'] == strtolower($request->input('section'))){
                            return response()->json(['status'=>false,"msg"=>$request->input('section')." is already registered"]);
                        }
                    }
                }

                $save = new section_opened();
                $save->sectionopenedID = $id;
                $save->sectionopenedName = strtolower($request->input('section'));
                $save->sectionopenedSpecialization = $request->input('specialization');
                $save->save();

                return response()->json(["status"=>true,"msg"=>"Successfully Created"]);

            }

            

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    protected function read_section(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'year'=>"required",
                'semester'=>"required|in:1st semester,2nd semester",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('section')){
                    $errorMessage = $validator->errors()->first('section');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"section"]);
                }

                if($validator->errors()->has('specialization')){
                    $errorMessage = $validator->errors()->first('specialization');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"specialization"]);
                }
            } 

            $section = sections::where(['sectionYearlevel'=>$request->input('year'),"sectionSemester"=>$request->input('semester'), "sectionSoftDelete"=>0])
            ->select("sectionID as id")
            ->first();

            $read = section_opened::join("sections",'sectionopenedID','=','sectionID')
            ->join("specializations","sectionopenedSpecialization",'=','specializationID')
            ->where(["sectionopenedID"=>$section->id,'sectionopenedSoftDelete'=>0, 'sectionSoftDelete'=>0])
            ->select("sectionID as id","sectionopenedName as section","specializationID","specializationName as specialization","sectionSemester as semester","sectionYearlevel as year")
            ->get();

            return response()->json($read);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function update_section(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'id'=>"required",
                'section'=>"required",
                'specialization'=>"required",
                'semester'=>"required|in:1st semester,2nd semester",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('id')){
                    $errorMessage = $validator->errors()->first('id');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"id"]);
                }

                if($validator->errors()->has('section')){
                    $errorMessage = $validator->errors()->first('section');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"section"]);
                }

                if($validator->errors()->has('specialization')){
                    $errorMessage = $validator->errors()->first('specialization');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"specialization"]);
                }

                if($validator->errors()->has('semester')){
                    $errorMessage = $validator->errors()->first('semester');
                    return response()->json(["status"=>false,"msg"=>$errorMessage, "error"=>"semester"]);
                }
            } 

            section_opened::join("sections","sectionopenedID",'=','sectionID')
            ->where(['sectionopenedID'=>$request->input('id'),'sectionSemester'=>$request->input('semester')])
            ->update(['sectionopenedName'=>$request->input('section'),'sectionopenedSpecialization'=>$request->input('specialization')]);
            
            return response()->json(['status'=>true,'msg'=>"Successfully Updated"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete_section($section){
        try{
            section_opened::where(['sectionopenedName'=>$section])
            ->delete();
            return response()->json(['status'=>true,'msg'=>"Successfully Deleted"]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
