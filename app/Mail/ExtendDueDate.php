<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Dwij\Laraadmin\Helpers\LAHelper;

class ExtendDueDate extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    public $task_title;
    public $pic;
    public $action_by;
    public $subject;
    public $task_date;
    public $extend_due_date;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task_title, $pic, $action_by, $subject, $task_date, $extend_due_date)
    {
        $this->task_title = $task_title;
        $this->pic = $pic;
        $this->action_by = $action_by;
        $this->subject = $subject;
        $this->task_date = $task_date;
        $this->extend_due_date = $extend_due_date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $task_title = $this->task_title;
        $pic = $this->pic;
        $action_by = $this->action_by;
        $subject = $this->subject;
        $task_date = $this->task_date;
        $extend_due_date = $this->extend_due_date;

        $email = $this->view('la.mail.extendDueDate')
                    ->with([ 'task_title' => $task_title, 'pic' => $pic, 'action_by' => $action_by, 'from_date' => $task_date, 'to_date' => $extend_due_date])
                    ->subject($subject);

        return $email;
    }
}
