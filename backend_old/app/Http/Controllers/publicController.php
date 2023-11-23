<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\MinorSchedule;
use App\Models\OfficialTime;
use App\Models\Professor;
use App\Models\Schedule;
use App\Models\Section;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class publicController extends Controller
{
    
    protected $template = [
        "monday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "tuesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "wednesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "thursday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "friday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "saturday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
    ];

    protected function showProfessor($department){
        try{
            $fetch = Professor::where([ "professorDepartment"=>$department , "professorSoftDelete"=>0])
            ->select("professorID", "professorFullname")
            ->get();
            return response()->json($fetch);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function scheduleProfessor(Request $request){
        try{
            //must be add review number 2 mean approved.
            $fetch = Schedule::where(['scheduleProfessor'=>$request->input('professor'), 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
            ->join('professors','scheduleProfessor','=','professorID')
            ->join('ranks','professorRank','=','rankID')
            ->join('classrooms','scheduleClassroom', '=', 'classroomID')
            ->join('sections', 'scheduleSection', '=','sectionID')
            ->select(
                    'scheduleID as id',
                    "scheduleDay as day",
                    "scheduleStart as start",
                    "scheduleEnd as end",
                    "scheduleSubject as subject",
                    'professorFullname as fullname',
                    "classroomName as room",
                    "sectionName as section",
                )
            ->get();
    
            $day = null;
            for($i = 0; $i < count($fetch);$i++){
                $day = $fetch[$i]->day;
                for($x =  $fetch[$i]->start;$x <= $fetch[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$fetch[$i]['id'], "professor"=>$fetch[$i]['fullname'] , "section"=>$fetch[$i]['section'] , "subject"=>$fetch[$i]['subject']  ,  "room"=>$fetch[$i]['room'] );
                }
            }
    
            // schedule for other department
            $otherDepartment = MinorSchedule::where(['minorProfessor'=>$request->input('professor'), 'minorSemester'=>$request->input('semester'),"minorSoftDelete"=>0 ])
            ->join('professors','minorProfessor','=','professorID')
            ->join('sections', 'minorSection', '=','sectionID')
            ->select(
                "minorID as id",
                "minorDay as day", 
                "minorStart as start",
                "minorEnd as end",
                "minorSubject as subject",
                "professorFullname as fullname",
                "minorClassroom as room",
                "sectionName as section"
                )
            ->get();
            $day = null;
            for($i = 0; $i < count($otherDepartment);$i++){
                $day = $otherDepartment[$i]->day;
                for($x =  $otherDepartment[$i]->start;$x <= $otherDepartment[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$otherDepartment[$i]['id'], "professor"=>$otherDepartment[$i]['fullname'] , "section"=>$otherDepartment[$i]['section'] , "subject"=>$otherDepartment[$i]['subject']  ,  "room"=>$otherDepartment[$i]['room'] );
                }
            }
    
            //official time
            $official = OfficialTime::where(['officialtimeProfessor'=>$request->input('professor'), 'officialtimeSemester'=>$request->input('semester'),"officialtimeSoftDelete"=>0 ])
            ->select("officialtimeDay as day", "officialtimeStart as start","officialtimeEnd as end")
            ->get();
    
            for($i = 0; $i < count($official);$i++){
                $day = $official[$i]->day;
                for($x =  $official[$i]->start;$x <= $official[$i]->end; $x++ ){
                    $this->template[$day][$x] = 1;
                }
            }
    
            $professorSchedule = [];
            for($i = 0; $i < 31; $i++){
                $time = $this->times[$i];
                $monday = $this->template["monday"][$i];
                $tuesday = $this->template["tuesday"][$i];
                $wednesday = $this->template["wednesday"][$i];
                $thursday = $this->template["thursday"][$i];
                $friday = $this->template["friday"][$i];
                $saturday = $this->template["saturday"][$i];
                array_push($professorSchedule, ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday]);
            }

            $professors = Professor::where(["professorID"=>$request->input('professor')])
            ->select('professorFullname as fullname', "professorDepartment as department")
            ->first();
            return response()->json($professorSchedule);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function printProfessor(Request $request){
        try{
            //must be add review number 2 mean approved.
            $fetch = Schedule::where(['scheduleProfessor'=>$request->input('professor'), 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
            ->join('professors','scheduleProfessor','=','professorID')
            ->join('ranks','professorRank','=','rankID')
            ->join('classrooms','scheduleClassroom', '=', 'classroomID')
            ->join('sections', 'scheduleSection', '=','sectionID')
            ->select(
                    'scheduleID as id',
                    "scheduleDay as day",
                    "scheduleStart as start",
                    "scheduleEnd as end",
                    "scheduleSubject as subject",
                    'professorFullname as fullname',
                    "classroomName as room",
                    "sectionName as section",
                )
            ->get();
    
            $day = null;
            for($i = 0; $i < count($fetch);$i++){
                $day = $fetch[$i]->day;
                for($x =  $fetch[$i]->start;$x <= $fetch[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$fetch[$i]['id'], "professor"=>$fetch[$i]['fullname'] , "section"=>$fetch[$i]['section'] , "subject"=>$fetch[$i]['subject']  ,  "room"=>$fetch[$i]['room'] );
                }
            }
    
            // schedule for other department
            $otherDepartment = MinorSchedule::where(['minorProfessor'=>$request->input('professor'), 'minorSemester'=>$request->input('semester'),"minorSoftDelete"=>0 ])
            ->join('professors','minorProfessor','=','professorID')
            ->join('sections', 'minorSection', '=','sectionID')
            ->select(
                "minorID as id",
                "minorDay as day", 
                "minorStart as start",
                "minorEnd as end",
                "minorSubject as subject",
                "professorFullname as fullname",
                "minorClassroom as room",
                "sectionName as section"
                )
            ->get();
            $day = null;
            for($i = 0; $i < count($otherDepartment);$i++){
                $day = $otherDepartment[$i]->day;
                for($x =  $otherDepartment[$i]->start;$x <= $otherDepartment[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$otherDepartment[$i]['id'], "professor"=>$otherDepartment[$i]['fullname'] , "section"=>$otherDepartment[$i]['section'] , "subject"=>$otherDepartment[$i]['subject']  ,  "room"=>$otherDepartment[$i]['room'] );
                }
            }
    
            //official time
            $official = OfficialTime::where(['officialtimeProfessor'=>$request->input('professor'), 'officialtimeSemester'=>$request->input('semester'),"officialtimeSoftDelete"=>0 ])
            ->select("officialtimeDay as day", "officialtimeStart as start","officialtimeEnd as end")
            ->get();
    
            for($i = 0; $i < count($official);$i++){
                $day = $official[$i]->day;
                for($x =  $official[$i]->start;$x <= $official[$i]->end; $x++ ){
                    $this->template[$day][$x] = 1;
                }
            }
    
            $professorSchedule = [];
            for($i = 0; $i < 31; $i++){
                $time = $this->times[$i];
                $monday = $this->template["monday"][$i];
                $tuesday = $this->template["tuesday"][$i];
                $wednesday = $this->template["wednesday"][$i];
                $thursday = $this->template["thursday"][$i];
                $friday = $this->template["friday"][$i];
                $saturday = $this->template["saturday"][$i];
                array_push($professorSchedule, ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday]);
            }

            $professors = Professor::where(["professorID"=>$request->input('professor')])
            ->select('professorFullname as fullname', "professorDepartment as department")
            ->first();

            return response()->json([[ "name"=>$professors['fullname'], "semester"=> $request->input('semester'), "department"=>$professors['department'], "schedule"=>$professorSchedule]]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function showClassroom($department){
        try{
            $fetch = Classroom::join('year_levels','classroomYearlevel','=','yearlevelID')
            ->where(['classroomDepartment'=>$department, 'classroomSoftDelete'=>0])
            ->select("classroomID as id", "classroomName as classroom", "classroomType as type" ,"yearlevel")
            ->get();

            return response()->json($fetch);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function scheduleClassroom(Request $request){
        try{
            $fetch = Schedule::where(['scheduleClassroom'=>$request->input('classroom'), 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
            ->join('professors','scheduleProfessor','=','professorID')
            ->join('ranks','professorRank','=','rankID')
            ->join('classrooms','scheduleClassroom', '=', 'classroomID')
            ->join('sections', 'scheduleSection', '=','sectionID')
            ->select(
                    'scheduleID as id',
                    "scheduleDay as day",
                    "scheduleStart as start",
                    "scheduleEnd as end",
                    "scheduleSubject as subject",
                    'professorFullname as fullname',
                    "classroomName as room",
                    "sectionName as section",
                )
            ->get();

            $day = null;
            for($i = 0; $i < count($fetch);$i++){
                $day = $fetch[$i]->day;
                for($x =  $fetch[$i]->start;$x <= $fetch[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$fetch[$i]['id'], "professor"=>$fetch[$i]['fullname'] , "section"=>$fetch[$i]['section'] , "subject"=>$fetch[$i]['subject']  ,  "room"=>$fetch[$i]['room'] );
                }
            }

            // schedule for other department
            $otherDepartment = MinorSchedule::where(['minorClassroom'=>$request->input('classroom'), 'minorSemester'=>$request->input('semester'),"minorSoftDelete"=>0 ])
            ->join('professors','minorProfessor','=','professorID')
            ->join('sections', 'minorSection', '=','sectionID')
            ->select(
                "minorID as id",
                "minorDay as day", 
                "minorStart as start",
                "minorEnd as end",
                "minorSubject as subject",
                "professorFullname as fullname",
                "minorClassroom as room",
                "sectionName as section"
                )
            ->get();
            $day = null;
            for($i = 0; $i < count($otherDepartment);$i++){
                $day = $otherDepartment[$i]->day;
                for($x =  $otherDepartment[$i]->start;$x <= $otherDepartment[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$otherDepartment[$i]['id'], "professor"=>$otherDepartment[$i]['fullname'] , "section"=>$otherDepartment[$i]['section'] , "subject"=>$otherDepartment[$i]['subject']  ,  "room"=>$otherDepartment[$i]['room'] );
                }
            }

            $classroomSchedule = [];
            for($i = 0; $i < 31; $i++){
                $time = $this->times[$i];
                $monday = $this->template["monday"][$i];
                $tuesday = $this->template["tuesday"][$i];
                $wednesday = $this->template["wednesday"][$i];
                $thursday = $this->template["thursday"][$i];
                $friday = $this->template["friday"][$i];
                $saturday = $this->template["saturday"][$i];
                array_push($classroomSchedule, ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday]);
            }

            return response()->json($classroomSchedule);
            }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function printClassroom(Request $request){
        try{
            $fetch = Schedule::where(['scheduleClassroom'=>$request->input('classroom'), 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
            ->join('professors','scheduleProfessor','=','professorID')
            ->join('ranks','professorRank','=','rankID')
            ->join('classrooms','scheduleClassroom', '=', 'classroomID')
            ->join('sections', 'scheduleSection', '=','sectionID')
            ->select(
                    'scheduleID as id',
                    "scheduleDay as day",
                    "scheduleStart as start",
                    "scheduleEnd as end",
                    "scheduleSubject as subject",
                    'professorFullname as fullname',
                    "classroomName as room",
                    "sectionName as section",
                )
            ->get();

            $day = null;
            for($i = 0; $i < count($fetch);$i++){
                $day = $fetch[$i]->day;
                for($x =  $fetch[$i]->start;$x <= $fetch[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$fetch[$i]['id'], "professor"=>$fetch[$i]['fullname'] , "section"=>$fetch[$i]['section'] , "subject"=>$fetch[$i]['subject']  ,  "room"=>$fetch[$i]['room'] );
                }
            }

            // schedule for other department
            $otherDepartment = MinorSchedule::where(['minorClassroom'=>$request->input('classroom'), 'minorSemester'=>$request->input('semester'),"minorSoftDelete"=>0 ])
            ->join('professors','minorProfessor','=','professorID')
            ->join('sections', 'minorSection', '=','sectionID')
            ->select(
                "minorID as id",
                "minorDay as day", 
                "minorStart as start",
                "minorEnd as end",
                "minorSubject as subject",
                "professorFullname as fullname",
                "minorClassroom as room",
                "sectionName as section"
                )
            ->get();
            $day = null;
            for($i = 0; $i < count($otherDepartment);$i++){
                $day = $otherDepartment[$i]->day;
                for($x =  $otherDepartment[$i]->start;$x <= $otherDepartment[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$otherDepartment[$i]['id'], "professor"=>$otherDepartment[$i]['fullname'] , "section"=>$otherDepartment[$i]['section'] , "subject"=>$otherDepartment[$i]['subject']  ,  "room"=>$otherDepartment[$i]['room'] );
                }
            }

            $classroomSchedule = [];
            for($i = 0; $i < 31; $i++){
                $time = $this->times[$i];
                $monday = $this->template["monday"][$i];
                $tuesday = $this->template["tuesday"][$i];
                $wednesday = $this->template["wednesday"][$i];
                $thursday = $this->template["thursday"][$i];
                $friday = $this->template["friday"][$i];
                $saturday = $this->template["saturday"][$i];
                array_push($classroomSchedule, ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday]);
            }

            $classroom = Classroom::where(['classroomID'=>$request->input('classroom')])
            ->select("classroomName as classroom", "classroomDepartment as department")
            ->first();

            return response()->json([["name"=>$classroom['classroom'],"semester"=>$request->input('semester'),"department"=>$classroom['department'],"schedule"=>$classroomSchedule]]);
            }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function showSection($department){
        try{
            $fetch = Section::join('year_levels','sectionYearlevel','=','yearlevelID')
            ->where([ "sectionDepartment"=>$department , "sectionSoftDelete"=>0])
            ->select("sectionID as id", "sectionName as section","yearlevel")
            ->get();
            return response()->json($fetch);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function scheduleSection(Request $request){
        try{
            $fetch = Schedule::where(['scheduleSection'=>$request->input('section'), 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
            ->join('professors','scheduleProfessor','=','professorID')
            ->join('ranks','professorRank','=','rankID')
            ->join('classrooms','scheduleClassroom', '=', 'classroomID')
            ->join('sections', 'scheduleSection', '=','sectionID')
            ->select(
                    'scheduleID as id',
                    "scheduleDay as day",
                    "scheduleStart as start",
                    "scheduleEnd as end",
                    "scheduleSubject as subject",
                    'professorFullname as fullname',
                    "classroomName as room",
                    "sectionName as section",
                )
            ->get();

            $day = null;
            for($i = 0; $i < count($fetch);$i++){
                $day = $fetch[$i]->day;
                for($x =  $fetch[$i]->start;$x <= $fetch[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$fetch[$i]['id'], "professor"=>$fetch[$i]['fullname'] , "section"=>$fetch[$i]['section'] , "subject"=>$fetch[$i]['subject']  ,  "room"=>$fetch[$i]['room'] );
                }
            }

            // schedule for other department
            $otherDepartment = MinorSchedule::where(['minorSection'=>$request->input('section'), 'minorSemester'=>$request->input('semester'),"minorSoftDelete"=>0 ])
            ->join('professors','minorProfessor','=','professorID')
            ->join('sections', 'minorSection', '=','sectionID')
            ->join('classrooms','minorClassroom', '=', 'classroomID')
            ->select(
                "minorID as id",
                "minorDay as day", 
                "minorStart as start",
                "minorEnd as end",
                "minorSubject as subject",
                "professorFullname as fullname",
                "classroomName as room",
                "sectionName as section"
                )
            ->get();

            $day = null;
            for($i = 0; $i < count($otherDepartment);$i++){
                $day = $otherDepartment[$i]->day;
                for($x =  $otherDepartment[$i]->start;$x <= $otherDepartment[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$otherDepartment[$i]['id'], "professor"=>$otherDepartment[$i]['fullname'] , "section"=>$otherDepartment[$i]['section'] , "subject"=>$otherDepartment[$i]['subject']  ,  "room"=>$otherDepartment[$i]['room'] );
                }
            }

            $sectionSchedule = [];
            for($i = 0; $i < 31; $i++){
                $time = $this->times[$i];
                $monday = $this->template["monday"][$i];
                $tuesday = $this->template["tuesday"][$i];
                $wednesday = $this->template["wednesday"][$i];
                $thursday = $this->template["thursday"][$i];
                $friday = $this->template["friday"][$i];
                $saturday = $this->template["saturday"][$i];
                array_push($sectionSchedule, ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday]);
            }

            return response()->json($sectionSchedule);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function printSection(Request $request){
        try{
            $fetch = Schedule::where(['scheduleSection'=>$request->input('section'), 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
            ->join('professors','scheduleProfessor','=','professorID')
            ->join('ranks','professorRank','=','rankID')
            ->join('classrooms','scheduleClassroom', '=', 'classroomID')
            ->join('sections', 'scheduleSection', '=','sectionID')
            ->select(
                    'scheduleID as id',
                    "scheduleDay as day",
                    "scheduleStart as start",
                    "scheduleEnd as end",
                    "scheduleSubject as subject",
                    'professorFullname as fullname',
                    "classroomName as room",
                    "sectionName as section",
                )
            ->get();

            $day = null;
            for($i = 0; $i < count($fetch);$i++){
                $day = $fetch[$i]->day;
                for($x =  $fetch[$i]->start;$x <= $fetch[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$fetch[$i]['id'], "professor"=>$fetch[$i]['fullname'] , "section"=>$fetch[$i]['section'] , "subject"=>$fetch[$i]['subject']  ,  "room"=>$fetch[$i]['room'] );
                }
            }

            // schedule for other department
            $otherDepartment = MinorSchedule::where(['minorSection'=>$request->input('section'), 'minorSemester'=>$request->input('semester'),"minorSoftDelete"=>0 ])
            ->join('professors','minorProfessor','=','professorID')
            ->join('sections', 'minorSection', '=','sectionID')
            ->select(
                "minorID as id",
                "minorDay as day", 
                "minorStart as start",
                "minorEnd as end",
                "minorSubject as subject",
                "professorFullname as fullname",
                "minorClassroom as room",
                "sectionName as section"
                )
            ->get();
            $day = null;
            for($i = 0; $i < count($otherDepartment);$i++){
                $day = $otherDepartment[$i]->day;
                for($x =  $otherDepartment[$i]->start;$x <= $otherDepartment[$i]->end; $x++ ){
                    $this->template[$day][$x] = array("id"=>$otherDepartment[$i]['id'], "professor"=>$otherDepartment[$i]['fullname'] , "section"=>$otherDepartment[$i]['section'] , "subject"=>$otherDepartment[$i]['subject']  ,  "room"=>$otherDepartment[$i]['room'] );
                }
            }

            $sectionSchedule = [];
            for($i = 0; $i < 31; $i++){
                $time = $this->times[$i];
                $monday = $this->template["monday"][$i];
                $tuesday = $this->template["tuesday"][$i];
                $wednesday = $this->template["wednesday"][$i];
                $thursday = $this->template["thursday"][$i];
                $friday = $this->template["friday"][$i];
                $saturday = $this->template["saturday"][$i];
                array_push($sectionSchedule, ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday]);
            }

            $section = Section::where(["sectionID"=>$request->input('section')])
            ->select('sectionName as section', "sectionDepartment as department")
            ->first();

            return response()->json([["name"=> $section['section'], "semester"=>$request->input('semester'), "department"=>$section['department'] ,"schedule"=>$sectionSchedule]]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    public function deanProfessorSchedule(Request $request){
        try{
            //tokens // semester
            $department = $this->tokenDepartment($request->input('tokens'));
            $professorList = Professor::join("ranks","professorRank","=","rankID")
            ->where(['professorDepartment'=>$department, "professorSoftDelete"=>0, "rankSoftDelete"=>0])
            ->select("professorID as professor", "professorFullname as fullname", "rankName as rank" ,"professorDesignation as designated")
            // ->orderBy("professorFullname", "asc")
            ->get();

            $output = array();
            for($i =0; $i < count($professorList); $i++){
                $profID = $professorList[$i]['professor'];
                $fullname =  $professorList[$i]['fullname'];
                $rank = $professorList[$i]['rank'];
                $designated = $professorList[$i]['designated'];
                
                $fetch = Schedule::where(['scheduleProfessor'=>$profID, 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
                ->join('professors','scheduleProfessor','=','professorID')
                ->join('ranks','professorRank','=','rankID')
                ->join('classrooms','scheduleClassroom', '=', 'classroomID')
                ->join('sections', 'scheduleSection', '=','sectionID')
                ->select(
                        'scheduleID as id',
                        "scheduleDay as day",
                        "scheduleStart as start",
                        "scheduleEnd as end",
                        "scheduleSubject as subject",
                        'professorFullname as fullname',
                        "classroomName as room",
                        "sectionName as section",
                    )
                ->get();
        
                $day = null;
                for($z = 0; $z < count($fetch);$z++){
                    $day = $fetch[$z]->day;
                    for($j =  $fetch[$z]->start;$j <= $fetch[$z]->end; $j++ ){
                        $this->template[$day][$j] = array("id"=>$fetch[$z]['id'], "professor"=>$fetch[$z]['fullname'] , "section"=>$fetch[$z]['section'],"subject"=>$fetch[$z]['subject'],"classroom"=>$fetch[$z]['room'] );
                    }
                }

                // schedule for other department
                $otherDepartment = MinorSchedule::where(['minorProfessor'=>$profID, 'minorSemester'=>$request->input('semester'),"minorSoftDelete"=>0 ])
                ->join('professors','minorProfessor','=','professorID')
                ->join('sections', 'minorSection', '=','sectionID')
                ->select(
                    "minorID as id",
                    "minorDay as day", 
                    "minorStart as start",
                    "minorEnd as end",
                    "minorSubject as subject",
                    "professorFullname as fullname",
                    "minorClassroom as room",
                    "sectionName as section"
                    )
                ->get();
                $day = null;
                for($z = 0; $z < count($otherDepartment);$z++){
                    $day = $otherDepartment[$z]->day;
                    for($j =  $otherDepartment[$z]->start;$j <= $otherDepartment[$z]->end; $j++ ){
                        $this->template[$day][$j] = array("id"=>$otherDepartment[$z]['id'], "professor"=>$otherDepartment[$z]['fullname'] , "section"=>$otherDepartment[$z]['section'] , "subject"=>$otherDepartment[$z]['subject']  ,  "classroom"=>$otherDepartment[$z]['room'] );
                    }
                }

                $official = OfficialTime::where(['officialtimeProfessor'=>$profID, 'officialtimeSemester'=>$request->input('semester'),"officialtimeSoftDelete"=>0 ])
                ->select("officialtimeDay as day", "officialtimeStart as start","officialtimeEnd as end")
                ->get();
        
                for($z = 0; $z < count($official);$z++){
                    $day = $official[$z]->day;
                    for($j =  $official[$z]->start;$j <= $official[$z]->end; $j++ ){
                        $this->template[$day][$j] = 1;
                    }
                }

                $professorSchedule = [];
                for($z = 0; $z < 31; $z++){
                    $time = $this->times[$z];
                    $monday = $this->template["monday"][$z];
                    $tuesday = $this->template["tuesday"][$z];
                    $wednesday = $this->template["wednesday"][$z];
                    $thursday = $this->template["thursday"][$z];
                    $friday = $this->template["friday"][$z];
                    $saturday = $this->template["saturday"][$z];
                    array_push($professorSchedule, ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday]);
                }
                array_push($output, ['id'=>$profID,'owner'=>$fullname, "rank"=>$rank , "designated"=>$designated , "schedule"=>$professorSchedule ]);
            
                //reset template again
                $this->template = [
                    "monday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "tuesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "wednesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "thursday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "friday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "saturday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                ];
                
            }

            return response()->json($output);
        }   
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    public function deanSectionSchedule(Request $request){
        try{
            //tokens // semester
            $department = $this->tokenDepartment($request->input('tokens'));
            $sectionList = Section::join("year_levels", "sectionYearlevel", "=", "yearlevelID")
            ->where(["sectionDepartment"=>$department, "sectionSoftDelete"=>0])
            ->select("sectionID as sectionID","sectionName as section", "yearlevelID", "yearlevel")
            ->get();

            $output = array();
            for($i =0; $i < count($sectionList); $i++){
                $sectionID = $sectionList[$i]['sectionID'];
                $section = $sectionList[$i]['section'];
                $yearlevelID = $sectionList[$i]['yearlevelID'];
                $yearlevel = $sectionList[$i]['yearlevel'];

                $fetch = Schedule::where(['scheduleSection'=>$sectionID, 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
                ->join('professors','scheduleProfessor','=','professorID')
                ->join('ranks','professorRank','=','rankID')
                ->join('classrooms','scheduleClassroom', '=', 'classroomID')
                ->join('sections', 'scheduleSection', '=','sectionID')
                ->select(
                        'scheduleID as id',
                        "scheduleDay as day",
                        "scheduleStart as start",
                        "scheduleEnd as end",
                        "scheduleSubject as subject",
                        'professorFullname as fullname',
                        "classroomName as room",
                        "sectionName as section",
                    )
                ->get();

                $day = null;
                for($z = 0; $z < count($fetch);$z++){
                    $day = $fetch[$z]->day;
                    for($x =  $fetch[$z]->start;$x <= $fetch[$z]->end; $x++ ){
                        $this->template[$day][$x] = array("id"=>$fetch[$z]['id'], "professor"=>$fetch[$z]['fullname'] , "section"=>$fetch[$z]['section'] , "subject"=>$fetch[$z]['subject']  ,  "classroom"=>$fetch[$z]['room'] );
                    }
                }

                $otherDepartment = MinorSchedule::where(['minorSection'=>$sectionID, 'minorSemester'=>$request->input('semester'),"minorSoftDelete"=>0 ])
                ->join('professors','minorProfessor','=','professorID')
                ->join('sections', 'minorSection', '=','sectionID')
                ->join('classrooms','minorClassroom', '=', 'classroomID')
                ->select(
                    "minorID as id",
                    "minorDay as day", 
                    "minorStart as start",
                    "minorEnd as end",
                    "minorSubject as subject",
                    "professorFullname as fullname",
                    "classroomName as room",
                    "sectionName as section"
                    )
                ->get();
                $day = null;
                for($z = 0; $z < count($otherDepartment);$z++){
                    $day = $otherDepartment[$z]->day;
                    for($x =  $otherDepartment[$z]->start;$x <= $otherDepartment[$z]->end; $x++ ){
                        $this->template[$day][$x] = array("id"=>$otherDepartment[$z]['id'], "professor"=>$otherDepartment[$z]['fullname'] , "section"=>$otherDepartment[$z]['section'] , "subject"=>$otherDepartment[$z]['subject']  ,  "classroom"=>$otherDepartment[$z]['room'] );
                    }
                }

                $sectionSchedule = [];
                for($z = 0; $z < 31; $z++){
                    $time = $this->times[$z];
                    $monday = $this->template["monday"][$z];
                    $tuesday = $this->template["tuesday"][$z];
                    $wednesday = $this->template["wednesday"][$z];
                    $thursday = $this->template["thursday"][$z];
                    $friday = $this->template["friday"][$z];
                    $saturday = $this->template["saturday"][$z];
                    array_push($sectionSchedule, ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday]);
                }
                
                array_push($output,[ "id"=>$sectionID ,"owner"=>$section,"yearlevelID"=>$yearlevelID,"yearlevel"=>$yearlevel, "schedule"=>$sectionSchedule]);
            
                $this->template = [
                    "monday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "tuesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "wednesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "thursday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "friday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "saturday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                ];

            }

            return response()->json($output);
        }   
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    public function deanClassroomSchedule(Request $request){
        try{
            //tokens // semester
            $department = $this->tokenDepartment($request->input('tokens'));
            $classroomList = Classroom::join("year_levels", "classroomYearlevel", "=", "yearlevelID")
            ->where(["classroomDepartment"=>$department, "classroomSoftDelete"=>0])
            ->select("classroomID","classroomName as classroom", "yearlevelID", "yearlevel")
            ->get();

            $output = array();
            for($i =0; $i < count($classroomList); $i++){
                
                $classroomID = $classroomList[$i]['classroomID'];
                $classroom = $classroomList[$i]['classroom'];
                $yearlevelID = $classroomList[$i]['yearlevelID'];
                $yearlevel = $classroomList[$i]['yearlevel'];

                $fetch = Schedule::where(['scheduleClassroom'=>$classroomID, 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
                ->join('professors','scheduleProfessor','=','professorID')
                ->join('ranks','professorRank','=','rankID')
                ->join('classrooms','scheduleClassroom', '=', 'classroomID')
                ->join('sections', 'scheduleSection', '=','sectionID')
                ->select(
                        'scheduleID as id',
                        "scheduleDay as day",
                        "scheduleStart as start",
                        "scheduleEnd as end",
                        "scheduleSubject as subject",
                        'professorFullname as fullname',
                        "classroomName as room",
                        "sectionName as section",
                    )
                ->get();

                $day = null;
                for($z = 0; $z < count($fetch);$z++){
                    $day = $fetch[$z]->day;
                    for($x =  $fetch[$z]->start;$x <= $fetch[$z]->end; $x++ ){
                        $this->template[$day][$x] = array("id"=>$fetch[$z]['id'], "professor"=>$fetch[$z]['fullname'] , "section"=>$fetch[$z]['section'] , "subject"=>$fetch[$z]['subject']  ,  "classroom"=>$fetch[$z]['room'] );
                    }
                }

                // schedule for other department
                $otherDepartment = MinorSchedule::where(['minorClassroom'=>$classroomID, 'minorSemester'=>$request->input('semester'),"minorSoftDelete"=>0 ])
                ->join('professors','minorProfessor','=','professorID')
                ->join('sections', 'minorSection', '=','sectionID')
                ->select(
                    "minorID as id",
                    "minorDay as day", 
                    "minorStart as start",
                    "minorEnd as end",
                    "minorSubject as subject",
                    "professorFullname as fullname",
                    "minorClassroom as room",
                    "sectionName as section"
                    )
                ->get();
                $day = null;
                for($z = 0; $z < count($otherDepartment);$z++){
                    $day = $otherDepartment[$z]->day;
                    for($x =  $otherDepartment[$z]->start;$x <= $otherDepartment[$z]->end; $x++ ){
                        $this->template[$day][$x] = array("id"=>$otherDepartment[$z]['id'], "professor"=>$otherDepartment[$z]['fullname'] , "section"=>$otherDepartment[$z]['section'] , "subject"=>$otherDepartment[$z]['subject']  ,  "classroom"=>$otherDepartment[$z]['room'] );
                    }
                }

                $classroomSchedule = [];
                for($z = 0; $z < 31; $z++){
                    $time = $this->times[$z];
                    $monday = $this->template["monday"][$z];
                    $tuesday = $this->template["tuesday"][$z];
                    $wednesday = $this->template["wednesday"][$z];
                    $thursday = $this->template["thursday"][$z];
                    $friday = $this->template["friday"][$z];
                    $saturday = $this->template["saturday"][$z];
                    array_push($classroomSchedule, ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday]);
                }
                
                array_push($output,["classroom"=>$classroom,"yearlevelID"=>$yearlevelID,"yearlevel"=>$yearlevel, "schedule"=>$classroomSchedule]);
            
                $this->template = [
                    "monday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "tuesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "wednesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "thursday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "friday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "saturday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                ];
            }

            return response()->json($output);
        }   
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    public function deanLastSchedule($tokens){
        try{
            $department = $this->tokenDepartment($tokens);
            $last = Schedule::join('professors','scheduleProfessor','=','professorID')
            ->join('ranks','professorRank','=','rankID')
            ->join('sections','scheduleSection','=','sectionID')
            ->join('classrooms', 'scheduleClassroom','=','classroomID')
            ->join('subjects', 'scheduleSubject','=','subjectCode')
            ->orderBy('scheduleID', 'desc')
            ->select( "professorFullname as fullname","rankName as rank", "scheduleSemester as semester","sectionName as section","classroomName as classroom","subjectName as subject","scheduleDay as day","scheduleStart as start","scheduleEnd as end")
            ->where(["professorDepartment"=>$department, "scheduleSoftDelete"=>0])->first();
            
            $output = [];
            $output['fullname'] = $last['fullname'];
            $output['rank'] = $last['rank'];
            $output['semester'] = $last['semester'];
            $output['section'] = $last['section'];
            $output['classroom'] = $last['classroom'];
            $output['subject'] = $last['subject'];
            $output['day'] = $last['day'];
            $output['time'] = $this->convertTime($last['start']).' - '.$this->convertTime($last['end']);
            return response()->json($output);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    public function deanLastScheduleDepartment($tokens){
        try{
            $department = $this->tokenDepartment($tokens);
            $last = MinorSchedule::join('professors','minorProfessor','=','professorID')
            ->join('ranks','professorRank','=','rankID')
            ->join('sections','minorSection','=','sectionID')
            ->join('classrooms', 'minorClassroom','=','classroomID')
            ->join('subjects', 'minorSubject','=','subjectCode')
            ->orderBy('minorID', 'desc')
            ->select("professorDepartment as department","professorFullname as fullname","rankName as rank", "minorSemester as semester","sectionName as section","classroomName as classroom","subjectName as subject","minorDay as day","minorStart as start","minorEnd as end")
            ->where(["minorDepartment"=>$department,"minorSoftDelete"=>0])
            ->first();
            
            $output = [];
            $output['department'] = $last['department'];
            $output['fullname'] = $last['fullname'];
            $output['rank'] = $last['rank'];
            $output['semester'] = $last['semester'];
            $output['section'] = $last['section'];
            $output['classroom'] = $last['classroom'];
            $output['subject'] = $last['subject'];
            $output['day'] = $last['day'];
            $output['time'] = $this->convertTime($last['start']).' - '.$this->convertTime($last['end']);
            return response()->json($output);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

}
