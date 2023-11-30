<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Professor;
use App\Models\section_opened;
use App\Models\sections;
use App\Models\Subject;
use App\Models\Load;
use App\Models\OfficialTime;
use App\Models\Schedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MinorController extends Controller
{
    protected function read_professor($tokens){
        //user login professors
        try{
            $department = $this->tokenDepartment($tokens);
            $read = Professor::where(['professorDepartment'=>$department,"professorSoftDelete"=>0])
            ->select("professorID","professorFullname as fullname")
            ->get();

            return response()->json($read);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read_classroom(Request $request){
        ///department(other) // department(login)
        try{
            $validator = Validator::make($request->all(),[
                "type"=>"required|in:lecture,laboratory",
                "department"=>"required",
                "tokens"=>"required"
            ]);

            $department = $this->tokenDepartment($request->input('tokens'));
            $otherDepartment = $request->input('department');

            $ownClassroom = Classroom::where(["classroomType"=>$request->input('type'),"classroomDepartment"=>$department,"classroomSoftDelete"=>0])
            ->select("classroomID as id","classroomName as classroom")
            ->get();

            $otherClassroom = Classroom::where(["classroomType"=>$request->input('type'),"classroomDepartment"=>$otherDepartment,"classroomSoftDelete"=>0])
            ->select("classroomID as id","classroomName as classroom")
            ->get();

            $arr_classroom = array();
            if(count($ownClassroom) > 0){
                foreach($ownClassroom as $classroom){
                    array_push($arr_classroom,["id"=>$classroom['id'],"classroom"=>$classroom['classroom'].' - '.$department]);
                }
            }

            if(count($otherClassroom) > 0){
                foreach($otherClassroom as $classroom){
                    array_push($arr_classroom,["id"=>$classroom['id'],"classroom"=>$classroom['classroom'].' - '.$otherDepartment]);
                }
            }

            return response()->json($arr_classroom);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }

    }

    protected function read_section(Request $request){
        //subject to get what section year show;
        try{
            $validator = Validator::make($request->all(),[
                "subject"=>"required",
                "department"=>"required"
            ]);

            $subjects = Subject::
            join("year_levels","subjectYearlevel","=","yearlevelID")
            ->where([
                "subjectCode"=>$request->input('subject')
                ])
            ->select("subjectSemester as semester","yearlevel as year")
            ->first();

            $sub_semester = $subjects['semester'];
            $sub_year = $subjects['year'];

            $sections = sections::
            join("year_levels","sectionYearlevel","=","yearlevelID")
            ->where([
                'sectionDepartment'=>$request->input('department'),
                "yearlevel"=>$sub_year,
                "sectionSemester"=>$sub_semester,
                "sectionSoftDelete"=>0
            ])
            ->select("sectionID as id")
            ->first();

            $section_opens = section_opened::where(['sectionopenedID'=>$sections['id'],"sectionopenedSoftDelete"=>0])
            ->select("sectionopenedName as section")
            ->get();

            return response()->json($section_opens);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
