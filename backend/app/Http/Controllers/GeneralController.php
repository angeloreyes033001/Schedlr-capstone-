<?php

namespace App\Http\Controllers;

use App\Mail\SendNotification;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{
    protected function total_subjects($tokens){
        try{
            $department = $this->tokenDepartment($tokens);
            $totals = Subject::where(['subjectDepartment'=>$department,"subjectSoftDelete"=>0])
            ->count();

            return response()->json($totals);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
    protected function total_classrooms($tokens){
        try{
            $department = $this->tokenDepartment($tokens);
            $totals = Classroom::where(['classroomDepartment'=>$department,"classroomSoftDelete"=>0])
            ->count();

            return response()->json($totals);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function totalSection($tokens){
        // try{
        //     $department = $this->tokenDepartment($tokens);
        //     $totals = Classroom::where(['classroomDepartment'=>$department,"classroomSoftDelete"=>0])
        //     ->count();

        //     return response()->json($totals);
        // }
        // catch(Exception $e){
        //     Log::error($e->getMessage());
        // }
    }

    protected function total_admins($tokens){
        try{
            $department = $this->tokenDepartment($tokens);
            $totals = User::where(['userDepartment'=>$department,"userRole"=>"admin","userSoftDelete"=>0])
            ->count();

            return response()->json($totals);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function show_schedule_public($tokens){
        try{
            $currentYear = now()->format('Y');
            $nextYear = now()->addYear()->format('Y');
            $academicYear = $currentYear. '-'.$nextYear;
            $department = $this->tokenDepartment($tokens);

            $schedules = Schedule::
            join('section_openeds',"scheduleSection","=","sectionopenedName")
            ->join("sections","sectionopenedID","=","sectionID")
            ->where(["sectionDepartment"=>$department,'scheduleSoftDelete'=>0,"scheduleAcademicYear"=>$academicYear])
            ->select("scheduleID as id")
            ->get();

            if(count($schedules)> 0){
                foreach($schedules as $schedule){
                    Schedule::where(['scheduleID'=>$schedule['id']])->update(['scheduleApproved'=>1]);
                }
            }

            return response()->json(["status"=>true,"msg"=>"The schedule is now visible to everyone."]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function hidden_public_schedule($tokens){
        try{
            $currentYear = now()->format('Y');
            $nextYear = now()->addYear()->format('Y');
            $academicYear = $currentYear. '-'.$nextYear;
            $department = $this->tokenDepartment($tokens);

            $schedules = Schedule::
            join('section_openeds',"scheduleSection","=","sectionopenedName")
            ->join("sections","sectionopenedID","=","sectionID")
            ->where(["sectionDepartment"=>$department,'scheduleSoftDelete'=>0,"scheduleAcademicYear"=>$academicYear])
            ->select("scheduleID as id")
            ->get();

            if(count($schedules)> 0){
                foreach($schedules as $schedule){
                    Schedule::where(['scheduleID'=>$schedule['id']])->update(['scheduleApproved'=>0]);
                }
            }

            return response()->json(["status"=>true,"msg"=>"The schedule is now hidden from the public."]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function dean_to_otherDepartment(Request $request){
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

    // protected function notifyMinorToDean(Request $request){
    //     try{
    //         $validator = Validator::make($request->all(),[
    //             'tokens'=>"required",
    //             'other_dep'=>"required",
    //         ]);

    //         if($validator->fails()){

    //             if($validator->errors()->has('other_dep')){
    //                 $msgError = $validator->errors()->first('other_dep');
    //                 Log::error($msgError);
    //             }

    //             if($validator->errors()->has('tokens')){
    //                 $msgError = $validator->errors()->first('tokens');
    //                 Log::error($msgError);
    //             }
    //         }

    //         $department = $this->tokenDepartment($request->input('tokens'));

    //         $userOtherDepartment = User::where(['userDepartment'=>$request->input('other_dep'),"userRole"=>"dean"])
    //         ->select("userEmail as email")
    //         ->first();

    //         Mail::to($userOtherDepartment->email)->send(new MinorToDean(strtoUpper($department),strtoUpper($request->input('other_dep'))));
    //         return response()->json(["status"=>true,"msg"=>"Sent Successfully"]);

    //     }
    //     catch(Exception $e){
    //         Log::error($e->getMessage());
    //     }
    // }
}
