<?php
/**
 * Migration generated using LaraAdmin
 * Help: http://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: http://dwijitsolutions.com
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Dwij\Laraadmin\Models\Module;

class CreateSopSetupsTable extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Sop_setups", 'sop_setups', 'work_description', 'fa-cube', [
            [
                "colname" => "active_status",
                "label" => "Status",
                "field_type" => "Integer",
                "unique" => false,
                "defaultvalue" => "1",
                "minlength" => 0,
                "maxlength" => 32,
                "required" => false,
                "listing_col" => false
            ], [
                "colname" => "created_by",
                "label" => "Created By",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false,
                "popup_vals" => "@users",
            ], [
                "colname" => "dayofweek",
                "label" => "Day Of Week",
                "field_type" => "Multiselect",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false,
                "popup_vals" => ["Monday","Tuesday","Wednesday","Thursday","Friday"],
            ], [
                "colname" => "monthly_type",
                "label" => "Monthly Type",
                "field_type" => "Radio",
                "unique" => false,
                "defaultvalue" => "Day Of Month",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false,
                "popup_vals" => ["Day Of Month","Day Of Week"],
            ], [
                "colname" => "day",
                "label" => "Day",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "1",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false,
                "popup_vals" => ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"],
            ], [
                "colname" => "week",
                "label" => "Week",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "First Week",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false,
                "popup_vals" => ["First Week","Second Week","Third Week","Last Week"],
            ], [
                "colname" => "every_interval",
                "label" => "Every Interval",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "1",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false,
                "popup_vals" => ["1","2","3","4","5","6","7","8","9","10"],
            ], [
                "colname" => "report_to_userid",
                "label" => "Report To",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false,
                "popup_vals" => "@users",
            ], [
                "colname" => "monthly_dayofweek",
                "label" => "Day Of Week",
                "field_type" => "Multiselect",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false,
                "popup_vals" => ["Monday","Tuesday","Wednesday","Thursday","Friday"],
            ], [
                "colname" => "start_date",
                "label" => "Start Date",
                "field_type" => "Date",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false
            ], [
                "colname" => "work_description",
                "label" => "Work Description",
                "field_type" => "String",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
                "required" => true,
                "listing_col" => true
            ], [
                "colname" => "job_type",
                "label" => "Job Type",
                "field_type" => "TextField",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
                "required" => false,
                "listing_col" => false
            ], [
                "colname" => "timeframe",
                "label" => "Time Frame",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => true,
                "popup_vals" => "@frames",
            ], [
                "colname" => "pic_userid",
                "label" => "PIC",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => false,
                "popup_vals" => "@users",
            ], [
                "colname" => "remark",
                "label" => "Remark",
                "field_type" => "Textarea",
                "unique" => false,
                "defaultvalue" => " ",
                "minlength" => 0,
                "maxlength" => 1000,
                "required" => false,
                "listing_col" => true
            ]
        ]);
        
        /*
        Module::generate("Module_Name", "Table_Name", "view_column_name" "Fields_Array");

        Field Format:
        [
            "colname" => "name",
            "label" => "Name",
            "field_type" => "Name",
            "unique" => false,
            "defaultvalue" => "John Doe",
            "minlength" => 5,
            "maxlength" => 100,
            "required" => true,
            "listing_col" => true,
            "popup_vals" => ["Employee", "Client"]
        ]
        # Format Details: Check http://laraadmin.com/docs/migrations_cruds#schema-ui-types
        
        colname: Database column name. lowercase, words concatenated by underscore (_)
        label: Label of Column e.g. Name, Cost, Is Public
        field_type: It defines type of Column in more General way.
        unique: Whether the column has unique values. Value in true / false
        defaultvalue: Default value for column.
        minlength: Minimum Length of value in integer.
        maxlength: Maximum Length of value in integer.
        required: Is this mandatory field in Add / Edit forms. Value in true / false
        listing_col: Is allowed to show in index page datatable.
        popup_vals: These are values for MultiSelect, TagInput and Radio Columns. Either connecting @tables or to list []
        */
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('sop_setups')) {
            Schema::drop('sop_setups');
        }
    }
}
