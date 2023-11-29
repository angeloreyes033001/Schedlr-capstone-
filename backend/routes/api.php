<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearLevelController;
use App\Http\Controllers\OfficialTimeController;
use App\Http\Controllers\publicController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'departments'],function(){
     Route::post('create', [DepartmentController::class, 'create']);
    Route::get('read', [DepartmentController::class, 'read']);
    Route::get('delete/{department}', [DepartmentController::class, 'delete']);
});

Route::group(['prefix'=>'users'], function(){
    Route::post('login',  [UserController::class, 'login']);
    Route::get('session/{tokens}', [UserController::class , 'sessionLogin' ]);
    Route::get('read/{tokens}', [UserController::class , 'read' ]);
    Route::post('changeStatus', [UserController::class , 'changeStatus' ]);
    Route::post('create-accounts', [UserController::class, 'createAccounts']);
    Route::post('create-deans', [UserController::class, 'createAccountDean']);
    
    Route::post('update-email', [UserController::class,'updateEmail']);
    Route::post('update-password',[UserController::class, 'updatePassword']);
});

Route::group(['prefix'=>'yearlevels'], function(){
    Route::get('read/{tokens}', [YearLevelController::class, 'read']);
    Route::post('create', [YearLevelController::class, 'create']);
    Route::post('update', [YearLevelController::class, 'update']);
    Route::get('delete/{id}', [YearLevelController::class, 'delete']);
});

Route::group(['prefix'=>'ranks'],function(){
    Route::post('create', [RankController::class,'create'] );
    Route::get('read/{tokens}', [RankController::class,'read'] );
    Route::post('update', [RankController::class,'update'] );
    Route::get('delete/{id}', [RankController::class,'delete'] );
});

Route::group(['prefix'=>'designations'], function(){

    Route::post('create', [DesignationController::class , 'create']);
    Route::get('read', [DesignationController::class , 'read']);
    Route::post('update', [DesignationController::class , 'update'] );
    Route::get('delete/{position}', [DesignationController::class , 'delete']);
});

Route::group(['prefix'=>'specialization'], function(){

    Route::get('autocreatecommon/{tokens}', [ SpecializationController::class , 'AutoCreateCommon' ]);
    Route::post('create', [ SpecializationController::class , 'create' ]);
    Route::get('read/{tokens}',[SpecializationController::class , 'read' ]);
    Route::post('update',[SpecializationController::class,'update']);
    Route::post('delete', [ SpecializationController::class , 'delete' ]);
});

Route::group(["prefix"=>"sections"], function(){
    Route::post('read_slot',[SectionController::class , 'read_slot']);
    Route::post('update_slot',[SectionController::class,'update_slot']);
    Route::get('auto_generated_slot/{tokens}', [SectionController::class , 'auto_generated_slot']);
    Route::post('create_section', [SectionController::class,'create_section']);
    Route::post('read_section', [SectionController::class,'read_section']);
    Route::post('update_section', [SectionController::class,'update_section']);
    Route::get('delete_section/{section}',[SectionController::class, 'delete_section']);
});

Route::group(["prefix"=>'classrooms'], function(){
    Route::post('create',[ClassroomController::class, 'create']);
    Route::get('read/{tokens}',[ClassroomController::class, 'read']);
    Route::post('update',[ClassroomController::class, 'update']);
    Route::get('delete/{id}',[ClassroomController::class, 'delete']);
});

Route::group(['prefix'=>'subjects'],function(){
    Route::post('read_slot', [SubjectController::class,'read_slot']);
    Route::post('create',[SubjectController::class, 'create']);
    Route::get('read/{tokens}', [SubjectController::class , 'read'] );
    Route::post('update',[SubjectController::class, 'update']);
    Route::get('delete/{code}', [SubjectController::class, 'delete']);
});

Route::group(['prefix'=>'professors'], function(){
    Route::post('create',[ProfessorController::class , 'create']);
    Route::get('read/{tokens}',[ProfessorController::class , 'read']);
    Route::post('update',[ProfessorController::class , 'update']);
    Route::get('delete/{id}',[ProfessorController::class , 'delete']);
    Route::get('count/{tokens}',[ProfessorController::class , 'countProfessor']);
});

Route::group(['prefix'=>"loads"], function(){
    Route::get('read_load_professor/{tokens}',[LoadController::class, 'read_load_professor']);
    Route::post('read_all_load',[LoadController::class,'read_all_load']);
    Route::get('delete_load/{id}',[LoadController::class, 'delete_load']);
    Route::post('create_load',[LoadController::class,'create_load']);
});

Route::group(['prefix'=>'officialtime'],function(){
    Route::post('create',[OfficialTimeController::class, 'create']);
    Route::post('read',[OfficialTimeController::class, 'read']);
    Route::post('update',[OfficialTimeController::class, 'update']);
    Route::get('delete/{id}',[OfficialTimeController::class, 'delete']);
    Route::post('showDelete',[OfficialTimeController::class, 'showDelete']);
});

Route::group(['prefix'=>'schedules'],function(){
    Route::get('loads/{professor}',[ScheduleController::class,'showLoad']);
    Route::post('showClassroom',[ScheduleController::class,'showClassroom']);
    Route::post('showSection',[ScheduleController::class,'showSection']);
    Route::post('create_schedule',[ScheduleController::class,'create_schedule']);
    Route::get('showAvailableDelete/{professor}',[ScheduleController::class,'showAvailableDelete']);

    Route::get('professorSchedule/{professor}',[ScheduleController::class,'professorSchedule']);
    Route::get('sectionSchedule/{section}',[ScheduleController::class,'sectionSchedule']);
    Route::get('classroomSchedule/{classroom}',[ScheduleController::class,'classroomSchedule']);
    Route::get('delete_schedule/{id}',[ScheduleController::class,'delete_schedule']);
});

Route::group(['prefix'=>"public"],function(){
    Route::get('read_professor/{department}', [publicController::class, 'read_professor']);
    Route::post('read_professor_schedule', [publicController::class, 'read_professor_schedule']);

    Route::get('read_classroom/{department}',[publicController::class,'read_classroom']);
    Route::post('read_classroom_schedule',[publicController::class,'read_classroom_schedule']);

    Route::post('read_print',[publicController::class,'read_print']);

});