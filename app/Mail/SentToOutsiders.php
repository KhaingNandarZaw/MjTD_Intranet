<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Dwij\Laraadmin\Helpers\LAHelper;
use Auth;

class SentToOutsiders extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    public $contents;
    public $files;
    public $cc_array;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contents, $files, $cc_array, $subject)
    {
        $this->contents = $contents;
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
        $contents = $this->contents;
        $files = $this->files;
        $subject = $this->subject;
        $cc_array = $this->cc_array;

        $email = $this->view('la.mail.sentToOutsiders')
                    ->with([ 'contents' => $contents]);

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
            
        return $email->from(Auth::user()->email, Auth::user()->name)->subject($subject)->replyTo(Auth::user()->email, Auth::user()->name);
    }
}
