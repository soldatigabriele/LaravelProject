<?php
use Illuminate\Support\Facades\Mail;

//Route::get('/', function () { return view('welcome'); });
Route::get('/', 'DriveController@login');
Route::any('/home', 'DriveController@index');

Auth::routes();

Route::get('/admin/cpanel', 'AdminController@cpanel');
Route::post('/admin/project', 'AdminController@projectStore');
Route::delete('/admin/project', 'AdminController@projectDestroy');
Route::post('/admin/folder', 'AdminController@folderStore');
Route::delete('/admin/folder', 'AdminController@folderDestroy');
Route::post('/admin/user', 'AdminController@store');
Route::get('/admin/user/{id}', 'AdminController@show');
Route::delete('/admin/user/{id}', 'AdminController@deleteUser');
Route::get('/admin/user/{user_id}/project/{project_id}', 'AdminController@taskList');
Route::put('/abc', 'AdminController@updateVisibleTasklists');
Route::any('/updateall', 'AdminController@updateAll');

//Route::get('/admin/client-task/{task_id}', 'ClientTaskController@show');
Route::any('/test10', 'ClientTaskController@setTagDetails');
Route::post('/addtag', 'ClientTaskController@setTagDetails');
Route::get('/addtag', 'ClientTaskController@setTagDetails');
Route::post('/updatetag', 'ClientTaskController@updateTagDetails');
Route::get('/act', 'ClientTaskController@updateActiveOtherTask');
Route::put('/updatetag', 'ClientTaskController@updateVisibleTask');
Route::delete('/updatetag', 'ClientTaskController@deleteTag');
Route::get('/completetask', 'ClientTaskController@completeTask');

Route::any('/project/{project_id}', 'UserController@userTaskList');
Route::get('/registerotheremail', 'UserController@confirmOtherEmail');
Route::post('/registerotheremail', 'UserController@registerOtherEmail');
Route::post('/project/{project_id}/task/{task_id}', 'UserController@completedTask');
Route::delete('/project/{project_id}/task/{task_id}', 'UserController@uncompletedTask');
Route::put('/completeTask', 'UserController@completeClientTask');

//Google Auth
Route::get('auth/google', 'Auth\RegisterController@google');
Route::get('auth/google/callback', 'Auth\RegisterController@googleCallback');
// Google Drive Admin
Route::get('/admin/user/{user_id}/folder/{folder_id?}', 'AdminDriveController@getToken');
// Google Drive User
Route::post('project/{project_id}/user/{user_id}/folder/{folder_id?}', 'DriveController@drive');
Route::post('project/{project_id}/user/{user_id}/folder/{folder_id?}', 'DriveController@drive');
Route::get('project/{project_id}/user/{user_id}/folder/{folder_id?}', 'DriveController@drive');
Route::get('/google', 'DriveController@index');
Route::post('/upload', 'DriveController@doUpload');

// GoCardless
Route::get('/gocardless/show-customers', 'GoCardlessController@showCustomers');
Route::get('/gocardless/new-customer', 'GoCardlessController@setupNewCustomer');
Route::get('/gocardless/store-customer', 'GoCardlessController@storeCustomer');
Route::get('/gocardless/make-payment', 'GoCardlessController@confirmPayment');

//updates the tasklists visible to the user

//Route::any('/test','TestController@test');
//Route::any('/test0', 'TestController@test0');
//Route::any('/test1', 'TestController@test1');
//Route::any('/test2', 'TestController@test2');
//Route::any('/test3', 'TestController@test3');
//Route::any('/test4', 'TestController@test4');
//Route::any('/test5', 'TestController@test5');
Route::any('/test8', 'TestController@test8');
Route::any('/test9', 'TestController@test9');
Route::post('/updateNames', 'AdminController@updateNames');
//Route::get('/admin/control', ['middleware'=>'admin', function(){return view('admin.controlPanel');}]);

//Route::get('/mail','MailController@contact');


