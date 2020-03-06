<?php

use Dwij\Laraadmin\Helpers\LAHelper;

/* ================== Homepage ================== */
Route::get('/', 'HomeController@index');
Route::auth();

/* ================== Access Uploaded Files ================== */
Route::get('files/{hash}/{name}', 'LA\UploadsController@get_file');
Route::get('workflowfiles/{hash}/{name}', 'LA\UploadsController@get_workflowFile');
Route::get('manualfiles/{hash}/{name}', 'LA\UploadsController@get_manualFile');
Route::get('task_attachments/{hash}/{name}', 'LA\UploadsController@get_task_attachments');
/*
|--------------------------------------------------------------------------
| Admin Application Routes
|--------------------------------------------------------------------------
*/

$as = "";
if(LAHelper::laravel_ver() == 5.3 || LAHelper::laravel_ver() == 5.4) {
	$as = config('laraadmin.adminRoute').'.';
	
	// Routes for Laravel 5.3
	Route::get('/logout', 'Auth\LoginController@logout');
}

Route::group(['as' => $as, 'middleware' => ['auth', 'permission:ADMIN_PANEL']], function () {
	
	/* ================== Dashboard ================== */
	
	Route::get(config('laraadmin.adminRoute'), 'LA\DashboardController@index');
	Route::get(config('laraadmin.adminRoute'). '/dashboard', 'LA\DashboardController@index');
	
	/* ================== Users ================== */
	Route::resource(config('laraadmin.adminRoute') . '/users', 'LA\UsersController');
	Route::get(config('laraadmin.adminRoute') . '/user_dt_ajax', 'LA\UsersController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/change_password/{id}', 'LA\UsersController@change_password');
	
	/* ================== Uploads ================== */
	Route::resource(config('laraadmin.adminRoute') . '/uploads', 'LA\UploadsController');
	Route::post(config('laraadmin.adminRoute') . '/upload_files', 'LA\UploadsController@upload_files');
	Route::post(config('laraadmin.adminRoute') . '/upload_task_files', 'LA\UploadsController@upload_task_files');
	Route::post(config('laraadmin.adminRoute') . '/upload_manualfiles', 'LA\UploadsController@upload_ManualFiles');
	Route::post(config('laraadmin.adminRoute') . '/upload_workflowfiles', 'LA\UploadsController@upload_WorkflowFiles');
	Route::get(config('laraadmin.adminRoute') . '/uploaded_files', 'LA\UploadsController@uploaded_files');
	Route::post(config('laraadmin.adminRoute') . '/uploaded_files_byid', 'LA\UploadsController@uploaded_files_byid');
	Route::post(config('laraadmin.adminRoute') . '/uploaded_task_attachments', 'LA\UploadsController@uploaded_task_attachments');
	Route::post(config('laraadmin.adminRoute') . '/uploaded_flowchartFiles', 'LA\UploadsController@uploaded_flowchartFiles');
	Route::post(config('laraadmin.adminRoute') . '/uploaded_manualFiles', 'LA\UploadsController@uploaded_manualFiles');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_caption', 'LA\UploadsController@update_caption');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_filename', 'LA\UploadsController@update_filename');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_public', 'LA\UploadsController@update_public');
	Route::post(config('laraadmin.adminRoute') . '/uploads_delete_file', 'LA\UploadsController@delete_file');
	Route::post(config('laraadmin.adminRoute') . '/delete_manualfiles', 'LA\UploadsController@delete_manualfiles');
	Route::post(config('laraadmin.adminRoute') . '/delete_flowchartfiles', 'LA\UploadsController@delete_flowchartfiles');
	Route::post(config('laraadmin.adminRoute') . '/delete_task_attachment', 'LA\UploadsController@delete_task_attachment');
	
	/* ================== Roles ================== */
	Route::resource(config('laraadmin.adminRoute') . '/roles', 'LA\RolesController');
	Route::get(config('laraadmin.adminRoute') . '/role_dt_ajax', 'LA\RolesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_module_role_permissions/{id}', 'LA\RolesController@save_module_role_permissions');
	
	/* ================== Permissions ================== */
	Route::resource(config('laraadmin.adminRoute') . '/permissions', 'LA\PermissionsController');
	Route::get(config('laraadmin.adminRoute') . '/permission_dt_ajax', 'LA\PermissionsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_permissions/{id}', 'LA\PermissionsController@save_permissions');
	
	/* ================== Departments ================== */
	Route::resource(config('laraadmin.adminRoute') . '/departments', 'LA\DepartmentsController');
	Route::get(config('laraadmin.adminRoute') . '/department_dt_ajax', 'LA\DepartmentsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/departments/update_hierarchy', 'LA\DepartmentsController@update_hierarchy');
	Route::post(config('laraadmin.adminRoute') . '/departments/department_users', 'LA\DepartmentsController@department_users');
	
	/* ================== Backups ================== */
	Route::resource(config('laraadmin.adminRoute') . '/backups', 'LA\BackupsController');
	Route::get(config('laraadmin.adminRoute') . '/backup_dt_ajax', 'LA\BackupsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/create_backup_ajax', 'LA\BackupsController@create_backup_ajax');
	Route::get(config('laraadmin.adminRoute') . '/downloadBackup/{id}', 'LA\BackupsController@downloadBackup');

    /* ================== SOP_Management_Types ================== */
    Route::resource(config('laraadmin.adminRoute') . '/sop_management_types', 'LA\SOP_Management_TypesController');
    Route::get(config('laraadmin.adminRoute') . '/sop_management_type_dt_ajax', 'LA\SOP_Management_TypesController@dtajax');

    /* ================== Frames ================== */
    Route::resource(config('laraadmin.adminRoute') . '/frames', 'LA\FramesController');
    Route::get(config('laraadmin.adminRoute') . '/frame_dt_ajax', 'LA\FramesController@dtajax');

	Route::resource(config('laraadmin.adminRoute') . '/admintheme', 'AdminthemeController@index');
	
	/* ================== ViewController ================== */
	Route::get(config('laraadmin.adminRoute') . '/task_management/{method}', 'LA\ViewController@task_management');

    /* ================== SOP_Setups ================== */
    Route::resource(config('laraadmin.adminRoute') . '/sop_setups', 'LA\SOP_SetupsController');
	Route::post(config('laraadmin.adminRoute') . '/sop_setup_dt_ajax', 'LA\SOP_SetupsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/sop_data_by_pic', 'LA\SOP_SetupsController@getSOPDataByPIC');
	Route::get(config('laraadmin.adminRoute') . '/my_sops', 'LA\SOP_SetupsController@getSOPList');
	Route::post(config('laraadmin.adminRoute') .'/filter', 'LA\SOP_SetupsController@filter');
	
    /* ================== SOP_Manual_Uploads ================== */
    Route::resource(config('laraadmin.adminRoute') . '/sop_manual_uploads', 'LA\SOP_Manual_UploadsController');
    Route::get(config('laraadmin.adminRoute') . '/sop_manual_upload_dt_ajax', 'LA\SOP_Manual_UploadsController@dtajax');

    /* ================== SOP_Flowchart_Uploads ================== */
    Route::resource(config('laraadmin.adminRoute') . '/sop_flowchart_uploads', 'LA\SOP_Flowchart_UploadsController');
	Route::get(config('laraadmin.adminRoute') . '/sop_flowchart_upload_dt_ajax', 'LA\SOP_Flowchart_UploadsController@dtajax');
	
	/*=================== Announcement ===============*/
	Route::resource(config('laraadmin.adminRoute') . '/announcements', 'AnnouncementController');

	/*=================== SopExcel ===============*/
	Route::get('export', 'SopExcelController@export')->name('export');
	Route::post('import', 'SopExcelController@import')->name('import');
	Route::post(config('laraadmin.adminRoute') . '/check', 'SopExcelController@checkUser');

	Route::get(config('laraadmin.adminRoute').'/sopexcel','SopExcelController@importExportView');



    /* ================== Vendor_Registrations ================== */
    Route::resource(config('laraadmin.adminRoute') . '/vendor_registrations', 'LA\Vendor_RegistrationsController');
    Route::get(config('laraadmin.adminRoute') . '/vendor_registration_dt_ajax', 'LA\Vendor_RegistrationsController@dtajax');

    /* ================== Announcements ================== */
    Route::resource(config('laraadmin.adminRoute') . '/announcements', 'LA\AnnouncementsController');
    Route::get(config('laraadmin.adminRoute') . '/announcement_dt_ajax', 'LA\AnnouncementsController@dtajax');

    /* ================== Tasks ================== */
    Route::resource(config('laraadmin.adminRoute') . '/tasks', 'LA\TasksController');
	Route::get(config('laraadmin.adminRoute') . '/task_dt_ajax', 'LA\TasksController@dtajax');


    /* ================== Task_Instances ================== */
    Route::resource(config('laraadmin.adminRoute') . '/task_instances', 'LA\Task_InstancesController');
	Route::get(config('laraadmin.adminRoute') . '/task_instance_dt_ajax', 'LA\Task_InstancesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/sent_to_officer', 'LA\Task_InstancesController@sent_to_officer');
	Route::post(config('laraadmin.adminRoute') . '/report_to_officer', 'LA\Task_InstancesController@report_to_officer');
	Route::post(config('laraadmin.adminRoute') . 'extend_duedate', 'LA\Task_InstancesController@extend_duedate');
	Route::match(['get', 'post'], config('laraadmin.adminRoute') . '/task_checking', 'LA\Task_InstancesController@task_checking');
	Route::post(config('laraadmin.adminRoute') . '/approved_by_officer', 'LA\Task_InstancesController@approved_by_officer');
	Route::post(config('laraadmin.adminRoute') . '/cancel_task', 'LA\Task_InstancesController@cancel_task');
	Route::post(config('laraadmin.adminRoute') . '/reassign_pic', 'LA\Task_InstancesController@reassign_pic');
	Route::post(config('laraadmin.adminRoute') . '/rejected_by_officer', 'LA\Task_InstancesController@rejected_by_officer');
	Route::match(['get', 'post'], config('laraadmin.adminRoute') . '/my_tasks', 'LA\Task_InstancesController@my_tasks');
	Route::get('attached_files/{hash}/{name}', 'LA\Task_InstancesController@getAttachedFiles');

    /* ================== Create_New_Tasks ================== */
    Route::resource(config('laraadmin.adminRoute') . '/create_new_tasks', 'LA\Create_New_TasksController');
	Route::get(config('laraadmin.adminRoute') . '/create_new_task_dt_ajax', 'LA\Create_New_TasksController@dtajax');
	Route::get(config('laraadmin.adminRoute') .'/create_new_tasks/{id}/cancel', 'LA\Create_New_TasksController@cancel');
	Route::get(config('laraadmin.adminRoute') .'/confirm_new_tasks', 'LA\ConfirmNewTaskController@index');
	Route::post(config('laraadmin.adminRoute') .'/confirm_new_task', 'LA\ConfirmNewTaskController@confirm');
	Route::post(config('laraadmin.adminRoute') .'/reject_new_task', 'LA\ConfirmNewTaskController@reject');

	Route::get('fullcalendar','LA\Task_InstancesController@fullcalendar');
	Route::match(['get', 'post'], 'all_calendar','LA\Task_InstancesController@all_calendar');
	Route::post('fullcalendar/getData', 'LA\Task_InstancesController@get_taskdata_forcalendar');
	Route::post('fullcalendar_bytask', 'LA\Task_InstancesController@fullcalendar_bytask');

	/* ================== ReportController ================== */
	Route::match(['get', 'post'], config('laraadmin.adminRoute') . '/reports/evaluation_report', 'LA\ReportController@evaluation_report');
	Route::match(['get', 'post'], config('laraadmin.adminRoute') . '/reports/detail_evaluation_report', 'LA\ReportController@detail_evaluation_report');

});
