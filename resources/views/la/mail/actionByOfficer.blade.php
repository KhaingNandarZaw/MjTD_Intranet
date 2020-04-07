
	<p>Dear {{ $pic }},</p>
	
	<p>{{$action_by}} has {{$status}} for the task - {{ '"' . $task_title . '"'}} @if($status == 'reassigned') from {{$old_pic_name}}@endif.

	<p>Please check it in our <a href="http://150.95.24.198/">system</a>.</p>

	<p>*** This mail has automatically sent from system. ***</p>
