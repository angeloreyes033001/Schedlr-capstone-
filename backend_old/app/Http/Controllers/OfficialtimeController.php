<?php

namespace App\Http\Controllers;

use App\Models\OfficialTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OfficialtimeController extends Controller
{

    private $schedules = [
        'monday'=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        'tuesday'=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        'wednesday'=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        'thursday'=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        'friday'=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        'saturday'=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
    ];

    protected function create(Request $request){
        try{

            $schedules = [
                'store'=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                'actual'=>[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ];

            $validator = Validator::make($request->all(),[
                "professor"=>"required",
                "day"=>"required|in:monday,tuesday,wednesday,thursday,friday,saturday",
                "start"=>"required|numeric",
                "end"=>"required|numeric",
                "semester" => "required|in:1st semester,2nd semester",
            ]);

            if($validator->fails()){

                if($validator->errors()->has('professor')){
                    $msgError = $validator->errors()->first('professor');
                    return response()->json(['status'=>false, "error"=>"professor", "msg"=>$msgError]);
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

                if($validator->errors()->has('semester')){
                    $msgError = $validator->errors()->first('semester');
                    return response()->json(['status'=>false, "error"=>"semester", "msg"=>$msgError]);
                }
            }

            $getTime = OfficialTime::where(['officialtimeProfessor'=>$request->input('professor'),'officialtimeSemester'=>$request->input('semester'),'officialtimeDay'=>$request->input('day'),'officialtimeSoftDelete'=>0])
            ->select("officialtimeStart as start", "officialtimeEnd as end")
            ->get();

            if(count($getTime) > 0){
                foreach($getTime as $time){
                    for($i = $time['start']; $i <= $time['end'];$i++ ) {
                        $schedules['store'][$i] = 1;
                    }
                }
            }

            ///check if theres conflic
            for($i = $request->input('start'); $i <= $request->input('end');$i++){

                if($i === $request->input('start') || $i === $request->input('end') ){
                    $schedules['actual'][$i] = 1;
                }
                else{
                    if($schedules['store'][$i] != 1 ){
                        $schedule['actual'][$i] = 1;
                    }
                    else{
                        $convert =  $this->convertTime($request->input('start')).' - '.$this->convertTime($request->input('end'));
                        return response()->json("Conflict between ".$convert.', Please check the Official Time table.');
                    }
                }

            }
            
            $save = new OfficialTime();
            $save->officialtimeSemester = strtolower($request->input('semester'));
            $save->officialtimeDay = strtolower($request->input('day'));
            $save->officialtimeStart = $request->input('start');
            $save->officialtimeEnd = $request->input('end');
            $save->officialtimeProfessor = strtolower($request->input('professor'));
            $save->save();

            return response()->json(["status"=>true, "msg"=>"Successuly Created!"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read(Request $request){
        try{
            $read = OfficialTime::where(['officialtimeProfessor'=>$request->input('professor'),'officialtimeSemester'=>$request->input('semester'),'officialtimeSoftDelete'=>0])
            ->select('officialtimeID as id','officialtimeDay as day', 'officialtimeStart as start','officialtimeEnd as end')
            ->get();

            $day = null;
            for($i=0;$i < count($read);$i++){
                $day = $read[$i]->day;
                for($x =  $read[$i]->start;$x <= $read[$i]->end; $x++ ){
                    $this->schedules[$day][$x] = 1;
                }
            }

            $output = [];
            for($i = 0; $i < 31; $i++){
                $time = $this->times[$i];
                $monday = $this->schedules["monday"][$i];
                $tuesday = $this->schedules["tuesday"][$i];
                $wednesday = $this->schedules["wednesday"][$i];
                $thursday = $this->schedules["thursday"][$i];
                $friday = $this->schedules["friday"][$i];
                $saturday = $this->schedules["saturday"][$i];
                array_push($output, [ "time"=>$time, "mon"=>$monday,"tue"=>$tuesday ,"wed"=>$wednesday, "thu"=>$thursday, "fri"=>$friday,"sat"=>$saturday]);
            }

            return response()->json($output);

        }catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function update(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "id"=>"required", 
                "day"=>"required",
                "start"=>"required|numeric",
                "end"=>"required|numeric"
            ]);

            if($validator->fails()){

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

            OfficialTime::where(["officialtimeID"=>$request->input('id')])
            ->update(["officialtimeDay"=>strtolower($request->input('day')),"officialtimeStart"=>$request->input('start'),"officialtimeEnd"=>$request->input('end')]);
            return response()->json(["status"=>true,"msg"=>"Successfully Updated."]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete($id){
        try{
            OfficialTime::where(["officialtimeID"=>$id])->update(['officialtimeSoftDelete'=>1]);
            return response()->json(["status"=>true,"msg"=>"Successfully Deleted."]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function showDelete(Request $request){
        $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];

        $lists = [];

        // Initialize lists with empty arrays for each day
        foreach ($days as $day) {
            $lists[$day] = ['list' => []];
        }

        $officialTimes = OfficialTime::where([
            "officialtimeProfessor" => $request->input('professor'),
            'officialtimeSemester' => $request->input('semester'),
            "officialtimeSoftDelete" => 0
        ])->select("officialtimeID as id", "officialtimeDay as day", "officialtimeStart as start", "officialtimeEnd as end")->get();

        foreach ($officialTimes as $data) {
            $day = strtolower($data['day']);
            $time = $this->convertTime($data['start']) . ' - ' . $this->convertTime($data['end']);
            
            $lists[$day]['list'][] = ["id" => $data['id'], "day" => $day, "time" => $time];
        }

        return response()->json($lists);
    }

}
