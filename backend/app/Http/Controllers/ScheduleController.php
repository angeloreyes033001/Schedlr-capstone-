<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Load;
use App\Models\OfficialTime;
use App\Models\Schedule;
use App\Models\sections;
use App\Models\Subject;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    protected function showLoad($professor){
        try{
            $loads = Load::where(["loadProfessor"=>$professor],"loadSoftDelete")
            ->join("subjects","subjectCode","=","loadSubject")
            ->select("subjectCode as code","loadHour as givenHour","subjectLecHour as lec","subjectLabHour as lab","subjectYearlevel as year")
            ->get();

            return response()->json($loads);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
    protected function showClassroom(Request $request){
        ///department(other) // department(login)
        try{
            $validator = Validator::make($request->all(),[
                "type"=>"required|in:lecture,laboratory",
                "tokens"=>"required"
            ]);

            $department = $this->tokenDepartment($request->input('tokens'));

            $ownClassroom = Classroom::where(["classroomType"=>$request->input('type'),"classroomDepartment"=>$department,"classroomSoftDelete"=>0])
            ->select("classroomID as id","classroomName as classroom")
            ->get();

            $arr_classroom = array();
            if(count($ownClassroom) > 0){
                foreach($ownClassroom as $classroom){
                    array_push($arr_classroom,["id"=>$classroom['id'],"classroom"=>$classroom['classroom']]);
                }
            }

            return response()->json($arr_classroom);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }

    }

    protected function showSection(Request $request){
        //subject to get what section year show;
        try{
            $validator = Validator::make($request->all(),[
                "subject"=>"required",
                "tokens"=>"required"
            ]);

            $subjects = Subject::join("sections","subjectYearlevel","=","sectionYearlevel")
            ->join("section_openeds","sectionID",'=',"sectionopenedID")
            ->where([
                "subjectCode"=>$request->input('subject'),
                "subjectSoftDelete"=>0,
                "sectionSoftDelete"=>0,
                "sectionopenedSoftDelete"=>0,
            ])
            ->select("sectionopenedSpecialization as specialization","sectionopenedName as section")
            ->get();

            
            $specializations = Subject::where(["subjectCode"=>$request->input('subject')])
            ->select("subjectSpecialization as spe")
            ->first();

            $output = array();

            foreach($subjects as $subject){
                if($specializations['spe'] == $subject['specialization']){
                    array_push($output,$subject);
                }
            }

            return response()->json($output);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function professorSchedule($professor){
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

        $OfficialtimeProfessor = OfficialTime::where(['officialtimeProfessor'=>$professor,"officialtimeSoftDelete"=>0])
        ->select("officialtimeDay as day","officialtimeStart as start","officialtimeEnd as end")
        ->get();

        if(count($OfficialtimeProfessor) > 0){
            foreach($OfficialtimeProfessor as $schedule){
                for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                    $schedules[$schedule['day']]['professor'][$i] = 1;
                }
            }
        }

        $professorSchedule = Schedule::where(['scheduleProfessor'=>$professor,'scheduleAcademicYear'=>$academicYear ,'scheduleSoftDelete'=>0,'classroomSoftDelete'=>0])
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

    protected function classroomSchedule($classroom){
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

        $sectionSchedule = Schedule::where(['scheduleClassroom'=>$classroom,'scheduleAcademicYear'=>$academicYear ,'scheduleSoftDelete'=>0,'classroomSoftDelete'=>0])
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

    protected function sectionSchedule($section){
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

        $sectionSchedule = Schedule::where(['scheduleSection'=>$section,'scheduleAcademicYear'=>$academicYear ,'scheduleSoftDelete'=>0,'classroomSoftDelete'=>0])
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

    protected function showAvailableDelete($professor){
        try{
            $currentYear = now()->format('Y');
            $nextYear = now()->addYear()->format('Y');
            $academicYear = $currentYear. '-'.$nextYear;
    
            $professorSchedule = Schedule::where(['scheduleProfessor'=>$professor,'scheduleAcademicYear'=>$academicYear ,'scheduleSoftDelete'=>0,'classroomSoftDelete'=>0])
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
    
            $output = array();
            if(count($professorSchedule) > 0){
                foreach($professorSchedule as $schedule){
                    $start = $this->convertTime($schedule['start']);
                    $end = $this->convertTime($schedule['end']);
                    $actualtime = $start.'-'.$end;
                    
                    array_push($output,["id"=>$schedule['id'],"subject"=>$schedule['subject'],"day"=>$schedule['day'],"time"=>$actualtime,"section"=>$schedule['section'],"classroom"=>$schedule['classroom']]);
                }
            }
            return response()->json($output);
        }
        catch(Exception $e){
                Log::error($e->getMessage());
        } 
    }

    protected function create_schedule(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "day"=>"required|in:monday,tuesday,wednesday,thursday,friday,saturday",
                "start"=>"required|integer",
                "end"=>"required|integer",
                "professor"=>"required",
                "subject"=>"required",
                "classroom"=>"required|integer",
                "section"=>"required",
            ]);

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

            $getSemester = Subject::where(['subjectCode'=>$request->input('subject')])
            ->select("subjectSemester as sem")
            ->first();

            $getAssignedHour = Load::where(['loadProfessor'=>$request->input('professor'),"loadSubject"=>$request->input('subject'),"loadSoftDelete"=>0])
            ->select("loadHour as hour")->first();

            $getUsedHour =  Schedule::where(['scheduleProfessor'=>$request->input('professor'),'scheduleAcademicYear'=>$academicYear,'scheduleSoftDelete'=>0])
            ->select("scheduleDay as day","scheduleStart as start","scheduleEnd as end")
            ->get();

            $getAllowedOverlap = Classroom::where(['classroomID'=>$request->input('classroom')])
            ->select("classroomMulti as overlap")
            ->first();

            $totalUsedHolder = 0;
            if(count($getUsedHour) > 0){
                foreach($getUsedHour as $schedule){
                    for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                        $totalUsedHolder += 30;
                    }
                }
            }
            $totalUsed = Carbon::now()->addMinutes($totalUsedHolder)->diffInHours(Carbon::now());

            if($totalUsed >= $getAssignedHour['hour'] ){
                return response()->json(['status'=>false,"msg"=>"Already reach maximun givern"]);
            }

            $want_insert_subHour = 0;
            for($i = $request->input('start');$i <= $request->input('end');$i++ ){
                $want_insert_subHour += 30;
            }
            $convert_want_insert_subHour = Carbon::now()->addMinutes($want_insert_subHour)->diffInHours(Carbon::now());

            $check_schedule_inserted = Schedule::where([
                "scheduleSubject"=>$request->input('subject'),
                "scheduleSection"=>$request->input('section'),
                'scheduleAcademicYear'=>$academicYear,
                'scheduleSoftDelete'=>0])
            ->select("scheduleDay as day","scheduleStart as start","scheduleEnd as end")
            ->get();

            $subjecHour = 0;
            if(count($check_schedule_inserted) > 0 ){
                foreach($check_schedule_inserted as $schedule){
                    for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                        $subjecHour += 30;
                    }
                    $convert_insert_time = Carbon::now()->addMinutes($subjecHour)->diffInHours(Carbon::now());
                    if($convert_want_insert_subHour == $convert_insert_time){
                        return response()->json(["status"=>false, "msg"=>"already assigned schedule of the subject."]);
                    }
                    $subjecHour = 0;
                }
            }

            $OfficialtimeProfessor = OfficialTime::where(['officialtimeProfessor'=>$request->input('professor'),"officialtimeSoftDelete"=>0])
            ->select("officialtimeDay as day","officialtimeStart as start","officialtimeEnd as end")
            ->get();

            if(count($OfficialtimeProfessor) > 0){
                foreach($OfficialtimeProfessor as $schedule){
                    for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                        $schedules[$schedule['day']]['professor'][$i] = 2;
                    }
                }
            }

            $professorSchedule = Schedule::where(['scheduleProfessor'=>$request->input('professor'),'scheduleAcademicYear'=>$academicYear,'scheduleSoftDelete'=>0])
            ->select("scheduleDay as day","scheduleStart as start","scheduleEnd as end")
            ->get();

            if(count($professorSchedule) > 0){
                foreach($professorSchedule as $schedule){
                    for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                        $schedules[$schedule['day']]['professor'][$i] = 1;
                    }
                }
            }

            $classroomSchedule = Schedule::where(['scheduleClassroom'=>$request->input('classroom'),'scheduleAcademicYear'=>$academicYear,'scheduleSoftDelete'=>0])
            ->select("scheduleDay as day","scheduleStart as start","scheduleEnd as end")
            ->get();

            if(count($classroomSchedule) > 0){
                foreach($classroomSchedule as $schedule){
                    for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                        $schedules[$schedule['day']]['classroom'][$i] = 1;
                    }
                }
            }

            $sectionSchedule = Schedule::where(['scheduleSection'=>$request->input('section'),'scheduleAcademicYear'=>$academicYear,'scheduleSoftDelete'=>0])
            ->select("scheduleDay as day","scheduleStart as start","scheduleEnd as end")
            ->get();

            if(count($sectionSchedule) > 0){
                foreach($sectionSchedule as $schedule){
                    for($i = $schedule['start'];$i <= $schedule['end'];$i++ ){
                        $schedules[$schedule['day']]['section'][$i] = 1;
                    }
                }
            }

            for($i = $request->input('start'); $i <= $request->input('end'); $i++ ){
                if($request->input('start') == 10 || $request->input('start') == 11 ){
                    return response()->json(["status"=>false,"msg"=>"You can't start class in 12:00NN-1:00PM"]);
                }
                else{
                    if($request->input('start') != $i && $request->input('end') != $i ){

                        if($i != 10 || $i == 11 ){
                            if($schedules[$request->input('day')]['professor'][$i] == 1 ){
                                return response()->json(["status"=>false,"msg"=>"Professor schedule conflict"]);
                            }
    
                            if($schedules[$request->input('day')]['classroom'][$i] == 1){
                                return response()->json(["status"=>false,"msg"=>"Classroom schedule conflict"]);
                            }
    
                            if($getAllowedOverlap['overlap'] > 0){
                                if($schedules[$request->input('day')]['section'][$i] == 1){
                                    return response()->json(["status"=>false,"msg"=>"Section schedule conflict"]);
                                } 
                            }
                        }
                        else{
                            return response()->json(["status"=>false,"msg"=>"12:00nn-1:00pm are lunch time."]);
                        }
                    }
                }
            }

            $counterRegular = 0;
            $counterOT = 0;
            for($i = $request->input('start'); $i <= $request->input('end'); $i++ ){
                if($schedules[$request->input('day')]['professor'][$i] == 1 || $schedules[$request->input('day')]['professor'][$i] == 0 ){
                    $counterRegular += 1;
                }
                else{
                    $counterOT += 0;
                }
            }

            $scheduleWork =($counterRegular == 0) ? "over-time" : "regular";

            $save = new Schedule();
            $save->scheduleDay = $request->input('day');
            $save->scheduleStart = $request->input('start');
            $save->scheduleEnd = $request->input('end');
            $save->scheduleWork = $scheduleWork;
            $save->scheduleProfessor = $request->input('professor');
            $save->scheduleSubject = $request->input("subject");
            $save->scheduleClassroom = $request->input('classroom');
            $save->scheduleSection = $request->input('section');
            $save->scheduleSemester = $getSemester['sem'];
            $save->scheduleStatus = 0;
            $save->scheduleApproved = 0;
            $save->scheduleAcademicYear = $academicYear;
            $save->scheduleSoftDelete = 0;
            $save->save();

            return response()->json(['status'=>true, "msg"=>"Successfully Added."]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete_schedule($id){
        try{
            $check = Schedule::where(['scheduleID'=>$id,'scheduleSoftDelete'=>0])->count();

            if($check > 0){
                Schedule::where(['scheduleID'=>$id])->update(['scheduleSoftDelete'=>1]);
                return response()->json(["status"=>true,'msg'=>"Successfully Deleted"]);
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
    
}
