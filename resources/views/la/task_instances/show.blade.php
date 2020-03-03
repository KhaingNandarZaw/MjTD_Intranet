@extends('la.layouts.app')

@section('htmlheader_title')
    Task Instance View
@endsection


@section('main-content')
<div id="page-content" class="profile2">
    
    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
        <li class=""><a href="javascript:history.back()" data-toggle="tooltip" data-placement="right" title="Back to Task Instances"><i class="fa fa-chevron-left"></i></a></li>
        <li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="tab-info">
            <div class="tab-content">
                <div class="panel infolist">
                    <div class="panel-default panel-heading">
                        <h4>General Info</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Task Name :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->name}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Main Description :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->MainDescription}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Task Description :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->TaskDescription}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Time Frame :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->time_frame}}</div>
                        </div>
                        <div class="form-group">
                            <?php
                                $dt = strtotime($task_instance->task_date);
                                $value = date("d M Y", $dt);
                            ?>
                            <label class="col-md-4 col-sm-6 col-xs-6">To Finish Date :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$value}}</div>
                        </div>
                    </div>
                </div>
            </div>
            @if(count($task_remarks) > 0)
            <div class="tab-content">
                <div class="panel infolist">
                    <div class="panel-default panel-heading">
                        <h4>Approval History</h4>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Finished Date</th>
                                    <th>Remarks</th>
                                    <th>Attached Files</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($task_remarks as $key=>$task_remark)
                                <tr>
                                    <td>{{ $task_remark->name }}</td>
                                    <td><span class="label  {{ (($task_remark->status=='On Progress') ? 'label-warning' : (($task_remark->status=='Rejected') ? 'label-danger' : (($task_remark->status == 'Approved') ? 'label-success' : (($task_remark->status == 'Done') ? 'label-primary' : 'label-default')))) }}">{{$task_remark->status}}</span></td>
                                    <?php 
                                        $dt = strtotime($task_instance->done_date);
                                        $value = date("d M Y, h:i A", $dt);
                                    ?>
                                    <td>{{ $value }}</td>
                                    <td>{{ $task_remark->remark }}</td>
                                    <td>
                                        <ol>
                                            <?php $task_files = DB::table('task_files')->select('id', 'filename', 'extension', 'hash')->where("task_remark_id", $task_remark->id)->where('task_instance_id', $task_instance->id)->get(); ?>
                                            @foreach($task_files as $task_file)
                                             <li><a target='_blank' href="{{ url('attached_files/'. $task_file->hash .'/'. $task_file->filename) }}" >{{$task_file->filename}}</a></li><!-- href= '". bsurl . /attached_files/'. $task_file->hash . / . $task_file->filename . "' -->
                                            @endforeach
                                        </ol>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
    </div>
</div>
@endsection
