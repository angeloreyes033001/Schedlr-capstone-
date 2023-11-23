<?php

namespace App\Http\Controllers;

use App\Models\Load;
use App\Models\Subject;
use App\Models\YearLevel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoadController extends Controller
{
    protected function create(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "professor"=>"required",
                "subject"=>"required",
                "tokens"=>"required",
            ]);

            if($validator->fails()){

                if($validator->errors()->has('subject')){
                    $msgError = $validator->errors()->first('subject');
                    return response()->json(['status'=>false, "error"=>"subject", "msg"=>$msgError]);
                }
            }

            $check = Load::where(["loadProfessor"=>$request->input('professor'),"loadSubject"=>$request->input('subject'), "loadSoftDelete"=>0])->count();

            if($check > 0){
                return response()->json(['status'=>false, "error"=>"subject", "msg"=>"This subject is already taken."]);
            }

            $departments = $this->tokenDepartment($request->input('tokens'));
            
            $save = new Load();
            $save->loadProfessor = strtolower($request->input('professor'));
            $save->loadSubject = strtolower($request->input('subject'));
            $save->loadDepartment = $departments;
            $result = $save->save();

            if(!$result){
                Log::error("Failed to create load!");
            }
            return response()->json(["status"=>true,"msg"=>"Successfully Created"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read($professor){
        try{
            $read =  Load::where(['loadProfessor'=>$professor , "loadSoftDelete"=>0, "yearlevelSoftDelete"=>0, "subjectSoftDelete"=>0])
            ->join("subjects","loadSubject","=","subjectCode")
            ->join("year_levels", "subjectYearlevel","=","yearlevelID")
            ->select("loadProfessor as professor","loadID as id", "subjectCode as code","subjectName as subject","subjectSemester as semester","subjectLecHour as lecture","subjectLabHour as laboratory","yearlevel as year")
            ->get();

            return response()->json($read);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function show_subject_assigned(Request $request){
        try{

            $departments = $this->tokenDepartment($request->input('tokens'));

            $semester = $request->input('semester');

            $years = YearLevel::where(['yearlevelDepartment'=>$departments,'yearlevelSoftDelete'=>0])
            ->orderBy('yearlevel', 'asc')
            ->select('yearlevelID as id','yearlevel as year')
            ->get();

            $subjects = Subject::where(['subjectDepartment'=>$departments,'subjectSemester'=>$semester,'subjectSoftDelete'=>0])
            ->join("year_levels","subjectYearlevel","=","yearlevelID")
            ->select("subjectCode as code","subjectSemester as semester","subjectYearlevel as yearID","yearlevel as year")
            ->get();

            $loads = Load::
            join("professors","loadProfessor","=","professorID")
            ->join('subjects','loadSubject',"=","subjectCode")
            ->where([
                'loadDepartment'=>$departments,
                'loadSoftDelete'=>0,
                'professorSoftDelete'=>0,
                "subjectSoftDelete"=>0
                ])
            ->select("professorID","professorFullname as fullname", "subjectCode as subject" ,"subjectSemester as semester")
            ->get();

            $output = array();
            foreach($years as $year){

                $collectionSubject = array();

                foreach($subjects as $subject){
                    if($year['year'] == $subject['year']){

                        $collectionLoad = array();

                        foreach($loads as $load){
                            if($subject['code'] === $load['subject'] ){
                                array_push($collectionLoad,["professorID"=>$load['professorID'],"fullname"=>$load['fullname']]);
                            }
                        }

                        array_push($collectionSubject,["code"=>$subject['code'],"professors"=>$collectionLoad]);
                    }
                }

                array_push($output,["year"=>$year['year'],"subjects"=>$collectionSubject]);
            }

            return response()->json($output);

            
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($id){
        try{
            Load::where(["loadID"=>$id])->update(["loadSoftDelete"=>1]);
            return response()->json(["status"=>true, "msg"=>"Successfully Deleted!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

}
