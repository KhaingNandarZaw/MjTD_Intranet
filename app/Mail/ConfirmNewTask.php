<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Dwij\Laraadmin\Helpers\LAHelper;

class ConfirmNewTask extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    public $task_title;
    public $pic;
    public $reportTo;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task_title, $pic, $reportTo, $subject)
    {
        $this->task_title = $task_title;
        $this->pic = $pic;
        $this->reportTo = $reportTo;
        $this->subject = $subject;
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
        $reportTo = $this->reportTo;
        $subject = $this->subject;

        $email = $this->view('la.mail.confirmNewTask')
                    ->with([ 'task_title' => $task_title, 'pic' => $pic, 'reportTo' => $reportTo])
                    ->subject($subject);

        return $email;
    }
}
