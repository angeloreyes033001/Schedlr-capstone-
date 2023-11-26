<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Load;
use App\Models\Professor;
use App\Models\sections;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoadController extends Controller
{
    protected function read_load_professor($tokens){
        try{

            $department = $this->tokenDepartment($tokens);

            $professors = Professor::where(['professorDepartment'=>$department,'professorSoftDelete'=>0])
            ->join("ranks","professorRank","=","rankID")
            ->select("professorID", "professorFullname as fullname","rankID","rankName as rank","rankHour as hour", "professorDesignation as designated")
            ->get();

            $renewals = array();

            foreach($professors as $professor){
                if($professor['designated'] != 'none'){
                    $designation = Designation::where(['designationPosition'=>$professor['designated']])
                    ->select('designationHour as hour')
                    ->first();

                    array_push($renewals,[
                        "professorID"=>$professor['professorID'],
                        "fullname"=>$professor['fullname'],
                        "rankID"=>$professor['rankID'],
                        "rank"=>$professor['rank'],
                        "hour"=>$designation['hour'],
                        "designated"=>$professor['designated']
                    ]);
                }
                else{
                    array_push( $renewals,$professor);
                }
            }

            $output = array();
            foreach($renewals as $renew){
                $load = Load::where(["LoadProfessor"=>$renew['professorID'],"loadDepartment"=>$department,"loadSoftDelete"=>0])
                ->select("loadID as id","loadProfessor as professor", "loadSubject as subject","loadHour as hour")
                ->get();
                array_push($output,['info'=>$renew,"loads"=>$load]);
            }

            return response()->json($output);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read_all_load(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'year'=>"required",
                "semester"=>"required",
                "professor"=>"required",
                "tokens"=>"required"
            ]);

            $department = $this->tokenDepartment($request->input('tokens'));

            $totalSection = sections::where([
                'sectionYearlevel'=>$request->input('year'),
                'sectionSemester'=>$request->input('semester'),
                'sectionDepartment'=>$department,
            ])
            ->select("sectionID as id","sectionSlot as total")
            ->first();

            $subjects = Subject::where([
                'subjectYearlevel'=>$request->input('year'),
                'subjectSemester'=>$request->input('semester'),
                'subjectDepartment'=>$department
                ])
            ->select("subjectCode as code","subjectName as subject","subjectLecHour as lec","subjectLabHour as lab")
            ->get();

            $totalProfessorHours = Load::join("professors","professorID","=","loadProfessor")
            ->join('ranks',"professorRank","=","rankID")
            ->where(["loadProfessor"=>$request->input('professor'),"loadDepartment"=>$department,"loadSoftDelete"=>0])
            ->select("loadHour as hour")
            ->get();

            $professorCurrentHour = 0;
            foreach($totalProfessorHours as $total){
                $professorCurrentHour += $total['hour'];
            }

            $output = array();

            foreach($subjects as $subject){

                $totalSubjectHours = Load::where(["loadSubject"=>$subject['code'],"loadDepartment"=>$department,"loadSoftDelete"=>0])
                ->select("loadHour as hour")
                ->get();
                
                $subjectCurrentHour = 0;
                foreach($totalSubjectHours as $total){
                    $subjectCurrentHour = $subjectCurrentHour + $total['hour'];
                }

                $subjectMaxHour = ($subject['lec'] + $subject['lab']) * $totalSection['total'];

                

                $checkDesignated = Professor::join("ranks","professorRank","=","rankID")
                ->where(["professorID"=>$request->input('professor')])
                ->select('professorDesignation as designated', "rankHour as hour")
                ->first();

                $designatedHour = 0;
                if($checkDesignated->designated != 'none'){
                    $hours = Designation::where(['designationPosition'=>$checkDesignated->designated])
                    ->select("designationHour as hour")->first();
                    $designatedHour = $hours->hour;
                }
                else{
                    $designatedHour = $checkDesignated['hour'];
                }

                $loads = Load::join("professors","professorID","=","loadProfessor")
                    ->join('ranks',"professorRank","=","rankID")
                    ->where(['loadSubject'=>$subject['code'],"loadDepartment"=>$department,"loadSoftDelete"=>0])
                    ->select("loadID as id","loadHour as hour","professorID as professor","professorFullname as fullname")
                    ->get();

                    array_push($output,[
                        "code"=>$subject['code'],
                        "subject"=>$subject['subject'],
                        "professorCurrentHour"=>$professorCurrentHour,
                        "professorMaxHour"=>$designatedHour,
                        "subjectCurrentHour"=>$subjectCurrentHour,
                        "subjectMaxHour"=>$subjectMaxHour,
                        "loads"=>$loads,
                    ]);
                array_push($output,);
            }

            return response()->json($output);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function create_load(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "year"=>"required",
                "semester"=>"required|in:1st semester,2nd semester",
                "professor"=>"required",
                "subject"=>"required",
                "hour"=>"required|between:0,30",
                "tokens"=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('hour')){
                    $errorMesage = $validator->errors()->first('hour');
                    return response(["status"=>false, "error"=>"hour", "msg"=>$errorMesage]);
                }
            }

            $department = $this->tokenDepartment($request->input('tokens'));

            $totalSection = sections::where([
                'sectionYearlevel'=>$request->input('year'),
                'sectionSemester'=>$request->input('semester'),
                'sectionDepartment'=>$department,
            ])
            ->select("sectionID as id","sectionSlot as total")
            ->first();

            $subjectHour = Subject::where(['subjectCode'=>$request->input('subject')])
            ->select("subjectLecHour as lec","subjectLabHour as lab")
            ->first();

            $totalSubjectHours = Load::where(["loadSubject"=>$request->input('subject'),"loadDepartment"=>$department,"loadSoftDelete"=>0])
                ->select("loadHour as hour")
                ->get();
                
            $subjectCurrentHour = 0;
            foreach($totalSubjectHours as $total){
                $subjectCurrentHour = $subjectCurrentHour + $total['hour'];
            }

            $totalSubjectHour = ($subjectHour['lec'] + $subjectHour['lab']) * $totalSection['total'];

            if($subjectCurrentHour === $totalSubjectHour){
                return response()->json(["status"=>false, "This subject reach maximun time"]);
            }
            else{
                $newTime = $subjectCurrentHour + $request->input('hour');
                if($newTime <= $totalSubjectHour){
                    $check = Load::where(['loadSubject'=>$request->input('subject'),"loadProfessor"=>$request->input('professor'),"loadDepartment"=>$department,"loadSoftDelete"=>0])
                    ->count();
                    if($check > 0){
                        return response()->json(['status'=>false,"msg"=> strtoupper($request->input('subject'))." is already assigned in this person."]);
                    }

                    $save = new Load();
                    $save->loadProfessor = $request->input('professor');
                    $save->loadSubject = $request->input('subject');
                    $save->loadHour = $request->input('hour');
                    $save->loadDepartment = $department;
                    $save->loadSoftDelete = 0;
                    $save->save();

                    return response()->json(['status'=>true,"msg"=>"Successfully Added." ]);
                }
                else{
                    return response()->json(["status"=>false, "msg"=>"The maximun hour's are ".$totalSubjectHour]);
                }
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete_load($id){
        try{
            Load::where(['loadID'=>$id,"loadSoftDelete"=>0])
            ->update(['loadSoftDelete'=>1]);

            return response()->json(['status'=>true,"msg"=>"Successsfully Deleted"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
