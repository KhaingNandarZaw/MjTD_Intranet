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

class CreateTaskPicsTable extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Task_pics", 'task_pics', 'task_id', 'fa-cube', [
            [
                "colname" => "task_id",
                "label" => "Task",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => false,
                "popup_vals" => "@tasks",
            ], [
                "colname" => "pic_userid",
                "label" => "PIC",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false,
                "popup_vals" => "@users",
            ], [
                "colname" => "description",
                "label" => "Description",
                "field_type" => "Textarea",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false
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
        if(Schema::hasTable('task_pics')) {
            Schema::drop('task_pics');
        }
    }
}
