<?php

namespace App\Http\Controllers;

use App\Models\MinorSchedule;
use App\Models\Schedule;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $salt = "schedlr";

    protected function tokenDepartment($tokens){
        $toks = $this->salt.$tokens;
        $convert = User::where(["users.personal_tokens"=>$toks])
        ->join("departments","departments.department","=","users.userDepartment")
        ->select("department")->first();
    
        return $convert['department'];
    }

    protected $times  = 
        [
            "7:00 AM", "7:30 AM",
            "8:00 AM", "8:30 AM",
            "9:00 AM", "9:30 AM",
            "10:00 AM", "10:30 AM",
            "11:00 AM", "11:30 AM",
            "12:00 PM", "12:30 PM",
            "1:00 PM", "1:30 PM",
            "2:00 PM", "2:30 PM",
            "3:00 PM", "3:30 PM",
            "4:00 PM", "4:30 PM",
            "5:00 PM", "5:30 PM",
            "6:00 PM", "6:30 PM",
            "7:00 PM", "7:30 PM",
            "8:00 PM", "8:30 PM",
            "9:00 PM", "9:30 PM",
            "10:00 PM",
        ];

    protected $officialTemplate = [
        "monday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "tuesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "wednesday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "thursday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "friday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        "saturday"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
    ];

    protected function convertTime(int $day){
        switch($day){
            case 0: return "7:00 AM"; case 1: return "7:30 AM";
            case 2: return "8:00 AM"; case 3: return "8:30 AM";
            case 4: return "9:00 AM"; case 5: return "9:30 AM";
            case 6: return "10:00 AM"; case 7: return "10:30 AM";
            case 8: return "11:00 AM"; case 9: return "11:30 AM";
            case 10: return "12:00 PM"; case 11: return "12:30 PM";
            case 12: return "1:00 PM"; case 13: return "1:30 PM";
            case 14: return "2:00 PM"; case 15: return "2:30 PM";
            case 16: return "3:00 PM"; case 17: return "3:30 PM";
            case 18: return "4:00 PM"; case 19: return "4:30 PM";
            case 20: return "5:00 PM"; case 21: return "5:30 PM";
            case 22: return "6:00 PM"; case 23: return "5:30 PM";
            case 24: return "7:00 PM"; case 25: return "6:30 PM";
            case 26: return "8:00 PM"; case 27: return "8:30 PM";
            case 28: return "9:00 PM"; case 29: return "9:30 PM";
            case 30: return "10:00 PM";
        }
    }

    protected function conflictCheckers($start,$end,$schedule,$check){
        $template = $schedule;
        for($i = $start; $i <= $end; $i++){
            if($start == $i ){
                if($start == 10 || $start == 11){
                    return ["status"=>false,'msg'=>"Lunch Break, Do not select between 12:00PM-1:00PM"];
                }
            }
            else if($end == $i ){
                if($end == 11 || $end == 12){
                    return ["status"=>false,'msg'=>"Lunch Break, Do not select between 12:00PM-1:00PM"];
                }
            }
            else{
                if($template[$i] === 1){
                    return ["status"=>false,'msg'=>"{$check} Schedule Conflicts!"];
                }

                if($i == 11 || $i == 12){
                    return ["status"=>false,'msg'=>"Lunch Break, Do not select between 12:00PM-1:00PM"];
                }
            }
        }
        return ["status"=>true, "msg"=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]];
    }

    protected function hours($professor,$semester, $department = null){
        try{

            $template = [
                "monday"    =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "tuesday"   =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "wednesday" =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "thursday"  =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "friday"    =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "saturday"  =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            ];

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
                for($x =  $schedule[$i]->start;$x < $schedule[$i]->end; $x++ ){
                    $template[$day][$x] = 1;
                    $hours += 30;
                }
            }

            $template = [
                "monday"    =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "tuesday"   =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "wednesday" =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "thursday"  =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "friday"    =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                "saturday"  =>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            ];

            $minor = MinorSchedule::where([
                "minorProfessor"=>$professor,
                "minorSemester"=> $semester,
                "minorDepartment"=>$department,
            ])
            ->select('minorDay as day','minorStart as start', 'minorEnd as end')
            ->get();

            $day = null;
            for($i = 0; $i < count($minor);$i++){
                $day = $minor[$i]->day;
                for($x =  $minor[$i]->start;$x < $minor[$i]->end; $x++ ){
                    $template[$day][$x] = 1;
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
}
