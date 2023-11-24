<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\MinorController;
use App\Http\Controllers\OfficialtimeController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\publicController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearLevelController;
use Illuminate\Http\Request;
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

Route::group(["prefix"=>'classrooms'], function(){
    Route::post('create',[ClassroomController::class, 'create']);
    Route::get('read/{tokens}',[ClassroomController::class, 'read']);
    Route::post('update',[ClassroomController::class, 'update']);
    Route::get('delete/{id}',[ClassroomController::class, 'delete']);
});

Route::group(['prefix'=>'subjects'],function(){
    Route::post('create',[SubjectController::class, 'create']);
    Route::get('read/{tokens}', [SubjectController::class , 'read'] );
    Route::post('update',[SubjectController::class, 'update']);
    Route::get('delete/{code}', [SubjectController::class, 'delete']);
});

Route::group(["prefix"=>"sections"], function(){
    Route::post('create',[SectionController::class, 'create']);
    Route::get('read/{tokens}', [SectionController::class , 'read']);
    Route::post('update', [SectionController::class,'update']);
    Route::get('delete/{id}',[SectionController::class, 'delete']);
});

Route::group(['prefix'=>'professors'], function(){
    Route::post('create',[ProfessorController::class , 'create']);
    Route::get('read/{tokens}',[ProfessorController::class , 'read']);
    Route::post('update',[ProfessorController::class , 'update']);
    Route::get('delete/{id}',[ProfessorController::class , 'delete']);
    Route::get('count/{tokens}',[ProfessorController::class , 'countProfessor']);
});

Route::group(['prefix'=>"loads"], function(){
    Route::post('create',[LoadController::class, 'create']);
    Route::get('read/{professor}',[LoadController::class, 'read']);
    Route::get('delete/{id}',[LoadController::class, 'delete']);
    Route::post('all-load',[LoadController::class,'show_subject_assigned']);
});

Route::group(['prefix'=>'officialtime'],function(){
    Route::post('create',[OfficialtimeController::class, 'create']);
    Route::post('read',[OfficialtimeController::class, 'read']);
    Route::post('update',[OfficialtimeController::class, 'update']);
    Route::get('delete/{id}',[OfficialtimeController::class, 'delete']);
    Route::post('showDelete',[OfficialtimeController::class, 'showDelete']);
});

Route::group(['prefix'=>'schedule'],function(){
    Route::post('professor', [ScheduleController::class , 'professorSchedule']);
    Route::post('classroom', [ScheduleController::class, 'classroomSchedule']);
    Route::post('section', [ScheduleController::class, 'sectionSchedule']);
    Route::post('create', [ScheduleController::class, 'createSchedule']);
    Route::post('showDelete', [ScheduleController::class, 'showDelete']);
    Route::get('delete/{id}', [ScheduleController::class, 'delete']);
    Route::get('submitSchedule', [ScheduleController::class, 'submitSchedule']);
    Route::post('myHour',  [ScheduleController::class, 'myTotalHours']);
    Route::get('approveSchedule/{tokens}',[ScheduleController::class, 'approveSchedule']);
    Route::post('professorLoad', [ScheduleController::class, 'professorLoad']);

    Route::post('automated',[ScheduleController::class, 'automated']);
    Route::post('reset',[ScheduleController::class, 'resetSchedule']);

    Route::post('notify-other-department',[ScheduleController::class,'notifyDeanOtherDepartment']);
    Route::post('notify-minor-to-dean',[ScheduleController::class, 'notifyMinorToDean']);
});

// Route::group(['prefix'=>'minor'], function(){
//     Route::get('department/{tokens}',[MinorController::class , 'departments']);
//     Route::post('classroom',[MinorController::class, 'allClassroom']);
//     Route::post('section',[MinorController::class, 'allSection']);
//     Route::get('yearlevel/{department}',[MinorController::class , 'showYearlevels']);
//     Route::get('showsubject/{department}',[MinorController::class , 'showSubjects']);
//     Route::post('showclassroom',[MinorController::class , 'showClassrooms']);
//     Route::get('showsection/{department}',[MinorController::class , 'showSections']);
//     Route::post('create-schedule',[MinorController::class, 'createSchedule']);
//     Route::post('showDelete',[MinorController::class, 'showDelete']);
//     Route::get('delete/{id}',[MinorController::class , 'delete']);
//     Route::get('submitSchedule',[MinorController::class , 'showSections']);
// });

Route::group(['prefix'=>"public"],function(){
    Route::get('showProfessor/{department}', [publicController::class, 'showProfessor']);
    Route::post('scheduleProfessor', [publicController::class, 'scheduleProfessor']);
    Route::post('printProfessor', [publicController::class, 'printProfessor']);

    Route::get('showClassroom/{department}', [publicController::class, 'showClassroom']);
    Route::post('scheduleClassroom', [publicController::class, 'scheduleClassroom']);
    Route::post('printClassroom', [publicController::class, 'printClassroom']);

    Route::get('showSection/{department}', [publicController::class, 'showSection']);
    Route::post('scheduleSection', [publicController::class, 'scheduleSection']);
    Route::post('printSection', [publicController::class, 'printSection']);
    # inside dean schedule
    Route::post('deanProfessor',[publicController::class, 'deanProfessorSchedule']);
    Route::post('deanSection',[publicController::class, 'deanSectionSchedule']);
    Route::post('deanClassroom',[publicController::class, 'deanClassroomSchedule']);
    Route::get('deanlast/{tokens}',[publicController::class, 'deanLastSchedule']);
    Route::get('deanlastDepartment/{tokens}',[publicController::class, 'deanLastScheduleDepartment']);
});