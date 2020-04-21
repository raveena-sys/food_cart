<?php
namespace App\Common\Helpers;
use Illuminate\Support\Facades\Mail;
use App\Common\Model\MailQueue;
class MailHelper
{
	public static function sendMail($view, $to, $subject, $data = null)
	{
		Mail::send($view, ['user'=>$data], function ($message) use($to, $subject) {
            $message->to($to)
                    ->subject($subject);
        });
	}

	public static function addQueue($to,$type,$data,$mail_queue_id=null){

		$queue =  new MailQueue;
		$queue->to= $to;
        $queue->type= $type;
        $queue->mail_queue_id= $mail_queue_id;
		$queue->data= json_encode($data);
		$queue->save();
	}
}
