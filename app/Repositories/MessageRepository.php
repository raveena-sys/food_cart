<?php

namespace App\Repositories;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Models\User;
use App\Models\Company;
use App\Models\Manager;
use App\Models\Employee;
use App\Models\Messages;
use App\Models\Media;
use App\Models\Threads;
use App\Models\UserAvailability;
use File;
use DB;
use Illuminate\Support\Facades\Auth;

//use Your Model

/**
 * Class UserRepository.
 */
class MessageRepository
{

    /**
     * @return string
     *  Return the model
     */
    private $user, $company, $manager, $employee, $userAvailability, $message, $threads, $media;

    public function __construct(User $user, Company $company, Manager $manager, Employee $employee, UserAvailability $userAvailability, Messages $message, Threads $threads, Media $media)
    {
        $this->user = $user;
        $this->media = $media;
        $this->company = $company;
        $this->manager = $manager;
        $this->employee = $employee;
        $this->message = $message;
        $this->threads = $threads;
        $this->userAvailability = $userAvailability;
    }

    public function  getChatUsers($post)
    {

        $userId = Auth::user()->id;

        // $data = DB::select("select * from ((select *, sender as userId from messages where receiver = 76 group by sender) UNION (select *, receiver as userId from messages where sender = 76 group by receiver )) as t3 group by userId");
        // print_r( $data );die;
        $q = " WHERE users.user_type  = '".$post['userRole']."'";
        if (!empty($post['serachkey'])) {
            $key = $post['serachkey'];
            $q .= " AND users.name like ('%$key%')";
        }
        $query = " SELECT *, (SELECT `message` FROM `messages` WHERE `sender` = `x`.`chatUser` OR `receiver` = `x`.`chatUser` ORDER BY `id` DESC LIMIT 1) as lastMessage,(SELECT `created_at` FROM `messages` WHERE `sender` = `x`.`chatUser` OR `receiver` = `x`.`chatUser` ORDER BY `created_at` DESC LIMIT 1) as lastTime FROM (SELECT max(messages.id), max(messages.created_at), (CASE WHEN receiver = '$userId' THEN sender ELSE receiver END) AS chatUser  "
            . " FROM `messages`"
            . " where (messages.receiver='$userId' OR messages.sender='$userId' )"
            . " GROUP BY chatUser) AS x INNER JOIN users ON users.id=x.chatUser  $q ORDER BY created_at DESC ";
            $relatedPosts = DB::select($query);
        return $relatedPosts;

        // return $relatedPosts->paginate(5);


    }
    public function getUserById($id)
    {

        $list = $this->user->where('id', $id)->first();
        return $list;
    }
    public static function getUser($id)
    {

        $list = User::where('id', $id)->first();
        return $list;
    }
    /**
     * Get Chat Message list
     */
    public static function getChatMessageList($data)
    {
        $send_to = $data['toUser'];
        $send_from = $data['fromUser'];
        $query = " select * from messages WHERE (receiver = " . $send_to . " OR sender =" . $send_to . ") AND "
            . " (receiver =" . $send_from . " OR sender = " . $send_from . ")"
            . " ORDER BY created_at ASC";
        $relatedPosts = DB::select($query);
        $post = array();
        $post['from'] = $send_from;

        return $relatedPosts;
    }
    /**
     * Get Chat Message count
     */
    public static function getChatMessageCount($data)
    {
        $send_to = $data['toUser'];
        $send_from = $data['fromUser'];
        $query = " select * from messages WHERE (receiver = " . $send_to . " OR sender =" . $send_to . ") AND "
            . " (receiver =" . $send_from . " OR sender = " . $send_from . ")"
            . " ORDER BY created_at ASC";
        $relatedPosts = DB::select($query);
        $post = array();
        $post['from'] = $send_from;

        return $relatedPosts;
    }



    public  function readMessages($post)
    {
        $result = $this->message->where('receiver', $post['from'])
            ->update(['receiver_read' => 1]);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Savechat Initiate
     */
    public  function chatInitiate($request)
    {
        $threadData['is_blocked'] = 0;
        $threadDetail = $this->threads->create($threadData);
        $messageData['thread_id'] = $threadDetail->id;
        $messageData['job_id'] = !empty($request['job_id']) ? $request['job_id'] : null;
        $messageData['sender'] = Auth::user()->id;
        $messageData['receiver'] = $request['id'];;
        $messageData['message'] = 'hi';
        $messageData =  $this->message->create($messageData);
        return $messageData;
    }
    /**
     * Save Chat Message
     */
    public  function saveChatMessages($request)
    {

        $msg['job_id'] = !empty($request['job_id']) ? $request['job_id'] : null;
        $msg['thread_id'] = $request['thread_id'];
        $msg['sender'] =  isset($request['sender_id']) ? $request['sender_id'] : Auth::user()->id;
        $msg['receiver'] = $request['toUser'];
        $msg['message'] = $request['message'];

        $model =  $this->message->create($msg);

        return $model;
    }
    /**
     * Save Chat Doc
     */
    public  function saveChatdoc($request)
    {

        $msg['job_id'] = !empty($request['job_id']) ? $request['job_id'] : null;
        $msg['thread_id'] = $request['thread_id'];
        $msg['sender'] =  isset($request['sender_id']) ? $request['sender_id'] : Auth::user()->id;
        $msg['receiver'] = $request['toUser'];
        $msg['media'] = $request['file'];

        $model =  $this->message->create($msg);
        $this->media->where('name', $request['file'])->where('media_for', 'message')->update(['status' => 'used']);
        return $model;
    }

    /**
     * get Unread Count
     */
    public static function getUnreadmessges() {
        $userId = Auth::user()->id;
        $query = " select * from messages WHERE (receiver = " . $userId . " OR sender =" . $userId . ") AND "
            . " (receiver =" . $userId . ")"
            . " AND (messages.receiver_read = 0)"
            . " ORDER BY created_at ASC";

        $unread = DB::select($query);
        return $unread;
    }

    /**
     * get Unread Count for socket
     */
    public function getUnreadmessgesCount($userId) {
        return $this->message->where(function ($query) use($userId) {
                $query->where('receiver', '=',$userId)
                    ->orWhere('sender', '=', $userId);
            })->where(function ($query) use($userId) {
                $query->where(['receiver'=>$userId,'receiver_read'=>0]);
            })->count();
    }

    /**
     * save online offline
     */

    public function setProfileStatus($post) {
        $model =Self::getUserById($post['user_id']);
        if (!empty($model)) {
            $model->online_offline = $post['status'];
            if ($model->save()) {
                return $model;
            }
        }
        return false;
    }
}
