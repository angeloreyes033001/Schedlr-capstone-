<?php

namespace App\Http\Controllers;

use App\Models\MinorSchedule;
use App\Models\OfficialTime;
use App\Models\Professor;
use App\Models\Schedule;
use App\Models\Designation;
use App\Models\Load;
use App\Models\Subject;
use App\Models\YearLevel;
use App\Models\Section;
use App\Models\Classroom;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendNotification;
use App\Mail\MinorToDean;

class ScheduleController extends Controller
{
    protected $template = [
        "monday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "tuesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "wednesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "thursday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "friday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "saturday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
    ];

    protected function professorSchedule(Request $request){
        try{
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
            return response()->json($professorSchedule);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function classroomSchedule(Request $request){
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

    protected function sectionSchedule(Request $request){
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

        return response()->json($sectionSchedule);
    }

    protected function createSchedule(Request $request){
        
        try{

            $validator = Validator::make($request->all(),[
                "tokens"=>"required",
                "professor"=>"required",
                "semester"=>"required|in:1st semester,2nd semester",
                "subject"=>"required",
                "classroom"=>"required|numeric",
                "section"=>"required|numeric",
                "day"=>"required|in:monday,tuesday,wednesday,thursday,friday,saturday",
                "start"=>"required|numeric",
                "end"=>"required|numeric",
            ]);

            if($validator->fails()){

                if($validator->errors()->has('professor')){
                    $msgError = $validator->errors()->first('professor');
                    return response()->json(['status'=>false, "error"=>"professor", "msg"=>$msgError]);
                }

                if($validator->errors()->has('semester')){
                    $msgError = $validator->errors()->first('semester');
                    return response()->json(['status'=>false, "error"=>"semester", "msg"=>$msgError]);
                }

                if($validator->errors()->has('subject')){
                    $msgError = $validator->errors()->first('subject');
                    return response()->json(['status'=>false, "error"=>"subject", "msg"=>$msgError]);
                }

                if($validator->errors()->has('classroom')){
                    $msgError = $validator->errors()->first('classroom');
                    return response()->json(['status'=>false, "error"=>"classroom", "msg"=>$msgError]);
                }

                if($validator->errors()->has('section')){
                    $msgError = $validator->errors()->first('section');
                    return response()->json(['status'=>false, "error"=>"section", "msg"=>$msgError]);
                }

                if($validator->errors()->has('day')){
                    $msgError = $validator->errors()->first('day');
                    return response()->json(['status'=>false, "error"=>"day", "msg"=>$msgError]);
                }

                if($validator->errors()->has('start')){
                    $msgError = $validator->errors()->first('start');
                    return response()->json(['status'=>false, "error"=>"start", "msg"=>$msgError]);
                }

                if($validator->errors()->has('end')){
                    $msgError = $validator->errors()->first('end');
                    return response()->json(['status'=>false, "error"=>"end", "msg"=>$msgError]);
                }
            }

            $departments = $this->tokenDepartment($request->input('tokens'));

            $schedule = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
            $ranks = Professor::where(['professorID'=>$request->input('professor')])
            ->join('ranks','professorRank','=','rankID')
            ->select('professorStatus as status','professorDesignation as designation',"rankHour as hour")
            ->first();
            
            $professorStatus = $ranks['status'];
            $totalHours = $this->totalWorkHour($request->input('professor'),$request->input('semester'), $departments);
            $timeStatus = ($totalHours['hour'] <= 40) ? "regular":"overtime";

            $officialSchedule = OfficialTime::where([
                'officialtimeProfessor'=>$request->input('professor'),
                'officialtimeDay'=>$request->input('day'),
                'officialtimeSemester'=>$request->input('semester'),
                'officialtimeSoftDelete'=>0
             ])
             ->select('officialtimeDay as day','officialtimeStart as start','officialtimeEnd as end')
             ->get();
 
             //insert into tempalte [scheudle (official time)
             for($i = 0; $i < count($officialSchedule);$i++){
                for($x =  $officialSchedule[$i]->start;$x <= $officialSchedule[$i]->end; $x++ ){
                    $schedule[$x] = 1;
                }
             }

            //professor Schedule
            $professorSchedule = Schedule::where([
                'scheduleProfessor'=>$request->input('professor'),
                'scheduleDay'=>$request->input('day'),
                'scheduleSemester'=>$request->input('semester'),
                'scheduleSoftDelete'=>0
            ])
            ->select('scheduleDay as day','scheduleStart as start', 'scheduleEnd as end')
            ->get();

            //insert into tempalte [scheudle(professor schedule)
            for($i = 0; $i < count($professorSchedule);$i++){
                for($x =  $professorSchedule[$i]->start;$x <= $professorSchedule[$i]->end; $x++ ){
                    $schedule[$x] = 1;
                    // $totalHours += 30;
                }
            }
            
            $checkProfessor = $this->conflictCheckers($request->input('start'),$request->input('end'),$schedule,"Professor");
            if(!$checkProfessor['status']){
                return response()->json(["status"=>false, "msg"=>$checkProfessor['msg']]);
            }
            $schedule = $checkProfessor['msg'];
            
            //classroom schedule
            $classroomSchedule  = Schedule::where([
                'scheduleClassroom'=>$request->input('classroom'),
                'scheduleDay'=>$request->input('day'),
                'scheduleSemester'=>$request->input('semester'),
                'scheduleSoftDelete'=>0
            ])
            ->select('scheduleDay as day','scheduleStart as start', 'scheduleEnd as end')
            ->get();
            //insert into tempalte [scheudle
            for($i = 0; $i < count($classroomSchedule);$i++){
                for($x =  $classroomSchedule[$i]->start;$x <= $classroomSchedule[$i]->end; $x++ ){
                    $schedule[$x] = 1;
                }
            }
            
            $checkClassroom = $this->conflictCheckers($request->input('start'),$request->input('end'),$schedule,"Classroom");
            if(!$checkClassroom['status']){
                return response()->json(["status"=>false, "msg"=>$checkClassroom['msg']]);
            }
            $schedule = $checkClassroom['msg'];

            //section schedule
            $sectionSchedule  = Schedule::where([
                'scheduleSection'=>$request->input('section'),
                'scheduleDay'=>$request->input('day'),
                'scheduleSemester'=>$request->input('semester'),
                'scheduleSoftDelete'=>0
            ])
            ->select('scheduleDay as day','scheduleStart as start', 'scheduleEnd as end')
            ->get();
            //insert into tempalte [scheudle
            for($i = 0; $i < count($sectionSchedule);$i++){
                for($x =  $sectionSchedule[$i]->start;$x <= $sectionSchedule[$i]->end; $x++ ){
                    $schedule[$x] = 1;
                }
            }
            
            $sectionSchedule = $this->conflictCheckers($request->input('start'),$request->input('end'),$schedule,"Section");
            if(!$sectionSchedule['status']){
                return response()->json(["status"=>false, "msg"=>$sectionSchedule['msg']]);
            }
            $schedule = $sectionSchedule['msg'];

            $designationHour = 21;
            if($ranks["designation"] != "none"){
                $designationHour = 10;
            }

            if($timeStatus == "regular" && $professorStatus == "regular" ){
                if($request->input('start') >= 0 && $request->input('end') <= 20){

                }
                else{
                    return response()->json(["status"=>false, "msg"=>"Regular, Please select time between 7:00 AM - 5:00 PM"]);
                }
            }
            else  if($timeStatus == "overtime" && $professorStatus == "regular" ){
                return response()->json(["status"=>false,"msg"=>"OT, Please select time between 5:30 PM - 10:00 PM"]);
            }
            
            if($timeStatus == "overtime" || $professorStatus=="part time" ){
                if($request->input('start') >= 21 && $request->input('end') >= 22){

                }
                else{
                    return response()->json(["status"=>false,"msg"=>"{$timeStatus}, Please select time between 5:30 PM - 10:00 PM"]);
                }
            }

            $save = new Schedule();
            $save->scheduleDay = $request->input('day');
            $save->scheduleStart = $request->input('start');
            $save->scheduleEnd = $request->input('end');
            // $save->scheduleReview = 0;
            $save->scheduleStatus = $timeStatus;
            $save->scheduleProfessor = $request->input('professor');
            $save->scheduleSubject = $request->input('subject');
            $save->scheduleClassroom = $request->input('classroom');
            $save->scheduleSection = $request->input('section');
            $save->scheduleSemester = $request->input('semester');
            $save->scheduleSoftDelete = false;
            $result = $save->save();

            if(!$result){
                Log::error("Failed to create schedule manually");
            }

            return response()->json(["status"=>true,"msg"=>"Successfully Created"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function showDelete(Request $request){
        try{
            $show = Schedule::
            join('sections','scheduleSection','=','sectionID')
            ->join('classrooms','scheduleClassroom','=','classroomID')
            ->where(['scheduleProfessor'=>$request->input('professor'), "scheduleSemester"=>$request->input('semester') ,'scheduleSoftDelete'=>0])
            ->select("scheduleID as id", "scheduleDay as day", "scheduleStart as start", "scheduleEnd as end","sectionName as section","classroomName as classroom")
            ->get();
            $output = array();

            foreach($show as $data){
                $time = $this->convertTime($data['start']).' - '.$this->convertTime($data['end']);
                array_push($output , ["id"=>$data['id'],"day"=>$data['day'],"time"=>$time, 'section'=>$data['section'],'classroom'=>$data['classroom']]);
            }
        
            return response()->json($output);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($id){
        try{
            Schedule::where(['scheduleID'=>$id])->update(['scheduleSoftDelete'=>1]);
            return response()->json(["status"=>true,"msg"=>"Successuly Deleted."]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function professorLoad(Request $request){
        try{

            $load = Load::join('subjects','loadSubject','=','subjectCode')
            ->where(["loadProfessor"=>$request->input('professor'), "subjectSemester"=>$request->input('semester') ,"loadSoftDelete"=>0])
            ->select("loadSubject as code", "subjectYearlevel as yearlevel")
            ->get();

            return response()->json($load);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function myTotalHours(Request $request){
        $totalHours = $this->totalWorkHour($request->input('professor'),$request->input('semester'));
        $data = Professor::where(["professorID"=>$request->input('professor')])
        ->join('ranks','professorRank','=','rankID')
        ->select('professorRank as rank', 'rankHour as hour' ,"professorDesignation as designation")
        ->first();

        if($data['designation'] != "none"){
            $designation = Designation::where(['designationPosition'=>$data['designation']])
            ->select('designationHour as hour')
            ->first();
            return response()->json(["designation"=>$data['designation'], "hour"=>$designation['hour'],"myHour"=>$totalHours['hour'],"myTextHour"=>$totalHours['text']]);
        }

        return response()->json(["designation"=>$data['designation'], "hour"=>$data['hour'],"myHour"=>$totalHours['hour'],"myTextHour"=>$totalHours['text']]);
        
    }

    protected function totalWorkHour($professor,$semester){
        try{
            $hours = 0;
            $schedule = Schedule::where([
                'scheduleProfessor'=>$professor,
                'scheduleSemester'=>$semester,
            ])
            ->select('scheduleDay as day','scheduleStart as start', 'scheduleEnd as end')
            ->get();
    
            $day = null;
            for($i = 0; $i < count($schedule);$i++){
                $day = $schedule[$i]->day;
                for($x =  $schedule[$i]->start;$x <= $schedule[$i]->end; $x++ ){
                    $this->template[$day][$x] = 1;
                    $hours += 30;
                }
            }

            $time = $hours/60;
            if(is_int($time)){
                return ["hour"=>$time , "text"=>$time.":00"];
            }
            else{
                return ["hour"=>$time , "text"=>(int)$time.":30"];
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function automated(Request $request){
        try{
            
            $department = $this->tokenDepartment($request->input('tokens'));
            $semesters = ["1st semester","2nd Semester"]; //,"2nd Semester"

            Schedule::join("professors",'scheduleProfessor','=','professorID')
            ->where(['professorDepartment'=>$department])
            ->update(["scheduleSoftDelete"=>1]);

            Schedule::join("professors",'scheduleProfessor','=','professorID')
            ->where(['professorDepartment'=>$department])
            ->delete();


            $first_output = array();

            foreach($semesters as $semester){
                $yearlevels = Yearlevel::where(['yearlevelDepartment'=>$department,'yearlevelSoftDelete'=>0])
                ->select("yearlevelID as id","yearlevel as year")
                ->get();

                $arr_load = array();
                foreach($yearlevels as $year){

                    $subjectQuery = Subject::join("specializations","subjectSpecialization","=","specializationID")
                    ->where(['subjectYearlevel'=>$year['id'],"subjectSemester"=>$semester,"subjectSoftDelete"=>0])
                    ->select("subjectCode as code","subjectYearlevel as yearlevel","specializationID","specializationName as specialization")
                    ->get();

                    if(count($subjectQuery) > 0){
                        $arr_subject = array();
                        foreach($subjectQuery as $subject){

                            $sectionQuery = Section::join("specializations","sectionSpecialization","=","specializationID")
                            ->select("sectionID as id","sectionName as section","specializationName as specialization")
                            ->where(["sectionSoftDelete"=>0,"sectionYearlevel"=>$year['id']])
                            ->get();

                            // $arr_section = array();
                            if(count($sectionQuery) > 0){
                                foreach($sectionQuery as $section){
                                    if($subject['specialization'] == $section['specialization']){
                                        array_push($arr_subject,["code"=>$subject['code'],"subjectSpecialization"=>$subject['specialization'],"section"=>$section]);
                                    }
                                    else{
                                        if($subject['specialization'] == "common"){
                                            array_push($arr_subject,["code"=>$subject['code'],"subjectSpecialization"=>$subject['specialization'],"section"=>$section]);
                                        }
                                    }
                                }
                            }
                            else{
                                return response()->json(["status"=>false, "msg"=>"Please provide atleast one section."]);
                            }
                        }
                        array_push($arr_load,["yearlevel"=>$year['id'],"subject"=>$arr_subject]);
                    }
                    else{
                        return response()->json(["status"=>false, "msg"=>"Please provide atleast one subject."]);
                    }
                }
                array_push($first_output,["semester"=>$semester,"loads"=>$arr_load]);
            }
            // $second_output = array();

            // foreach($first_output as $outer){

            //     foreach($outer['loads'] as $load){
            //         $yearlevel = $load['yearlevel'];
            //         $subject = $load['subject'];

            //         $groupSubject = array();
            //         foreach($subject as $sub){
            //             $code = $sub['code'];
            //             $subjectSpecialization = $sub['subjectSpecialization'];
            //             $sectionID = $sub['section']['id'];
            //             $section = $sub['section']['section'];
            //             $sectionSpecialization = $sub['section']['specialization'];
            //             array_push($groupSubject, ["year"=>$yearlevel,"sectionID"=>$sectionID,"section"=>$section,"code"=>$code,"subjectSpecialization"=>$subjectSpecialization,"sectionSpecialization"=>$sectionSpecialization]);
            //         }
                    
            //         array_push($second_output,["semester"=>$outer['semester'],"subject"=>$groupSubject]);
            //     }
            // }
            // $third_output = array();

            // foreach($semesters as $key=>$semester){
            //     $current_semester = $second_output[$key]["semester"];
                
            //     if($current_semester === $semester){
            //         $loads = $second_output[$key]["subject"];
            //         foreach($loads as $load){
            //             $professorHaveLoadQuery = Load::join('professors','loadProfessor','=','professorID')
            //             ->join('ranks','professorRank','=','rankID')
            //             ->where(['loadSubject'=>$load['code'],"loadSoftDelete"=>0])
            //             ->select('loadProfessor as name',"rankName as rank","rankHour as hour","ProfessorDesignation as designated")
            //             ->get();

            //             $professorsLoads = array();
            //             foreach($professorHaveLoadQuery as $query ){
            //                 array_push($professorsLoads,$query);
            //             }

            //             array_push($third_output,[
            //                 "semester"=>$current_semester,
            //                 "year"=>$load['year'],
            //                 "subject"=>$load['code'],
            //                 "subjectSpecialization"=>$load['subjectSpecialization'],
            //                 "sectionID"=>$load['sectionID'],
            //                 "section"=>$load['section'],
            //                 "sectionSpecialization"=>$load['sectionSpecialization'],
            //                 "professor"=>$professorsLoads]);
            //         }
            //     }
            // }

            // $final_output = array();

            // foreach($third_output as $finalLoad){
            //     try{

            //         $checkExistingSubject = Schedule::where(["scheduleSubject"=>$finalLoad['subject'],"scheduleSoftDelete"=>0])
            //         ->count();

            //         $created_schedule_array = array();

            //         if($checkExistingSubject == 0){

            //             $allowedDays = ["monday","tuesday","wednesday","thursday","friday"];
            //             $scheduleX = [
            //                 "monday"=>[
            //                     "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                     "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                     "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                 ],
            //                 "tuesday"=>[
            //                     "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                     "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                     "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                 ],
            //                 "wednesday"=>[
            //                     "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                     "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                     "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                 ],
            //                 "thursday"=>[
            //                     "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                     "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                     "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                 ],
            //                 "friday"=>[
            //                     "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                     "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                     "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                 ],
            //             ];
 
            //             foreach($finalLoad['professor'] as $professor ){
            //                 //align professor schedule
            //                 $professorScheduleQuery = Schedule::where(['scheduleProfessor'=>$professor['name'],'scheduleSemester'=>$finalLoad['semester'],"scheduleSoftDelete"=>0])
            //                 ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
            //                 ->get();
                            
            //                 foreach($professorScheduleQuery as $schedule){
            //                     $day = $schedule['day'];
            //                     for($i = $schedule['start'];$i <= $schedule['end'];$i++){
            //                         $scheduleX[$day]['professor'][$i] = 1;
            //                     }
            //                 }

            //                 $totalHours = $this->hours($professor['name'],$finalLoad['semester'],$department);
            //                 $designatedTime = 0;

            //                 $designatedQuery = Designation::where(['designationPosition'=>$professor['designated'],"designationSoftDelete"=>0])
            //                     ->select('designationHour as hour')
            //                     ->first();

            //                 if ($designatedQuery) {
            //                     $designatedTime = $designatedQuery->hour;
            //                 } else {
            //                     $designatedTime = $professor['hour'];
            //                 }

            //                 if($totalHours['hour'] <= $designatedTime){
                                

            //                     // insert lecture

            //                     $classroomQuery = Classroom::where(['classroomYearlevel'=>$finalLoad['year'],"classroomType"=>"lecture",'classroomDepartment'=>$department,"classroomSoftDelete"=>0])
            //                     ->select('classroomID as id','classroomType as type')
            //                     ->get();

            //                     $getLectureRoom = 0;
            //                     $counterLecture = 0;
            //                     $counterLectureStart = 0;
            //                     $counterLectureEnd = 0;
            //                     $counterLectureStatus = false;
            //                     $counterLectureDay = "";

            //                     $groupClassroom = array();

            //                     foreach($classroomQuery as $classroom){

            //                         // professor schedule
            //                         $professorScheduleQuery = Schedule::where(['scheduleProfessor'=>$professor['name'],'scheduleSemester'=>$finalLoad['semester'],"scheduleSoftDelete"=>0])
            //                         ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
            //                         ->get();
                                    
            //                         foreach($professorScheduleQuery as $schedule){
            //                             $day = $schedule['day'];
            //                             for($i = $schedule['start'];$i <= $schedule['end'];$i++){
            //                                 $scheduleX[$day]['professor'][$i] = 1;
            //                             }
            //                         }

            //                         // proessor officalTime
            //                         $professorOfficialTimeQuery = OfficialTime::where(['officialtimeProfessor'=>$professor['name'],'officialtimeSemester'=>$finalLoad['semester'], "officialtimeSoftDelete"=>0])
            //                         ->select("officialtimeDay as day","officialtimeStart as start","officialtimeEnd as end")
            //                         ->get();
        
            //                         foreach($professorOfficialTimeQuery as $schedule){
            //                             $day = $schedule['day'];
            //                             for($i = $schedule['start'];$i <= $schedule['end'];$i++){
            //                                 $scheduleX[$day]['professor'][$i] = 1;
            //                             }
            //                         }

            //                         $sectionScheduleQuery = Schedule::where(['scheduleSection'=>$finalLoad['sectionID'],'scheduleSemester'=>$finalLoad['semester'],"scheduleSoftDelete"=>0])
            //                         ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
            //                         ->get();
                                
            //                         foreach($sectionScheduleQuery as $schedule){
            //                             $day = $schedule['day'];
            //                             for($i = $schedule['start'];$i <= $schedule['end'];$i++){
            //                                 $scheduleX[$day]['section'][$i] = 1;
            //                             }
            //                         }

            //                         //classroomSchedule
            //                         $classroomScheduleQuery =  Schedule::where(['scheduleClassroom'=>$classroom['id'],'scheduleSemester'=>$finalLoad['semester'],"scheduleSoftDelete"=>0])
            //                         ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
            //                         ->get();
                                    
            //                         if(!$counterLectureStatus){

            //                             foreach($classroomScheduleQuery as $schedule){
            //                                 $day = $schedule['day'];
            //                                 for($i = $schedule['start'];$i <= $schedule['end'];$i++){
            //                                     $scheduleX[$day]['classroom'][$i] = 1;
            //                                 }
            //                             }

            //                             if($classroom['type'] == "lecture"){
            //                                 $classHours = 5;///5
            //                                 foreach($allowedDays as $day){
            //                                     if(!$counterLectureStatus){
            //                                         for($i =0;$i < 31;$i++){
    
            //                                             if($counterLecture != $classHours && $i == 10 ||$counterLecture != $classHours && $i == 11 ){
            //                                                 $counterLectureStart = 0;
            //                                                 $counterLecture = 0;
            //                                             }else{
            //                                                 if($counterLecture != $classHours ){
            //                                                     if($i <= 20){
            //                                                         if(
            //                                                             ($scheduleX[$day]['professor'][$i] == 0 && 
            //                                                             $scheduleX[$day]['section'][$i] == 0 &&
            //                                                             $scheduleX[$day]['classroom'][$i] == 0)||
            //                                                             ($scheduleX[$day]['professor'][$i+1] == 0 && 
            //                                                             $scheduleX[$day]['section'][$i+1] == 0 &&
            //                                                             $scheduleX[$day]['classroom'][$i+1] == 0)
            //                                                         ){
            //                                                             if($counterLecture == 0){
            //                                                                 $counterLectureStart += $i;
            //                                                                 $counterLecture +=1;
            //                                                             }
            //                                                             else{
            //                                                                 $counterLecture +=1;
            //                                                             }
            //                                                             //Dont forgot to remove this one.
            //                                                             $scheduleX[$day]['professor'][$i] = "OK";
            //                                                             $scheduleX[$day]['section'][$i]  = "OK";
            //                                                             $scheduleX[$day]['classroom'][$i]  = "OK";
            //                                                         }
            //                                                         else{
            //                                                             $counterLectureStart = 0;
            //                                                             $counterLecture = 0;
            //                                                         }
            //                                                     }
            //                                                     else{
            //                                                         $counterLectureStart = 0;
            //                                                         $counterLecture = 0;
            //                                                     }
            //                                                 }
            //                                                 else{
            //                                                     $counterLectureEnd = $counterLectureStart + ($classHours - 1);
            //                                                     $counterLectureStatus = true;
            //                                                     $getLectureRoom = $classroom['id'];
            //                                                     $counterLectureDay = $day;
            //                                                 }
            //                                             }
            //                                         }
            //                                     }
                                                
            //                                 }
            //                             }
                                        
            //                             Log::alert([
            //                                 "day"=>$counterLectureDay,
            //                                 "start"=>$counterLectureStart,
            //                                 "end"=>$counterLectureEnd,
            //                                 "status"=>"regular",
            //                                 "professor"=>$professor['name'],
            //                                 "subject"=> $finalLoad['subject'],
            //                                 "clasroom"=>$getLectureRoom,
            //                                 "section"=>$finalLoad['section'],
            //                                 "semester"=>$finalLoad['semester'],
            //                             ]);

            //                             // log::alert($scheduleX);

            //                             $saveSchedule = new Schedule();
            //                             $saveSchedule->scheduleDay = $counterLectureDay;
            //                             $saveSchedule->scheduleStart = $counterLectureStart;
            //                             $saveSchedule->scheduleEnd = $counterLectureEnd;
            //                             $saveSchedule->scheduleStatus = "regular";
            //                             $saveSchedule->scheduleProfessor = $professor['name'];
            //                             $saveSchedule->scheduleSubject = $finalLoad['subject'];
            //                             $saveSchedule->scheduleClassroom = $getLectureRoom;
            //                             $saveSchedule->scheduleSection = $finalLoad['sectionID'];
            //                             $saveSchedule->scheduleSemester = $finalLoad['semester'];
            //                             $saveSchedule->save();

            //                             $scheduleX = [
            //                                 "monday"=>[
            //                                     "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                     "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                     "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                 ],
            //                                 "tuesday"=>[
            //                                     "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                     "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                     "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                 ],
            //                                 "wednesday"=>[
            //                                     "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                     "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                     "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                 ],
            //                                 "thursday"=>[
            //                                     "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                     "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                     "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                 ],
            //                                 "friday"=>[
            //                                     "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                     "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                     "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            //                                 ],
            //                             ];
            //                         }

                                   
            //                         array_push($groupClassroom,);
            //                     }

            //                     array_push($created_schedule_array,$groupClassroom);
            //                 }
            //             }
            //             array_push($final_output,$created_schedule_array);
            //         }
            //     }
            //     catch(Exception $e){
            //         Log::error("ADDING SCHEDULE ERROR: ".$e->getMessage());
            //     }

            // }

            // return response()->json($final_output);



            foreach($first_output as $key=>$data){    
                $semester = $data['semester'];
                $loads = $data['loads'];

                if($semester == $semesters[$key]){
                    foreach($loads as $load){

                        $yearlevel = $load['yearlevel'];
                        $collectionSlot = $load['subject'];
    
                        foreach($collectionSlot as $collection){
                            $professorHaveLoadQuery = Load::join('professors','loadProfessor','=','professorID')
                            ->join('ranks','professorRank','=','rankID')
                            ->where(['loadSubject'=>$collection['code'],"loadSoftDelete"=>0])
                            ->select('loadProfessor as professor',"rankName as rank","rankHour as hour","ProfessorDesignation as designated")
                            ->get();
    
                            $arr_load = array();
                            foreach($professorHaveLoadQuery as $professor){
                                //try here
                                $allowedDays = ["monday","tuesday","wednesday","thursday","friday"];
                                $scheduleX = [
                                    "monday"=>[
                                        "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    ],
                                    "tuesday"=>[
                                        "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    ],
                                    "wednesday"=>[
                                        "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    ],
                                    "thursday"=>[
                                        "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    ],
                                    "friday"=>[
                                        "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    ],
                                ];
    
                                //professor schudule
                                $professorScheduleQuery = Schedule::where(['scheduleProfessor'=>$professor['professor'],'scheduleSemester'=>$semester,"scheduleSoftDelete"=>0])
                                ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
                                ->get();
                                
                                foreach($professorScheduleQuery as $schedule){
                                    $day = $schedule['day'];
                                    for($i = $schedule['start'];$i <= $schedule['end'];$i++){
                                        $scheduleX[$day]['professor'][$i] = 1;
                                    }
                                }
    
                                //professor official time
                                $professorOfficialTimeQuery = OfficialTime::where(['officialtimeProfessor'=>$professor['professor'],'officialtimeSemester'=>$semester, "officialtimeSoftDelete"=>0])
                                ->select("officialtimeDay as day","officialtimeStart as start","officialtimeEnd as end")
                                ->get();
    
                                foreach($professorOfficialTimeQuery as $schedule){
                                    $day = $schedule['day'];
                                    for($i = $schedule['start'];$i <= $schedule['end'];$i++){
                                        $scheduleX[$day]['professor'][$i] = 1;
                                    }
                                }
    
                                $totalHours = $this->hours($professor['professor'],$semester,$department);
                                $designatedTime = 0;
    
                                $designatedQuery = Designation::where(['designationPosition'=>$professor['designated'],"designationSoftDelete"=>0])
                                    ->select('designationHour as hour')
                                    ->first();
    
                                if ($designatedQuery) {
                                    $designatedTime = $designatedQuery->hour;
                                } else {
                                    $designatedTime = $professor['hour'];
                                }
    
                                if($totalHours['hour'] <= $designatedTime){
                                    
                                    $check = Schedule::where(['scheduleSection'=>$collection['section']['id'], "scheduleSubject"=>$collection['code'],'scheduleSemester'=>$semester,"scheduleSoftDelete"=>0])
                                    ->count();

                                    if($check == 0){
                                        $sectionScheduleQuery = Schedule::where(['scheduleSection'=>$collection['section']['id'],'scheduleSemester'=>$semester,"scheduleSoftDelete"=>0])
                                        ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
                                        ->get();
                                    
                                        foreach($sectionScheduleQuery as $schedule){
                                            $day = $schedule['day'];
                                            for($i = $schedule['start'];$i <= $schedule['end'];$i++){
                                                $scheduleX[$day]['section'][$i] = 1;
                                            }
                                        }
        
                                        $subject = Subject::where(['subjectCode'=>$collection['code']])
                                        ->select("subjectLaboratory as laboratory")
                                        ->first();
        
                                        if($subject->laboratory){
                                            
                                            $classroomQuery = Classroom::where(['classroomYearlevel'=>$yearlevel,'classroomDepartment'=>$department,"classroomSoftDelete"=>0])
                                            ->select('classroomID as id','classroomType as type')
                                            ->get();
        
                                            $getLectureRoom = null;
                                             $counterLecture = 0;
                                            $counterLectureStart = 0;
                                            $counterLectureEnd = 0;
                                            $counterLectureStatus = false;
                                            $counterLectureDay = "";
        
                                            foreach($classroomQuery as $classroom){
        
                                                $classroomScheduleQuery =  Schedule::where(['scheduleClassroom'=>$classroom['id'],'scheduleSemester'=>$semester,"scheduleSoftDelete"=>0])
                                                ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
                                                ->get();
                                                
                                                foreach($classroomScheduleQuery as $schedule){
                                                    $day = $schedule['day'];
                                                    for($i = $schedule['start'];$i <= $schedule['end'];$i++){
                                                        $scheduleX[$day]['classroom'][$i] = 1;
                                                    }
                                                }
        
                                                if($classroom['type'] == "lecture"){
                                                    $classHours = 5;///5
                                                    foreach($allowedDays as $day){
                                                        if(!$counterLectureStatus){
                                                            for($i =0;$i < 31;$i++){
            
                                                                if($counterLecture != $classHours && $i == 10 ||$counterLecture != $classHours && $i == 11 ){
                                                                    $counterLectureStart = 0;
                                                                    $counterLecture = 0;
                                                                }else{
                                                                    if($counterLecture != $classHours ){
                                                                        if($i <= 20){
                                                                            if(
                                                                                ($scheduleX[$day]['professor'][$i] == 0 && 
                                                                                $scheduleX[$day]['section'][$i] == 0 &&
                                                                                $scheduleX[$day]['classroom'][$i] == 0)||
                                                                                $scheduleX[$day]['professor'][$i+1] == 0 && 
                                                                                $scheduleX[$day]['section'][$i+1] == 0 &&
                                                                                $scheduleX[$day]['classroom'][$i+1] == 0
                                                                            ){
                                                                                if($counterLecture == 0){
                                                                                    $counterLectureStart += $i;
                                                                                    $counterLecture +=1;
                                                                                }
                                                                                else{
                                                                                    $counterLecture +=1;
                                                                                }
                
        
                                                                                //Dont forgot to remove this one.
                                                                                $scheduleX[$day]['professor'][$i] = "OK";
                                                                                $scheduleX[$day]['section'][$i]  = "OK";
                                                                                $scheduleX[$day]['classroom'][$i]  = "OK";
                                                                            }
                                                                            else{
                                                                                $counterLectureStart = 0;
                                                                                $counterLecture = 0;
                                                                            }
                                                                        }
                                                                        else{
                                                                            $counterLectureStart = 0;
                                                                            $counterLecture = 0;
                                                                        }
                                                                    }
                                                                    else{
                                                                        $counterLectureEnd = $counterLectureStart + ($classHours - 1);
                                                                        $counterLectureStatus = true;
                                                                        $getLectureRoom = $classroom['id'];
                                                                        $counterLectureDay = $day;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        
                                                    }
        
                                                    $saveSchedule = new Schedule();
                                                    $saveSchedule->scheduleDay = $counterLectureDay;
                                                    $saveSchedule->scheduleStart = $counterLectureStart;
                                                    $saveSchedule->scheduleEnd = $counterLectureEnd;
                                                    $saveSchedule->scheduleStatus = "regular";
                                                    $saveSchedule->scheduleProfessor = $professor['professor'];
                                                    $saveSchedule->scheduleSubject = $collection['code'];
                                                    $saveSchedule->scheduleClassroom = $getLectureRoom;
                                                    $saveSchedule->scheduleSection = $collection['section']['id'];
                                                    $saveSchedule->scheduleSemester = $semester;
                                                    $saveSchedule->save(); 
                                                }
                                            }

                                            // -------------- insert laboratory

                                             // get latest schedule again 
                                             $classroomScheduleQuery =  Schedule::where(['scheduleClassroom'=>$classroom['id'],'scheduleSemester'=>$semester,"scheduleSoftDelete"=>0])
                                             ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
                                             ->get();
                                             
                                             foreach($classroomScheduleQuery as $schedule){
                                                 $day = $schedule['day'];
                                                 for($i = $schedule['start'];$i <= $schedule['end'];$i++){
                                                     $scheduleX[$day]['classroom'][$i] = 1;
                                                 }
                                             }

                                             //professor schedule
                                             $professorScheduleQuery = Schedule::where(['scheduleProfessor'=>$professor['professor'],'scheduleSemester'=>$semester,"scheduleSoftDelete"=>0])
                                             ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
                                             ->get();
                                             
                                             foreach($professorScheduleQuery as $schedule){
                                                 $day = $schedule['day'];
                                                 for($i = $schedule['start'];$i <= $schedule['end'];$i++){
                                                     $scheduleX[$day]['professor'][$i] = 1;
                                                 }
                                             }

                                             //professor official time
                                             $professorOfficialTimeQuery = OfficialTime::where(['officialtimeProfessor'=>$professor['professor'],'officialtimeSemester'=>$semester, "officialtimeSoftDelete"=>0])
                                             ->select("officialtimeDay as day","officialtimeStart as start","officialtimeEnd as end")
                                             ->get();

                                             foreach($professorOfficialTimeQuery as $schedule){
                                                 $day = $schedule['day'];
                                                 for($i = $schedule['start'];$i <= $schedule['end'];$i++){
                                                     $scheduleX[$day]['professor'][$i] = 1;
                                                 }
                                             }

                                             //professor schudule
                                             $sectionScheduleQuery = Schedule::where(['scheduleSection'=>$collection['section']['id'],'scheduleSemester'=>$semester,"scheduleSoftDelete"=>0])
                                             ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
                                             ->get();
                                         
                                             foreach($sectionScheduleQuery as $schedule){
                                                 $day = $schedule['day'];
                                                 for($i = $schedule['start'];$i <= $schedule['end'];$i++){
                                                     $scheduleX[$day]['section'][$i] = 1;
                                                 }
                                             }

                                             $classroomQuery = Classroom::where(['classroomYearlevel'=>$yearlevel,'classroomDepartment'=>$department,"classroomSoftDelete"=>0])
                                             ->select('classroomID as id','classroomType as type')
                                             ->get();
         
                                             $getLaboratoryRoom = null;
                                             $counterLaboratory = 0;
                                             $counterLaboratoryStart = 0;
                                             $counterLaboratoryEnd = 0;
                                             $counterLaboratoryStatus = false;
                                             $counterLaboratoryDay = "";

                                             foreach($classroomQuery as $classroom){
     
                                                 $classroomScheduleQuery =  Schedule::where(['scheduleClassroom'=>$classroom['id'],'scheduleSemester'=>$semester,"scheduleSoftDelete"=>0])
                                                 ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
                                                 ->get();
                                                 
                                                 foreach($classroomScheduleQuery as $schedule){
                                                     $day = $schedule['day'];
                                                     for($i = $schedule['start'];$i <= $schedule['end'];$i++){
                                                         $scheduleX[$day]['classroom'][$i] = 1;
                                                     }
                                                 }
         
                                                 if($classroom['type'] == "laboratory"){
                                                     $classHours = 7;///7
                                                     foreach($allowedDays as $day){
                                                         if(!$counterLaboratoryStatus){
                                                             for($i =0;$i < 31;$i++){
             
                                                                 if($counterLaboratory != $classHours && $i == 10 ||$counterLaboratory != $classHours && $i == 11 ){
                                                                     $counterLaboratoryStart = 0;
                                                                     $counterLaboratory = 0;
                                                                 }else{
                                                                     if($counterLaboratory != $classHours ){
                                                                         if($i <= 20){
                                                                             if(
                                                                                 ($scheduleX[$day]['professor'][$i] == 0 && 
                                                                                 $scheduleX[$day]['section'][$i] == 0 &&
                                                                                 $scheduleX[$day]['classroom'][$i] == 0)||
                                                                                 $scheduleX[$day]['professor'][$i+1] == 0 && 
                                                                                 $scheduleX[$day]['section'][$i+1] == 0 &&
                                                                                 $scheduleX[$day]['classroom'][$i+1] == 0
                                                                             ){
                                                                                 if($counterLaboratory == 0){
                                                                                     $counterLaboratoryStart += $i;
                                                                                     $counterLaboratory +=1;
                                                                                 }
                                                                                 else{
                                                                                     $counterLaboratory +=1;
                                                                                 }
                 
         
                                                                                 //Dont forgot to remove this one.
                                                                                 $scheduleX[$day]['professor'][$i] = "OK";
                                                                                 $scheduleX[$day]['section'][$i]  = "OK";
                                                                                 $scheduleX[$day]['classroom'][$i]  = "OK";
                                                                             }
                                                                             else{
                                                                                 $counterLaboratoryStart = 0;
                                                                                 $counterLaboratory = 0;
                                                                             }
                                                                         }
                                                                         else{
                                                                             $counterLaboratoryStart = 0;
                                                                             $counterLaboratory = 0;
                                                                         }
                                                                     }
                                                                     else{
                                                                         $counterLaboratoryEnd = $counterLaboratoryStart + ($classHours - 1);
                                                                         $counterLaboratoryStatus = true;
                                                                         $getLaboratoryRoom = $classroom['id'];
                                                                         $counterLaboratoryDay = $day;
                                                                     }
                                                                 }
                                                             }
                                                         }
                                                         
                                                     }
         
                                                     $saveSchedule = new Schedule();
                                                     $saveSchedule->scheduleDay = $counterLaboratoryDay;
                                                     $saveSchedule->scheduleStart = $counterLaboratoryStart;
                                                     $saveSchedule->scheduleEnd = $counterLaboratoryEnd;
                                                     $saveSchedule->scheduleStatus = "regular";
                                                     $saveSchedule->scheduleProfessor = $professor['professor'];
                                                     $saveSchedule->scheduleSubject = $collection['code'];
                                                     $saveSchedule->scheduleClassroom = $getLaboratoryRoom;
                                                     $saveSchedule->scheduleSection = $collection['section']['id'];
                                                     $saveSchedule->scheduleSemester = $semester;
                                                     $saveSchedule->save(); 
                                                 }
                                             }
                                        }
                                        else{
                                            $classroomQuery = Classroom::where(['classroomYearlevel'=>$yearlevel,'classroomDepartment'=>$department,"classroomSoftDelete"=>0])
                                            ->select('classroomID as id','classroomType as type')
                                            ->get();
        
                                            $getLectureRoom = null;
                                            $counterLecture = 0;
                                            $counterLectureStart = 0;
                                            $counterLectureEnd = 0;
                                            $counterLectureStatus = false;
                                            $counterLectureDay = "";
        
                                            foreach($classroomQuery as $classroom){
        
                                                $classroomScheduleQuery =  Schedule::where(['scheduleClassroom'=>$classroom['id'],'scheduleSemester'=>$semester,"scheduleSoftDelete"=>0])
                                                ->select('scheduleDay as day',"scheduleStart as start","scheduleEnd as end")
                                                ->get();
                                                
                                                foreach($classroomScheduleQuery as $schedule){
                                                    $day = $schedule['day'];
                                                    for($i = $schedule['start'];$i <= $schedule['end'];$i++){
                                                        $scheduleX[$day]['classroom'][$i] = 1;
                                                    }
                                                }
        
                                                if($classroom['type'] == "lecture"){
                                                    $classHours = 5;///5
                                                    foreach($allowedDays as $day){
                                                        if(!$counterLectureStatus){
                                                            for($i =0;$i < 31;$i++){
            
                                                                if($counterLecture != $classHours && $i == 10 ||$counterLecture != $classHours && $i == 11 ){
                                                                    $counterLectureStart = 0;
                                                                    $counterLecture = 0;
                                                                }else{
                                                                    if($counterLecture != $classHours ){
                                                                        if($i <= 20){
                                                                            if(
                                                                                ($scheduleX[$day]['professor'][$i] == 0 && 
                                                                                $scheduleX[$day]['section'][$i] == 0 &&
                                                                                $scheduleX[$day]['classroom'][$i] == 0)||
                                                                                $scheduleX[$day]['professor'][$i+1] == 0 && 
                                                                                $scheduleX[$day]['section'][$i+1] == 0 &&
                                                                                $scheduleX[$day]['classroom'][$i+1] == 0
                                                                            ){
                                                                                if($counterLecture == 0){
                                                                                    $counterLectureStart += $i;
                                                                                    $counterLecture +=1;
                                                                                }
                                                                                else{
                                                                                    $counterLecture +=1;
                                                                                }
                
        
                                                                                //Dont forgot to remove this one.
                                                                                $scheduleX[$day]['professor'][$i] = "OK";
                                                                                $scheduleX[$day]['section'][$i]  = "OK";
                                                                                $scheduleX[$day]['classroom'][$i]  = "OK";
                                                                            }
                                                                            else{
                                                                                $counterLectureStart = 0;
                                                                                $counterLecture = 0;
                                                                            }
                                                                        }
                                                                        else{
                                                                            $counterLectureStart = 0;
                                                                            $counterLecture = 0;
                                                                        }
                                                                    }
                                                                    else{
                                                                        $counterLectureEnd = $counterLectureStart + ($classHours - 1);
                                                                        $counterLectureStatus = true;
                                                                        $getLectureRoom = $classroom['id'];
                                                                        $counterLectureDay = $day;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        
                                                    }
        
                                                    $saveSchedule = new Schedule();
                                                    $saveSchedule->scheduleDay = $counterLectureDay;
                                                    $saveSchedule->scheduleStart = $counterLectureStart;
                                                    $saveSchedule->scheduleEnd = $counterLectureEnd;
                                                    $saveSchedule->scheduleStatus = "regular";
                                                    $saveSchedule->scheduleProfessor = $professor['professor'];
                                                    $saveSchedule->scheduleSubject = $collection['code'];
                                                    $saveSchedule->scheduleClassroom = $getLectureRoom;
                                                    $saveSchedule->scheduleSection = $collection['section']['id'];
                                                    $saveSchedule->scheduleSemester = $semester;
                                                    $saveSchedule->save(); 
                                                }
                                            }
                                        }
                                    }
    
                                }
                                // array_push($arr_load,["professor"=>$professor['professor'],"sechedule"=>$scheduleX]);
                            }
                            // array_push($actual_output,["code"=>$collection['code'],"section"=>$collection['section']['section'],"loads"=>$arr_load]);
                            // array_push($actual_output, [$collection['code'],$collection['section']['section'],$professorHaveLoadQuery]);
                        }
    
                    }
                }

            }

            return response()->json(["status"=>true,"msg"=>"Successfuly Created!"]);

            //send Notification TO OTHER DEPARTMENT THAT THEY ALLOWED CREATE SCHEDULE

            return response()->json(["status"=>true,"msg"=>"Successfuly Generated."]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function resetSchedule(Request $request){
        try{
            
            $validator = Validator($request->all(),[
                "password"=> 'required|min:8',
                "tokens"=>'required',
            ]);

            if($validator->fails()){
                if($validator->errors()->has('password')){
                    $msgError = $validator->errors()->first('password');
                    return response()->json(['status'=>false, "error"=>"password", "msg"=>$msgError]);
                }
            }

            $salt = 'schedlr';
            $check = User::where(["personal_tokens"=>$salt.$request->input('tokens')])
            ->select('userDepartment as department',"userPassword as password")
            ->first();

            if(Hash::check($request->input('password'),$check->password)){
                // Schedule::join("professors",'scheduleProfessor','=','professorID')
                // ->where(['professorDepartment'=>$check->department,])
                // ->update(["scheduleSoftDelete"=>1]);

                Schedule::join("professors",'scheduleProfessor','=','professorID')
                ->where(['professorDepartment'=>$check->department,])
                ->delete();

                return response()->json(["status"=>true,"msg"=>"Successfully."]);
            }
            else{
                return response()->json(["status"=>false,"error"=>"password", "msg"=>"Password not matched."]);
            }

            
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function notifyDeanOtherDepartment(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'other_dep'=>"required",
                'tokens'=>"required",
            ]);

            if($validator->fails()){

                if($validator->errors()->has('other_dep')){
                    $msgError = $validator->errors()->first('other_dep');
                    Log::error($msgError);
                }

                if($validator->errors()->has('tokens')){
                    $msgError = $validator->errors()->first('tokens');
                    Log::error($msgError);
                }
            }

            $department = $this->tokenDepartment($request->input('tokens'));

            $userOtherDepartment = User::where(['userDepartment'=>$request->input('other_dep'),"userRole"=>"dean"])
            ->select("userEmail as email")
            ->first();

            Mail::to($userOtherDepartment->email)->send(new SendNotification(strtoUpper($department), strtoUpper($request->input('other_dep')) ));
            return response()->json(["status"=>true,"msg"=>"Sent Successfully"]);
            
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function notifyMinorToDean(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'tokens'=>"required",
                'other_dep'=>"required",
            ]);

            if($validator->fails()){

                if($validator->errors()->has('other_dep')){
                    $msgError = $validator->errors()->first('other_dep');
                    Log::error($msgError);
                }

                if($validator->errors()->has('tokens')){
                    $msgError = $validator->errors()->first('tokens');
                    Log::error($msgError);
                }
            }

            $department = $this->tokenDepartment($request->input('tokens'));

            $userOtherDepartment = User::where(['userDepartment'=>$request->input('other_dep'),"userRole"=>"dean"])
            ->select("userEmail as email")
            ->first();

            Mail::to($userOtherDepartment->email)->send(new MinorToDean(strtoUpper($department),strtoUpper($request->input('other_dep'))));
            return response()->json(["status"=>true,"msg"=>"Sent Successfully"]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

}
