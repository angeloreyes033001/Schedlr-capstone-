<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Department;
use App\Models\OfficialTime;
use App\Models\Professor;
use App\Models\Schedule;
use App\Models\sections;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class publicController extends Controller
{

    protected function read_professor($department){
        try{
            $checkDepartment = Department::where(['department'=>$department])->count();

            if($checkDepartment > 0){
                $professors = Professor::where(['professorDepartment'=>$department, "professorSoftDelete"=>0])
                ->select("professorID","professorFullname as fullname")
                ->get();

                return response()->json($professors);
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read_professor_schedule(Request $request){
        $schedules = [
            "monday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "tuesday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "wednesday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "thursday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "friday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "saturday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
        ];

        $professor = $request->input('professor');
        $semester = $request->input('semester');

        $currentYear = now()->format('Y');
        $nextYear = now()->addYear()->format('Y');
        $academicYear = $currentYear. '-'.$nextYear;

        $professorSchedule = Schedule::where(['scheduleProfessor'=>$professor, "scheduleSemester"=>$semester, 'scheduleAcademicYear'=>$academicYear ,'scheduleSoftDelete'=>0,'classroomSoftDelete'=>0])
        ->join("classrooms","scheduleClassroom","=","classroomID")
        ->join('professors','scheduleProfessor','professorID')
        ->select(
            "scheduleDay as day",
            "scheduleStart as start",
            "scheduleEnd as end",
            "scheduleID as id",
            "scheduleSubject as subject",
            "scheduleSection as section",
            "classroomName as classroom",
            "professorFullname as professor",
            )
        ->get();

        if(count($professorSchedule) > 0){
            foreach($professorSchedule as $schedule){
                for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                    $schedules[$schedule['day']]['professor'][$i] = [
                        "id"=>$schedule['id'],
                        "subject"=>$schedule['subject'],
                        "section"=>$schedule['section'],
                        "classroom"=>$schedule['classroom'],
                        "professor"=>$schedule['professor']
                    ];
                }
            }
        }

        $output = array();
        foreach($this->times as $i=>$time){
            array_push($output, [
                "time"=>$time,
                "monday"=>$schedules['monday']['professor'][$i],
                "tuesday"=>$schedules['tuesday']['professor'][$i],
                "wednesday"=>$schedules['wednesday']['professor'][$i],
                "thursday"=>$schedules['thursday']['professor'][$i],
                "friday"=>$schedules['friday']['professor'][$i],
                "saturday"=>$schedules['saturday']['professor'][$i]
            ]);
        }
        return response()->json($output);
    }

    protected function read_classroom($department){
        try{
            $checkDepartment = Department::where(['department'=>$department])->count();

            if($checkDepartment > 0){
                $classroom = Classroom::where(['classroomDepartment'=>$department, "classroomSoftDelete"=>0])
                ->select("classroomID","classroomName as classroom","classroomType as type")
                ->get();

                return response()->json($classroom);
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read_classroom_schedule(Request $request){
        $schedules = [
            "monday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "tuesday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "wednesday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "thursday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "friday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "saturday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
        ];

        $classroom = $request->input('classroom');
        $semester = $request->input('semester');

        $currentYear = now()->format('Y');
        $nextYear = now()->addYear()->format('Y');
        $academicYear = $currentYear. '-'.$nextYear;

        $classroomSchedule = Schedule::where(['scheduleClassroom'=>$classroom, "scheduleSemester"=>$semester, 'scheduleAcademicYear'=>$academicYear ,'scheduleSoftDelete'=>0,'classroomSoftDelete'=>0])
        ->join("classrooms","scheduleClassroom","=","classroomID")
        ->join('professors','scheduleProfessor','professorID')
        ->select(
            "scheduleDay as day",
            "scheduleStart as start",
            "scheduleEnd as end",
            "scheduleID as id",
            "scheduleSubject as subject",
            "scheduleSection as section",
            "classroomName as classroom",
            "professorFullname as professor",
            )
        ->get();

        if(count($classroomSchedule) > 0){
            foreach($classroomSchedule as $schedule){
                for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                    $schedules[$schedule['day']]['professor'][$i] = [
                        "id"=>$schedule['id'],
                        "subject"=>$schedule['subject'],
                        "section"=>$schedule['section'],
                        "classroom"=>$schedule['classroom'],
                        "professor"=>$schedule['professor']
                    ];
                }
            }
        }

        $output = array();
        foreach($this->times as $i=>$time){
            array_push($output, [
                "time"=>$time,
                "monday"=>$schedules['monday']['professor'][$i],
                "tuesday"=>$schedules['tuesday']['professor'][$i],
                "wednesday"=>$schedules['wednesday']['professor'][$i],
                "thursday"=>$schedules['thursday']['professor'][$i],
                "friday"=>$schedules['friday']['professor'][$i],
                "saturday"=>$schedules['saturday']['professor'][$i]
            ]);
        }
        return response()->json($output);
    }

    protected function read_section($department){
        try{
            $checkDepartment = Department::where(['department'=>$department])->count();

            if($checkDepartment > 0){
                $sections = sections::
                join("section_openeds","sectionID",'=',"sectionopenedID")
                ->join("year_levels","sectionYearlevel","=","yearlevelID")
                ->where(['sectionDepartment'=>$department,"sectionSoftDelete"=>0,"sectionopenedSoftDelete"=>0,"yearlevelSoftDelete"=>0])
                ->select(["sectionopenedName as section","yearlevelID", "yearlevel as year", "sectionSemester as semester"])
                ->get();

                return response()->json($sections);
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read_section_schedule(Request $request){
        $schedules = [
            "monday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "tuesday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "wednesday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "thursday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "friday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
            "saturday"=>[
                "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ],
        ];

        $section = $request->input('section');
        $semester = $request->input('semester');

        $currentYear = now()->format('Y');
        $nextYear = now()->addYear()->format('Y');
        $academicYear = $currentYear. '-'.$nextYear;

        $sectionSchedule = Schedule::where(['scheduleSection'=>$section, "scheduleSemester"=>$semester, 'scheduleAcademicYear'=>$academicYear ,'scheduleSoftDelete'=>0,'classroomSoftDelete'=>0])
        ->join("classrooms","scheduleClassroom","=","classroomID")
        ->join('professors','scheduleProfessor','professorID')
        ->select(
            "scheduleDay as day",
            "scheduleStart as start",
            "scheduleEnd as end",
            "scheduleID as id",
            "scheduleSubject as subject",
            "scheduleSection as section",
            "classroomName as classroom",
            "professorFullname as professor",
            )
        ->get();

        if(count($sectionSchedule) > 0){
            foreach($sectionSchedule as $schedule){
                for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                    $schedules[$schedule['day']]['professor'][$i] = [
                        "id"=>$schedule['id'],
                        "subject"=>$schedule['subject'],
                        "section"=>$schedule['section'],
                        "classroom"=>$schedule['classroom'],
                        "professor"=>$schedule['professor']
                    ];
                }
            }
        }

        $output = array();
        foreach($this->times as $i=>$time){
            array_push($output, [
                "time"=>$time,
                "monday"=>$schedules['monday']['professor'][$i],
                "tuesday"=>$schedules['tuesday']['professor'][$i],
                "wednesday"=>$schedules['wednesday']['professor'][$i],
                "thursday"=>$schedules['thursday']['professor'][$i],
                "friday"=>$schedules['friday']['professor'][$i],
                "saturday"=>$schedules['saturday']['professor'][$i]
            ]);
        }
        return response()->json($output);
    }

    protected function read_print(Request $request){
        try{
            $validator  = Validator::make($request->all(),[
                "id"=>"required",
                "semester"=>"required|in:1st semester,2nd semester",
                "identifier"=>"required|in:professor,classroom,section"
            ]);

            if($validator->fails()){
                if($validator->errors()->has('id')){
                    $errorMesage = $validator->errors()->first('id');
                    return response(["status"=>false, "error"=>"id", "msg"=>$errorMesage]);
                }
                if($validator->errors()->has('semester')){
                    $errorMesage = $validator->errors()->first('semester');
                    return response(["status"=>false, "error"=>"semester", "msg"=>$errorMesage]);
                }
                if($validator->errors()->has('identifier')){
                    $errorMesage = $validator->errors()->first('identifier');
                    return response(["status"=>false, "error"=>"identifier", "msg"=>$errorMesage]);
                }
            }

            $schedules = [
                "monday"=>[
                    "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
                ],
                "tuesday"=>[
                    "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
                ],
                "wednesday"=>[
                    "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
                ],
                "thursday"=>[
                    "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
                ],
                "friday"=>[
                    "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
                ],
                "saturday"=>[
                    "professor"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "classroom"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                    "section"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
                ],
            ];

            $currentYear = now()->format('Y');
            $nextYear = now()->addYear()->format('Y');
            $academicYear = $currentYear. '-'.$nextYear;

            $name = "";
            $schedule_output = array();


            switch($request->input('identifier')){
                case 'professor':{

                    $professor = Professor::where(['professorID'=>$request->input('id')])
                    ->select("professorFullname as name")
                    ->first();

                    $name = $professor->name;

                    $professorSchedule = Schedule::where(['scheduleProfessor'=>$request->input('id'), "scheduleSemester"=>$request->input('semester'), 'scheduleAcademicYear'=>$academicYear ,'scheduleSoftDelete'=>0,'classroomSoftDelete'=>0])
                    ->join("classrooms","scheduleClassroom","=","classroomID")
                    ->join('professors','scheduleProfessor','professorID')
                    ->select(
                        "scheduleDay as day",
                        "scheduleStart as start",
                        "scheduleEnd as end",
                        "scheduleID as id",
                        "scheduleSubject as subject",
                        "scheduleSection as section",
                        "classroomName as classroom",
                        "professorFullname as professor",
                        )
                    ->get();

                    if(count($professorSchedule) > 0){
                        foreach($professorSchedule as $schedule){
                            for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                                $schedules[$schedule['day']]['professor'][$i] = [
                                    "id"=>$schedule['id'],
                                    "subject"=>$schedule['subject'],
                                    "section"=>$schedule['section'],
                                    "classroom"=>$schedule['classroom'],
                                    "professor"=>$schedule['professor']
                                ];
                            }
                        }
                    }

                    foreach($this->times as $i=>$time){
                        array_push($schedule_output, [
                            "time"=>$time,
                            "monday"=>$schedules['monday']['professor'][$i],
                            "tuesday"=>$schedules['tuesday']['professor'][$i],
                            "wednesday"=>$schedules['wednesday']['professor'][$i],
                            "thursday"=>$schedules['thursday']['professor'][$i],
                            "friday"=>$schedules['friday']['professor'][$i],
                            "saturday"=>$schedules['saturday']['professor'][$i]
                        ]);
                    }
                    break;
                }
                case 'classroom':{

                    $classroom = Classroom::where(['classroomID'=>$request->input('id')])
                    ->select("classroomName as name")
                    ->first();

                    $name = $classroom->name;

                    $classroomSchedule = Schedule::where(['scheduleClassroom'=>$request->input('id'), "scheduleSemester"=>$request->input('semester'), 'scheduleAcademicYear'=>$academicYear ,'scheduleSoftDelete'=>0,'classroomSoftDelete'=>0])
                    ->join("classrooms","scheduleClassroom","=","classroomID")
                    ->join('professors','scheduleProfessor','professorID')
                    ->select(
                        "scheduleDay as day",
                        "scheduleStart as start",
                        "scheduleEnd as end",
                        "scheduleID as id",
                        "scheduleSubject as subject",
                        "scheduleSection as section",
                        "classroomName as classroom",
                        "professorFullname as professor",
                        )
                    ->get();

                    if(count($classroomSchedule) > 0){
                        foreach($classroomSchedule as $schedule){
                            for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                                $schedules[$schedule['day']]['professor'][$i] = [
                                    "id"=>$schedule['id'],
                                    "subject"=>$schedule['subject'],
                                    "section"=>$schedule['section'],
                                    "classroom"=>$schedule['classroom'],
                                    "professor"=>$schedule['professor']
                                ];
                            }
                        }
                    }

                    foreach($this->times as $i=>$time){
                        array_push($schedule_output, [
                            "time"=>$time,
                            "monday"=>$schedules['monday']['professor'][$i],
                            "tuesday"=>$schedules['tuesday']['professor'][$i],
                            "wednesday"=>$schedules['wednesday']['professor'][$i],
                            "thursday"=>$schedules['thursday']['professor'][$i],
                            "friday"=>$schedules['friday']['professor'][$i],
                            "saturday"=>$schedules['saturday']['professor'][$i]
                        ]);
                    }
                    break;
                }
                case 'section':{

                    $name = $request->input('id');

                    $sectionSchedule = Schedule::where(['scheduleSection'=>$request->input('id'), "scheduleSemester"=>$request->input('semester'), 'scheduleAcademicYear'=>$academicYear ,'scheduleSoftDelete'=>0,'classroomSoftDelete'=>0])
                    ->join("classrooms","scheduleClassroom","=","classroomID")
                    ->join('professors','scheduleProfessor','professorID')
                    ->select(
                        "scheduleDay as day",
                        "scheduleStart as start",
                        "scheduleEnd as end",
                        "scheduleID as id",
                        "scheduleSubject as subject",
                        "scheduleSection as section",
                        "classroomName as classroom",
                        "professorFullname as professor",
                        )
                    ->get();

                    if(count($sectionSchedule) > 0){
                        foreach($sectionSchedule as $schedule){
                            for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                                $schedules[$schedule['day']]['professor'][$i] = [
                                    "id"=>$schedule['id'],
                                    "subject"=>$schedule['subject'],
                                    "section"=>$schedule['section'],
                                    "classroom"=>$schedule['classroom'],
                                    "professor"=>$schedule['professor']
                                ];
                            }
                        }
                    }

                    foreach($this->times as $i=>$time){
                        array_push($schedule_output, [
                            "time"=>$time,
                            "monday"=>$schedules['monday']['professor'][$i],
                            "tuesday"=>$schedules['tuesday']['professor'][$i],
                            "wednesday"=>$schedules['wednesday']['professor'][$i],
                            "thursday"=>$schedules['thursday']['professor'][$i],
                            "friday"=>$schedules['friday']['professor'][$i],
                            "saturday"=>$schedules['saturday']['professor'][$i]
                        ]);
                    }
                    break;
                }
            }

            return response()->json(array(['name'=>$name,"year"=>$academicYear,"semester"=>$request->input('semester'), "schedules"=>$schedule_output]));

            
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

}
