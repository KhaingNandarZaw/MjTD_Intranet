
	<p>Dear {{ $reportTo }},</p>
	
	<p>{{$pic}} has completed for the task - {{ '"' . $task_title . '"'}} for the date {{$task_date}} and sent to you for your Approval.
	@if($task_remark)
	<p>Here is the remark : 
		<span>{{$task_remark}}</span>
	</p>
	@endif
	<p>Please check it in our <a href="http://150.95.24.198/">system</a>.</p>

	<p>*** This mail has automatically sent from system. ***</p>
