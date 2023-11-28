<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Department;
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

}
