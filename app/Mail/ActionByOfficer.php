<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Dwij\Laraadmin\Helpers\LAHelper;

class ActionByOfficer extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    public $task_title;
    public $pic;
    public $action_by;
    public $subject;
    public $status;
    public $old_pic_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task_title, $pic, $action_by, $subject, $status, $old_pic_name = null)
    {
        $this->task_title = $task_title;
        $this->pic = $pic;
        $this->action_by = $action_by;
        $this->subject = $subject;
        $this->status = $status;
        $this->old_pic_name = $old_pic_name;
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
        $status = $this->status;
        $old_pic_name = $this->old_pic_name;

        $email = $this->view('la.mail.actionByOfficer')
                    ->with([ 'task_title' => $task_title, 'pic' => $pic, 'action_by' => $action_by, 'old_pic_name' => $old_pic_name])
                    ->subject($subject);

        return $email;
    }
}
