<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Dwij\Laraadmin\Helpers\LAHelper;

class CreateNewTask extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    public $task_title;
    public $pic;
    public $reportTo;
    public $subject;
    public $cc_array;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task_title, $pic, $reportTo, $subject, $cc_array)
    {
        $this->task_title = $task_title;
        $this->pic = $pic;
        $this->reportTo = $reportTo;
        $this->subject = $subject;
        $this->cc_array = $cc_array;
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

        $email = $this->view('la.mail.createNewTask')
                    ->with([ 'task_title' => $task_title, 'pic' => $pic, 'reportTo' => $reportTo])
                    ->subject($subject);
        if(isset($cc_array))
            $email->cc($cc_array);
        return $email;
    }
}
