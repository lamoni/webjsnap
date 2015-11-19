<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect('/snap');
});

Route::get('/snap', function () {
    return view('snap');
});

Route::post('/snap/snapshot', 'SnapController@snapshot');

Route::get('/compare', function () {
    return view('compare');
});

Route::get('/check', function () {
    return view('check');
});

/**
 * @todo: The file_get_contents call below is absolutely ugly but I need to redo the JSnapCommander class due to sloppy use of die()
 */
Route::get('/manage', function () {
    return view('manage', ['snapshots' => json_decode(file_get_contents('http://127.0.0.1/manage/snapshots'), true)]);
});

Route::get('/manage/snapshots', function (\Lamoni\JSnapCommander\JSnapCommander $jsnap) {
    return ['error' => 0, 'snapshots' => $jsnap->getAllSnapshots()];
});

Route::get('/settings', function () {
    return view('settings', ['config' => \Config::get('jsnap')]);
});

Route::post('/compare/presnaps', 'CompareController@presnaps');

Route::post('/compare/postsnaps', 'CompareController@postsnaps');

Route::post('/compare/compare', 'CompareController@compare');

Route::post('/check/check', 'CheckController@check');

Route::post('/manage/delete', 'ManageController@delete');

Route::post('/settings/save', 'SettingsController@save');