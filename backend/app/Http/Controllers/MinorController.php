<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Department;
use App\Models\MinorSchedule;
use App\Models\OfficialTime;
use App\Models\Professor;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Subject;
use App\Models\YearLevel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MinorController extends Controller
{
    private $template = [
        "monday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "tuesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "wednesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "thursday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "friday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "saturday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
    ];

    protected function departments($tokens){
        try{

            $ownerDep = $this->tokenDepartment($tokens);
            $departments =  Department::where(['departmentSoftDelete'=>0])
            ->select("department")
            ->get();

            $newDepartment = array();
            for($i = 0; $i < count($departments); $i++){
                if($ownerDep != $departments[$i]['department']){
                    array_push($newDepartment,$departments[$i]['department']);
                }
            }

            return response()->json($newDepartment);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function createSchedule(Request $request){
        try{
            //validation here
            // return response()->json("demo");
            $validator = Validator::make($request->all(),[
                "department"=>"required",
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
            //check Professor schedule error
            $schedule = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
            $ranks = Professor::where(['professorID'=>$request->input('professor')])
            ->join('ranks','professorRank','=','rankID')
            ->select('professorStatus as status','professorDesignation as designation',"rankHour as hour")
            ->first();
            
            $professorStatus = $ranks['status'];
            $totalHours = $this->hours($request->input('professor'),$request->input('semester'),$request->input('department'));
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

             $minorProfessor = MinorSchedule::where([
                'minorDepartment'=>$request->input('department'),
                'minorProfessor'=>$request->input('professor'),
                'minorDay'=>$request->input('day'),
                'minorSemester'=>$request->input('semester'),
                'minorSoftDelete'=>0
             ])
             ->select('minorDay as day','minorStart as start','minorEnd as end')
             ->get();

             //insert into tempalte minor professor
             for($i = 0; $i < count($minorProfessor);$i++){
                for($x =  $minorProfessor[$i]->start;$x <= $minorProfessor[$i]->end; $x++ ){
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
            $classroomSchedule  = MinorSchedule::where([
                'minorClassroom'=>$request->input('classroom'),
                'minorDay'=>$request->input('day'),
                'minorSemester'=>$request->input('semester'),
                'minorSoftDelete'=>0
            ])
            ->select('minorDay as day','minorStart as start', 'minorEnd as end')
            ->get();
            //insert into tempalte [scheudle
            for($i = 0; $i < count($classroomSchedule);$i++){
                for($x =  $classroomSchedule[$i]->start;$x <= $classroomSchedule[$i]->end; $x++ ){
                    $schedule[$x] = 1;
                }
            }

            $minorClassroom = MinorSchedule::where([
                'minorDepartment'=>$request->input('department'),
                'minorClassroom'=>$request->input('classroom'),
                'minorDay'=>$request->input('day'),
                'minorSemester'=>$request->input('semester'),
                'minorSoftDelete'=>0
             ])
             ->select('minorDay as day','minorStart as start','minorEnd as end')
             ->get();

             //insert into tempalte minor professor
             for($i = 0; $i < count($minorClassroom);$i++){
                for($x =  $minorClassroom[$i]->start;$x <= $minorClassroom[$i]->end; $x++ ){
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

            $minorSection  = MinorSchedule::where([
                'minorDepartment'=>$request->input('department'),
                'minorSection'=>$request->input('section'),
                'minorDay'=>$request->input('day'),
                'minorSemester'=>$request->input('semester'),
                'minorSoftDelete'=>0
            ])
            ->select('minorDay as day','minorStart as start', 'minorEnd as end')
            ->get();
            //insert into tempalte [scheudle
            for($i = 0; $i < count($minorSection);$i++){
                for($x =  $minorSection[$i]->start;$x <= $minorSection[$i]->end; $x++ ){
                    $schedule[$x] = 1;
                }
            }

            $checkSection = $this->conflictCheckers($request->input('start'),$request->input('end'),$schedule,"Section");
            if(!$checkSection['status']){
                return response()->json(["status"=>false, "msg"=>$checkSection['msg']]);
            }
            $schedule = $checkSection['msg'];

            // $designationHour = $ranks['hour'];
            // if($ranks["designation"] != "none"){
            //     $designationHour = 10;
            // }

            // if($designationHour > $)

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

            $save = new MinorSchedule();
            $save->minorDay = $request->input('day');
            $save->minorStart = $request->input('start');
            $save->minorEnd = $request->input('end');
            $save->minorStatus = $timeStatus;
            $save->minorProfessor = $request->input('professor');
            $save->minorSubject = $request->input('subject');
            $save->minorClassroom = $request->input('classroom');
            $save->minorSection = $request->input('section');
            $save->minorSemester = $request->input('semester');
            $save->minorDepartment = $request->input('department');
            $save->minorSoftDelete = false;
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

    protected function allClassroom(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "department"=>"required",
                "semester"=>"required|in:1st semester,2nd semester",
                "tokens"=>"required",
            ]);

            if($validator->fails()){
                Log::error($validator->errors());
            }

            $ownDepartment = $this->tokenDepartment($request->input('tokens'));

            if($ownDepartment == $request->input('department')){
                return response()->json(['status'=>false, "msg"=>"Please do not select your own department."]);
            }

            $classroomCollection = Classroom::join("year_levels",'classroomYearlevel','=','yearlevelID')
            ->where(['classroomDepartment'=>$request->input('department'), 'classroomType'=>"lecture" ,"classroomSoftDelete"=>0])
            ->select('classroomID as id', "classroomName as room", "classroomType as type", "yearlevel")
            ->get();

            $output = [];
            for($z = 0; $z < count($classroomCollection); $z++){
                $classroomID = $classroomCollection[$z]['id'];
                $classroomName = $classroomCollection[$z]['room'];
                $classroomType = $classroomCollection[$z]['type'];
                $classroomYearlevel = $classroomCollection[$z]['yearlevel'];


                $fetch = Schedule::join('professors','scheduleProfessor','=','professorID')
                    ->join('ranks','professorRank','=','rankID')
                    ->join('classrooms','scheduleClassroom', '=', 'classroomID')
                    ->join('sections', 'scheduleSection', '=','sectionID')
                    ->join('departments', 'professorDepartment','=', 'department')
                    ->where([ "scheduleClassroom"=>$classroomID, 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
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
                for($i = 0; $i < count($otherDepartment);$i++){
                    $day = $otherDepartment[$i]->day;
                    for($x =  $otherDepartment[$i]->start;$x <= $otherDepartment[$i]->end; $x++ ){
                        $this->template[$day][$x] = array("id"=>$otherDepartment[$i]['id'], "professor"=>$otherDepartment[$i]['fullname'] , "section"=>$otherDepartment[$i]['section'] , "subject"=>$otherDepartment[$i]['subject']  ,  "room"=>$otherDepartment[$i]['room'] );
                    }
                }

                $sched = [];
                for($i = 0; $i < 31; $i++){
                    $time = $this->times[$i];
                    $monday = $this->template["monday"][$i];
                    $tuesday = $this->template["tuesday"][$i];
                    $wednesday = $this->template["wednesday"][$i];
                    $thursday = $this->template["thursday"][$i];
                    $friday = $this->template["friday"][$i];
                    $saturday = $this->template["saturday"][$i];
                    $sched[$i] = ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday];
                }
                
                array_push($output, [
                    "classroom"=>$classroomName,
                    "yearlevel"=>$classroomYearlevel,
                    "type"=> $classroomType,
                    "department"=>$request->input('department'),
                    "schedule"=>$sched
                ]);
                //reset array data
                $sched = [];
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

    protected function allSection(Request $request){
        try{
            $validate = Validator::make($request->all(),[
                "department"=>"required",
                "semester"=>"required|in:1st semester,2nd semester",
                "tokens"=>"required",
            ]);

            // if($ownDepartment == $request->input('department')){
            //     return response()->json(['status'=>false, "msg"=>"Please do not select your own department."]);
            // }

            $sectionCollection = Section::join("year_levels",'sectionYearlevel','=','yearlevelID')
            ->where(['sectionDepartment'=>$request->input('department') ,"sectionSoftDelete"=>0])
            ->select('sectionID as id', "sectionName as section", "yearlevel")
            ->get();

            $output = [];
            for($z = 0; $z < count($sectionCollection); $z++){
                $sectionID = $sectionCollection[$z]['id'];
                $sectionName = $sectionCollection[$z]['section'];
                $sectionYear = $sectionCollection[$z]['yearlevel'];


                $fetch = Schedule::join('professors','scheduleProfessor','=','professorID')
                    ->join('ranks','professorRank','=','rankID')
                    ->join('classrooms','scheduleClassroom', '=', 'classroomID')
                    ->join('sections', 'scheduleSection', '=','sectionID')
                    ->join('departments', 'professorDepartment','=', 'department')
                    ->where([ "scheduleSection"=>$sectionID, 'scheduleSemester'=>$request->input('semester') , "scheduleSoftDelete"=>0 ])
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

                $otherDepartment = MinorSchedule::where(['minorSection'=>$sectionID, 'minorSemester'=>$request->input('semester'),"minorSoftDelete"=>0 ])
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

                $sched = [];
                for($i = 0; $i < 31; $i++){
                    $time = $this->times[$i];
                    $monday = $this->template["monday"][$i];
                    $tuesday = $this->template["tuesday"][$i];
                    $wednesday = $this->template["wednesday"][$i];
                    $thursday = $this->template["thursday"][$i];
                    $friday = $this->template["friday"][$i];
                    $saturday = $this->template["saturday"][$i];
                    $sched[$i] = ["time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday];
                }
                
                array_push($output, [
                    "section"=>$sectionName,
                    "yearlevel"=>$sectionYear,
                    "department"=>$request->input('department'),
                    "schedule"=>$sched
                ]);
                //reset array data
                $sched = [];
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

    protected function showYearlevels($department){
        try{

            $fetch = YearLevel::where(['yearlevelDepartment'=>$department, 'yearlevelSoftDelete'=>0])
            ->select('yearlevelID as id', 'yearlevel')
            ->get();
            return response()->json($fetch);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function showSubjects($tokens){
        try{
            $department = $this->tokenDepartment($tokens);
            $subjects = Subject::join("year_levels","subjectYearlevel","=","yearlevelID")
            ->join("specializations","subjectSpecialization","=","specializationID" )
            ->where(["subjectDepartment"=>$department])
            ->select("subjectCode as code", "subjectName as subject","yearlevel as year", "specializationName as specialization")
            ->get();
            return response()->json($subjects);
        }
        catch(Exception $e){
            log::error($e->getMessage());
        }
    }

    protected function showClassrooms(Request $request){
        try {
            // select("classroomID as id", "classroomName as room", "classroomType as type", "yearlevel", "classroomDepartment as department")
            $ownDepartment = $this->tokenDepartment($request->input('tokens'));
            $department = $request->input('department');

            $classrooms = Classroom::join("year_levels","classroomYearlevel","=","yearlevelID")
            ->where(['classroomDepartment'=>$department, "classroomType"=>"lecture","classroomSoftDelete"=>0])
            ->select("classroomID as id", "classroomName as room", "classroomType as type", "yearlevel as year", "classroomDepartment as department")
            ->get();

            $classroomOwners = Classroom::join("year_levels","classroomYearlevel","=","yearlevelID")
            ->where(['classroomDepartment'=>$ownDepartment,"classroomSoftDelete"=>0])
            ->select("classroomID as id", "classroomName as room", "classroomType as type", "yearlevel as year", "classroomDepartment as department")
            ->get();

            $output = array();

            foreach($classrooms as $classroom){
                array_push($output,$classroom);
            }

            foreach($classroomOwners as $classroom){
                array_push($output,$classroom);
            }

            return response()->json($output);

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    protected function showSections($department){
        try{
            //department ng selected dep. di ng naka login
            $section = Section::join("year_levels","sectionYearlevel","=","yearlevelID")
            ->join("specializations","sectionSpecialization","=","specializationID")
            ->where(['sectionDepartment'=>$department, "sectionSoftDelete"=>0])
            ->select("sectionID as id","sectionName as section","yearlevel as year","specializationName as specialization")
            ->get();

            return response()->json($section);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function showDelete(Request $request){
        try{
            $minor = MinorSchedule::where(['minorProfessor'=>$request->input('professor'), "minorSemester"=>$request->input('semester') ,'minorSoftDelete'=>0])
            ->select("minorID as id", "minorDay as day", "minorStart as start", "minorEnd as end")
            ->get();
            $output = array();

            foreach($minor as $data){
                $time = $this->convertTime($data['start']).' - '.$this->convertTime($data['end']);
                array_push($output , ["id"=>$data['id'],"day"=>$data['day'],"time"=>$time]);
            }
        
            return response()->json($output);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($id){
        try{
            MinorSchedule::where(['minorID'=>$id])->update(['minorSoftDelete'=>1]);
            return response()->json(["status"=>true,"msg"=>"Successuly Deleted."]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function submitSchedule(){
        try{
            MinorSchedule::where(['minorReview'=>0, 'minorSoftDelete'=>0])->update(['minorReview'=>1]);
            return response()->json(["status"=>true,"msg"=>"Successfuly submitted."]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
