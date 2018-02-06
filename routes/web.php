<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/checklistPerformance', 'ChecklistperformanceController@index');
Route::post('/search_personil_by_category', 'ChecklistperformanceController@search_personil_by_category');
Route::post('/search_type_task_by_dept', 'ChecklistperformanceController@search_type_task_by_dept');
// Route::post('/search_item_dijawab_by_bulan_groupBy', 'ChecklistperformanceController@search_item_dijawab_by_bulan_groupBy');
Route::post('/search_month_by_dpt', 'ChecklistperformanceController@search_month_by_dpt');
Route::post('/search_year_by_dptb', 'ChecklistperformanceController@search_year_by_dptb');

Route::post('/search_by_month', 'ChecklistperformanceController@search_by_month');