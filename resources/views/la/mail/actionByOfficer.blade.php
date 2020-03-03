
	<p>Dear {{ $pic }},</p>
	
	<p>{{$action_by}} has {{$status}} for the task - {{ '"' . $task_title . '"'}} @if($status == 'reassigned') from {{$old_pic_name}}@endif.

	<p>Please check it in our system.</p>

	<p>*** This mail has automatically sent from system. ***</p>
