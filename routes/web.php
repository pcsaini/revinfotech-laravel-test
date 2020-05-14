<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (){
    return redirect()->route('students.index');
});

Route::prefix('students')->group(function () {
    Route::get('/', 'StudentController@index')->name('students.index');
    Route::get('/{id}/edit', 'StudentController@edit')->name('students.edit');
    Route::get('/{id}/view', 'StudentController@view')->name('students.view');
    Route::post('save', 'StudentController@save')->name('students.save');
    Route::get('create', 'StudentController@create')->name('students.create');
    Route::get('/{id}/delete', 'StudentController@delete')->name('students.delete');
});


Route::prefix('teachers')->group(function () {
    Route::get('/', 'TeacherController@index')->name('teachers.index');
    Route::get('/{id}/edit', 'TeacherController@edit')->name('teachers.edit');
    Route::get('/{id}/view', 'TeacherController@view')->name('teachers.view');
    Route::post('save', 'TeacherController@save')->name('teachers.save');
    Route::get('create', 'TeacherController@create')->name('teachers.create');
    Route::get('/{id}/delete', 'TeacherController@delete')->name('teachers.delete');
});
