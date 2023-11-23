<?php

namespace App\Http\Controllers;

use App\Mail\PasswordSender;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected function login(Request $request){
        
        try{
            $validator = Validator::make($request->all(),[
                'email'=>"required|email",
                'password'=>"required|min:8",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('email')){
                    $errorMessage = $validator->errors()->first('email');
                    return response(['status'=>false, "error"=>"email" ,'msg'=>$errorMessage]);
                }
                if($validator->errors()->has('password')){
                    $errorMessage = $validator->errors()->first('password');
                    return response(['status'=>false,"error"=>"password",'msg'=>$errorMessage]);
                }
            }

            
            $check  = User::join('departments','department', '=', 'userDepartment')
            ->where(['userEmail'=>$request->input('email'),'userSoftDelete'=>0,"departmentSoftDelete"=>0])
            ->first();

            if($check){
               
                if(!Hash::check($request->input('password'),$check->userPassword)){
                    return response()->json(['status'=>false,"error"=>"password" , 'msg'=>"Password not matched!"]);
                }

                $generate_tokens = Str::random(80);
                $hash_token = $this->salt.$generate_tokens;
                $updateToken = User::where(['userEmail'=>$request->input('email')])->update(['personal_tokens'=>$hash_token]);

                return ($updateToken) ? response()->json(['status'=>true,'tokens'=>$generate_tokens]) : Log::error("Token wasnt updating");
            }
            else{
                return response()->json(["status"=>false,"error"=>"email","msg"=>"Please double check your email."]);
            }

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }

    }

    protected function sessionLogin($token){
        
        $toks = $this->salt.$token;
        $check = User::where(['personal_tokens'=>$toks,"userSoftDelete"=>0])
        ->select("userRole as role")
        ->first();

        if($check === null){
            return response()->json(["isAuth"=>false, 'isRole'=> '']);
        }
        else{
            return response()->json(["isAuth"=>true, 'isRole'=> $check['role']]);
        }
    }

    protected function read($tokens){
        try{

            $department = $this->tokenDepartment($tokens);

            $admins = User::where(["userDepartment"=>$department, "userRole"=>"admin"])
            ->select("userID as id","userEmail as email","userFullname as fullname", "userRole as role", "userSoftDelete as status")
            ->get();
            return response()->json($admins);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function changeStatus(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "id"=>"required|integer",
                "status"=>"required|integer|in:0,1",
            ]);
    
            if($validator->fails()){
                Log::error($validator->fails());
            }
    
            $check = User::where(['userID'=>$request->input('id')])->count();
            
            if($check == 0){
                Log::error("Data modified by users!!!");
            }

            $status = ($request->input('status') == 1) ? 0 : 1;

            User::where(['userID'=>$request->input('id')])->update(["userSoftDelete"=>$status]);
            
            return response()->json(["status"=>true, "msg"=>"Successfully Updated."]);

        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }

    }

    protected function createAccounts(Request $request){
        try{

            $validator = Validator::make($request->all(),[
                'email'=>"required|email|unique:users,userEmail",
                "fullname"=>"required",
                "tokens"=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('email')){
                    $errorMessage = $validator->errors()->first('email');
                    return response(['status'=>false, "error"=>"email" ,'msg'=>$errorMessage]);
                }

                if($validator->errors()->has('fullname')){
                    $errorMessage = $validator->errors()->first('fullname');
                    return response(['status'=>false, "error"=>"fullname" ,'msg'=>$errorMessage]);
                }

                if($validator->errors()->has('department')){
                    $errorMessage = $validator->errors()->first('department');
                    Log::error($errorMessage);
                }
            }

            $department = $this->tokenDepartment($request->input('tokens'));
            
            $generate = Str::random(12);
            $password = Hash::make($generate);
            Mail::to($request->input('email'), $request->input('fullname'))->send(new PasswordSender($generate));
            $save = new User();
            $save->userEmail= $request->input('email');
            $save->userFullname = strtolower($request->input('fullname'));
            $save->userPassword = $password;
            $save->userRole = "admin";
            $save->userDepartment = strtolower($department);
            $save->userSoftDelete =  0;
            $save->personal_tokens = $this->salt.Str::random(80);
            $res = $save->save();

            if(!$res){
                return response()->json("Failed to create Admin accounts.");
            }

            return response()->json(["status"=>true,"msg"=>"Successfully Created!"]);
            
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function createAccountDean(Request $request){
        try{

            $validator = Validator::make($request->all(),[
                'email'=>"required|email|unique:users,userEmail",
                "fullname"=>"required",
                "department"=>"required",
                "password"=>"required|min:8",
                "tokens"=>"required"
            ]);

            if($validator->fails()){
                if($validator->errors()->has('email')){
                    $errorMessage = $validator->errors()->first('email');
                    return response(['status'=>false, "error"=>"email" ,'msg'=>$errorMessage]);
                }

                if($validator->errors()->has('fullname')){
                    $errorMessage = $validator->errors()->first('fullname');
                    return response(['status'=>false, "error"=>"fullname" ,'msg'=>$errorMessage]);
                }

                if($validator->errors()->has('department')){
                    $errorMessage = $validator->errors()->first('department');
                    return response(['status'=>false, "error"=>"department" ,'msg'=>$errorMessage]);
                }

                if($validator->errors()->has('password')){
                    $errorMessage = $validator->errors()->first('password');
                    return response(['status'=>false, "error"=>"password" ,'msg'=>$errorMessage]);
                }

                if($validator->errors()->has('tokens')){
                    $errorMessage = $validator->errors()->first('tokens');
                    Log::error($errorMessage);
                }
            }

            $tokens = $this->salt.$request->input('tokens');
            $users = User::join("deparments","userDepartment","=","department")
            ->select('userPassword as password','userDepartment as department')
            ->where(["userSoftDelete"=>0,"departmentSoftDelete"=>0,'personal_tokens'=>$tokens])->first();

            if($users == null){
                Log::error("tokens is in correct!");
            }

            $checkPassword = Hash::check($request->input("password"),$users->password);

            if(!$checkPassword){
                return response()->json(["status"=>false, "error"=>"password" ,"msg"=>"Password incorrect!"]);
            }

            if($users->department != $request->input('department') ){
                $checkDepartmentUsers = User::join("departments","userDepartment","=","department")
                ->where(['userDepartment'=>$request->input('department'), "departmentSoftDelete"=>0])->count();
                if($checkDepartmentUsers > 0){
                    return response()->json(["status"=>false, "error"=>"global" ,"msg"=>"You don't have authorize!"]);
                }

            }
    
            $generate = Str::random(12);
            $password = Hash::make($generate);
            Mail::to($request->input('email'), $request->input('fullname'))->send(new PasswordSender($generate));
            $save = new User();
            $save->userEmail= $request->input('email');
            $save->userFullname = strtolower($request->input('fullname'));
            $save->userPassword = $password;
            $save->userRole = "dean";
            $save->userDepartment = $request->input('department');
            $save->userSoftDelete =  0;
            $save->personal_tokens = $this->salt.Str::random(80);
            $res = $save->save();

            if($res){
                return response()->json(["status"=>true,"msg"=>"Successfully Created!"]);
            }
            
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function updateEmail(Request $request){
        try{

            $validator = Validator::make($request->all(),[
                "email"=> "required|email",
                "password"=> "required|min:8",
                "tokens"=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('email')){
                    $errorMessage = $validator->errors()->first('email');
                    return response(['status'=>false, "error"=>"email" ,'msg'=>$errorMessage]);
                }

                if($validator->errors()->has('password')){
                    $errorMessage = $validator->errors()->first('password');
                    return response(['status'=>false, "error"=>"password" ,'msg'=>$errorMessage]);
                }
                
                if($validator->errors()->has('tokens')){
                    $errorMessage = $validator->errors()->first('tokens');
                    Log::error($errorMessage);
                }
            }

            $department = $this->tokenDepartment($request->input('tokens'));
            $tokens = "schedlr".$request->input('tokens');

            $getUser = User::join("departments","userDepartment","=","department")
            ->select("userEmail as email", "userPassword as password")
            ->where(["personal_tokens"=>$tokens,"userDepartment"=>$department,"userSoftDelete"=>0,"departmentSoftDelete"=>0])
            ->first();

            $checkEmail = User::where(['userEmail'=>$request->input('email')])
            ->count();

            if($checkEmail == 0){ 
                if(Hash::check($request->input('password'),$getUser->password)){
                    $update =  User::join('departments','userDepartment','=','department')
                    ->where(['personal_tokens'=>$tokens,"userDepartment"=>$department,'departmentSoftDelete'=>0,"userSoftDelete"=>0])
                    ->update(["userEmail"=>$request->input('email')]);

                    return response()->json(['status'=>true, "msg"=>"Successfully Updated."]);
                }
                else{
                    return response()->json(["status"=>false,"error"=>"password","msg"=>"Incorrect password!"]);
                }
            }
            else{
                return response()->json(['status'=>false,"error"=>"email","msg"=>"Email is already registered."]);
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function updatePassword(Request $request){
        try{
            $validator = Validator($request->all(),[
                "password"=>"required",
                "new_password"=>"required|min:8",
                "tokens"=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('password')){
                    $errorMessage = $validator->errors()->first('password');
                    return response(['status'=>false, "error"=>"password" ,'msg'=>$errorMessage]);
                }

                if($validator->errors()->has('new_password')){
                    $errorMessage = $validator->errors()->first('new_password');
                    return response(['status'=>false, "error"=>"new_password" ,'msg'=>$errorMessage]);
                }
            }

            $department = $this->tokenDepartment($request->input('tokens'));
            $tokens = "schedlr".$request->input('tokens');

            $check = User::join("departments","userDepartment","=","department")
            ->select("userEmail as email", "userPassword as password")
            ->where(["personal_tokens"=>$tokens,"userDepartment"=>$department,"userSoftDelete"=>0,"departmentSoftDelete"=>0])
            ->first();

            if($check != ''){
                
                if(Hash::check($request->input('password'),$check->password)){
                    $HashPassword = Hash::make($request->input('new_password'));
                    
                    User::where(['userEmail'=>$check->email,"personal_tokens"=>$tokens])
                    ->update(["userPassword"=>$HashPassword]);

                    return response()->json(["status"=>true,"msg"=>"Successfully Updated."]);

                }
                else{
                    return response()->json(["status"=>false, "error"=>"password","msg"=>"Incorrect Password!"]);
                }
            }
            else{
                Log::error("email not found, This account is already login!.");
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        } 
    }
}
