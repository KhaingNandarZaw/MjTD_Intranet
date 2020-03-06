<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnnouncementsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    public $task_title;
    public $description;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task_title, $description, $subject)
    {
        $this->task_title = $task_title;
        $this->description = $description;
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
        $description = $this->description;
        $subject = $this->subject;

        $email = $this->view('la.announcements.mail')
                    ->with([ 'task_title' => $task_title, 'description' => $description])
                    ->subject($subject);

        return $email;

    }
}
