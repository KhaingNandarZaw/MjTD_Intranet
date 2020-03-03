<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Dwij\Laraadmin\Helpers\LAHelper;

class SentToOfficer extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    public $task_title;
    public $pic;
    public $reportTo;
    public $files;
    public $cc_array;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task_title, $pic, $reportTo, $files, $cc_array, $subject)
    {
        $this->task_title = $task_title;
        $this->pic = $pic;
        $this->reportTo = $reportTo;
        $this->files = $files;
        $this->cc_array = $cc_array;
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
        $files = $this->files;
        $subject = $this->subject;
        $cc_array = $this->cc_array;

        $email = $this->view('la.mail.sentToOfficer')
                    ->with([ 'task_title' => $task_title, 'pic' => $pic, 'reportTo' => $reportTo]);

        if($files != null){
            foreach($files as $item){
            
                $email->attach($item->getRealPath(),
                [
                    'as' => $item->getClientOriginalName(),
                    'mime' => $item->getClientMimeType(),
                ]);
            }
        }
        if(isset($cc_array))
            $email->cc($cc_array);
            
        return $email->subject($subject);
    }
}
