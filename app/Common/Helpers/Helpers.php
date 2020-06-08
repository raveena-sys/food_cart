<?php

/**
 * Description: this helper file is used only for common function related operations.
 * Author : [myUser ].
 * Date : february 2019.
 */

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

/**
 * function using for send email
 * @param type $data
 * @return boolean
 */
function sendMail($data)
{
    try {
        switch ($data['request']) {
            case "admin_forgot_password":
                Mail::send('emails.admin_forgot_password', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject']);
                });
                break;
            case "add_company":
                Mail::send('emails.add_company', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject']);
                });

                break;
            case "add_Employee":
                Mail::send('emails.add_employee', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject']);
                });
                break;
            case "add_manager":
                Mail::send('emails.add_manager', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject']);
                });
                break;
            case "forgot_password":
                Mail::send('emails.forgot_password', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject']);
                });

                break;
            case "welcome_email":
                Mail::send('emails.welcome_email', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject']);
                });

                break;
            case "invite_appraiser":
                Mail::send('emails.invite_appraiser', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject']);
                });

                break;
            case "order_email":

                Mail::send('emails.order_email', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject'])
                        ->attachData($data['attachment']->output(), $data['pdf_name']);
                });
                Mail::send('emails.store_order_email', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['store_email'])
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject'])
                        ->attachData($data['attachment']->output(), $data['pdf_name']);
                });
                break;
            case "contact_us":
                Mail::send('emails.thankyou', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject']);
                });
                Mail::send('emails.contact_us', ['data' => $data], function ($message) use ($data) {
                    $message->to('info@freefoodcart.com')
                        ->from(env('FROM_EMAIL'), 'FoodCart')
                        ->subject($data['subject']);
                });
                break;
            default:
                return false;
                break;
        }
        return true;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

/**
 * Function for list loader
 */
function ajaxTableListLoader()
{
    echo '<tr><td class="listloader text-center" colspan="20"><i class="icon-refresh spinner"></i></td></tr>';
}

/**
 * Function for list loader
 */
function ajaxListLoader()
{
    echo '<div class="listloader text-center"><i class="icon-refresh spinner"></i></div>';
}

/**
 * Function for list loader
 */
function adminListLoader()
{
    $image = url('public/images/loader.svg');
    echo "<div class='listloader text-center'><img class='icon spinner' src='" . $image . "' alt='loader'></div>";
}
/**
 * Function for button loader
 */
function buttonLoader()
{
    echo '<i class="icon-refresh spinner"></i>';
}

/**
 * function using for get full name
 * @param type $firstName and $lastName(array of object)
 * @return string
 */
function getFullName($firstName, $lastName)
{
    return ucfirst($firstName) . ' ' . ucfirst($lastName);
}

/**
 * show created date format from database like d-m-Y
 * @param type $date
 * @return string
 */
function showDateFormat($date)
{
    $dateFormat = getSetting('date_format');
    return date($dateFormat, strtotime($date));
}

/**
 * for date time 25 Feb 2019 10:58 AM
 * @param type $string
 * @return string
 */
function datetimeFormat($string)
{
    return Carbon::parse($string)->format('d/m/Y h:i A');
}

function getDateOnly($date)
{
    return str_replace('-', '/', date('m-d-Y', strtotime($date)));
}

function stingDateFormat($string)
{
    return Carbon::parse($string)->format('M d, Y');
}
function rightSideBarDateFormat($string)
{
    return Carbon::parse($string)->format('d M Y');
}
function rightSideBarDateOnly($string)
{
    return Carbon::parse($string)->format('d');
}

function stingTimeFormat($string)
{
    return Carbon::parse($string)->format('h:i A');
}

/**
 * for date format Thursday, Jan 03rd 2019
 * @param type $date
 * @return string
 */
function showDateTimeFormat($date)
{
    return date('l M j<\s\up>S</\s\up> Y', strtotime($date));
}

/**
 * for date format 2019-03-01 10:41:16
 * @param type $string
 * @return string
 */

/**
 * for start and end date format
 * @param type $start and $end
 * @return string
 */
function startEndDateFormat($start, $end)
{
    $startDate = date('M d, Y', strtotime($start));
    $endDate = date('M d, Y', strtotime($end));
    return $startDate . ' - ' . $endDate;
}

/**
 * chat listing time conversion function
 * @param type $created_at
 * @return string
 */
function getTimeAgo($created_at)
{
    $full = false;
    $now = new Carbon(date('Y-m-d H:i:s')); // for using date format
    $ago = new Carbon($created_at);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full)
        $string = array_slice($string, 0, 1);
    echo $string ? implode(', ', $string) . ' ago' : 'just now';
}

/**
 * Check image url and set image path
 * @param type $image and $folder
 * @return src path
 */
function getUserImage($image, $folder = null)
{
    $src = url('public/backend/images/user-default-image.png');
    $fileName = public_path() . '/uploads/' . $folder . '/' . $image;
    if (!empty($image) && file_exists($fileName)) {
        $src = url('public/uploads/' . $folder . '/' . $image);
    }
    return $src;
}

function geJobImage($image, $folder = null)
{
    $src = url('public/backend/images/default-property-image.jpg');
    $fileName = public_path() . '/uploads/' . $folder . '/' . $image;
    if (!empty($image) && file_exists($fileName)) {
        $src = url('public/uploads/' . $folder . '/' . $image);
    }
    return $src;
}

function geJobThumbImage($image, $folder = null)
{
    $src = url('public/backend/images/default-property-image.jpg');
    $fileName = public_path() . '/uploads/' . $folder . '/thumb/' . $image;
    if (!empty($image) && file_exists($fileName)) {
        $src = url('public/uploads/' . $folder . '/thumb/' . $image);
    }
    return $src;
}

/**
 * Get user data
 * @param type $id and $column
 * @return array of object
 */
function getUserData($id, $column)
{
    $data = User::where('id', $id)->first([$column]);
    if ($data) {
        return $data->$column;
    } else {
        return [];
    }
}

/**
 * get mentors availability by user id and date
 * @param type $userId
 * @param string $date
 * @return type object
 */
function getMentorAvailability($userId, $date)
{
    $date = "'" . $date . "'";
    $getData = Availability::where('user_id', $userId)
        ->whereRaw("DATE(from_date_time) <= $date and DATE(to_date_time) >= $date")->first();
    return $getData;
}

/**
 * convert date time to user time zone
 * @param type $dateTime
 * @param type $fromTz(UTC)
 * @return type datetime
 */
function converToTz($dateTime, $fromTz = "UTC")
{
    $toTz = (isset($_COOKIE['timezone'])) ? $_COOKIE['timezone'] : "UTC";
    $date = new \DateTime($dateTime, new \DateTimeZone($fromTz));
    $date->setTimezone(new \DateTimeZone($toTz));
    $date = $date->format('Y-m-d H:i:s');
    return $date;
}

/**
 * check mentor is liked to mentee or not
 * @param type $userId
 * @param type $mentorID
 * @return type boolean
 */
function checkUserLiked($userId, $mentorID)
{
    $favourite = Favorite::where(['from_id' => $userId, 'to_id' => $mentorID])->first();
    return ($favourite) ? true : false;
}

/**
 * [removeProfileImage description] < To remove/delete profie Image >
 *
 * @param  [type] $fileName [description] File name to delete
 * @return [type]           [description] Boolean
 */
function removeProfileImage($fileName)
{
    try {
        $uploads = config('constants.UPLOAD_PATH');
        $profile = config('constants.PROFILE_PATH');
        $filePath = public_path($uploads . '/' . $profile . '/') . $fileName;
        unlink($filePath);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

function checkImageCount($imageArray)
{
    //    print_r($imageArray);die;
    $count = 0;
    foreach ($imageArray as $item) {
        $user = DB::table('media')->where('name', $item)->where('status', 'pending')->first();
        if ($user) {
            $count++;
        }
    }
    if (count($imageArray) != $count) {
        return false;
    } else {
        return true;
    }
}

function getManagerCount($type)
{
    $count = 0;
    $company = Company::where(['user_id' => Auth::user()->id])->first();
    if ($company) {
        if ($type == 'total') {
            $count = Manager::where(['company_id' => $company->id])->count();
        } else {
            $count = Manager::where(['company_id' => $company->id])->with(['managerUser']);
            $count->whereHas('managerUser', function ($q) {
                $q->where('status', '=', 'active');
            });
            $count = $count->count();
        }
    }
    return $count;
}

function getEmployeeCount($userId, $type)
{
    $count = 0;
    $user = User::where(['id' => $userId])->first();
    if ($user) {
        if ($user->user_role == 'company') {
            $company = Company::where(['user_id' => $user->id])->first();
            if ($type == 'total') {
                $count = Employee::where(['company_id' => $company->id])->count();
            } else {
                $count = Employee::where(['company_id' => $company->id])->with(['employeeDetail']);
                $count->whereHas('employeeDetail', function ($q) {
                    $q->where('status', '=', 'active');
                });
                $count = $count->count();
            }
        } else {
            $manager = Manager::where(['user_id' => $user->id])->first();
            if ($type == 'total') {
                $count = Employee::where(['manager_id' => $manager->id])->count();
            } else {
                $count = Employee::where(['manager_id' => $manager->id])->with(['employeeDetail']);
                $count->whereHas('managerUser', function ($q) {
                    $q->where('status', '=', 'active');
                });
                $count = $count->count();
            }
        }
    }
    return $count;
}

function getProfileCompletionPercentage($userId = '')
{
    $percentage = 0;
    if ($userId) {
        $user = User::where('id', $userId)->first();
    } else {
        $user = Auth::user();
    }

    if ($user) {
        if (!empty($user->email)) {
            $percentage += 25;
        }
        if (!empty($user->phone_number)) {
            $percentage += 25;
        }
        if (!empty($user->name)) {
            $percentage += 25;
        }
        if (!empty($user->status) && ($user->status == 'active')) {
            $percentage += 25;
        }
    }
    return $percentage;
}

/**
 * @GET FILE SIZE IN MB USING FILE URL
 * @date : 10/10/2019 -R
 */
function getFileSize($path)
{
    require_once('public/getid3/getid3.php');
    $getID3 = new \getID3();
    $filename = $path;
    $fileinfo = $getID3->analyze($filename);
    $bytes = '0 MB';
    if (isset($fileinfo['filesize'])) {
        $bytes = number_format($fileinfo['filesize'] / 1048576, 2) . ' MB';
    }
    return $bytes;
}

/**
 * @FOR RATING
 * @date : 11/10/2019 -R
 */
function getRatingstar($count)
{
    $rating = floor($count);
    $roundRating = round($count);
    $decimal = $count - $rating;
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $count) {
            $html .= ' <i class="icon-rating active"></i>';
        } else {
            if ($decimal >= 0.25 && $decimal <= 0.75) {
                $html .= ' <i class="icon-rating active"></i>';
                $decimal = 0;
            } else if ($decimal > 0.75) {
                $html .= ' <i class="icon-rating active"></i>';
            } else {
                $html .= '&nbsp;<i class="icon-rating"></i>';
            }
        }
    }
    return $html;
}

/**
 * @FOR MEDIA IMAGE
 * @date : 27/11/2019 -R
 */
function getMediaImage($type)
{
    $mediaType = strtolower($type);
    switch ($mediaType) {
        case 'pdf':
            $mediaType = 'pdf';
            break;
        case 'xls':
        case 'xlsx':
            $mediaType = 'excel';
            break;
        case 'doc':
        case 'docx':
            $mediaType = 'doc';
            break;
        case 'xml':
            $mediaType = 'xml';
            break;
        case 'csv':
            $mediaType = 'csv';
            break;
        case 'txt':
            $mediaType = 'text';
            break;
        case 'env':
            $mediaType = 'env';
            break;
    }
    return $mediaType;
}

/**
 * @FOR RANDOM STRING
 * @date : 15/10/2019 -R
 */
function generateRandomString($length_of_string)
{
    $length_of_string = isset($length_of_string) ? $length_of_string : 20;
    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Shufle the $str_result and returns substring
    // of specified length
    return substr(
        str_shuffle($str_result),
        0,
        $length_of_string
    );
}

/**
 * @FOR RANDOM STRING
 * @date : 15/10/2019 -R
 */
function generateReferralCode($length_of_string)
{
    $length_of_string = isset($length_of_string) ? $length_of_string : 20;
    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Shufle the $str_result and returns substring
    // of specified length
    $ref_code = substr(str_shuffle($str_result),  0, $length_of_string);
    return strtoupper($ref_code);
}

// function converToTz($time, $toTz, $fromTz,$dateFormat="Y-m-d H:i:s") {
//     $date = new DateTime($time, new DateTimeZone($fromTz));
//     $date->setTimezone(new DateTimeZone($toTz));
//     $time = $date->format($dateFormat);
//     return $time;
// }

/**
 * @FIND MISSED DATE FROM DATE ARRAY
 */
function findMissedDates($dates)
{
    $firstDate = '';
    $lastDate = '';
    $firstMissedDate = '';
    $missingDates = array();
    usort($dates, function ($a, $b) {
        return strtotime($a) - strtotime($b);
    });
    if (count($dates) > 0) {
        $firstDate = $dates[0];
        $lastDate = (count($dates) == 1) ? $dates[0] : $dates[count($dates) - 1];
    }
    $dateStart = date_create($firstDate);
    $dateEnd   = date_create($lastDate);
    $interval  = new DateInterval('P1D');
    $period    = new DatePeriod($dateStart, $interval, $dateEnd);
    foreach ($period as $day) {
        $formatted = $day->format("Y-m-d");
        if (!in_array($formatted, $dates)) $missingDates[] = $formatted;
    }

    if (count($missingDates) > 0) {
        $firstMissedDate = $missingDates[0];
    } else {
        if (count($dates) == 1 && empty($missingDates)) {
            $firstMissedDate = date('Y-m-d', strtotime('+1 days'));
        } else {
            $firstMissedDate = date('Y-m-d');
        }
    }

    return array('first_missed_date' => $firstMissedDate, 'missed_dates' => $missingDates, 'dates' => $dates);
}

/**
 * @GET TIME ACORDING TO LOCAL TIMEZONE
 */
function converToTimezone($data_time = "", $format = 'h:i A', $timezone = "")
{
    $dt = Carbon::parse($data_time)->timezone($timezone);
    $date = $dt->format($format);
    return $date;
}

function getUsersRatingCount($jobId, $userId)
{
    $user = Reviews::select('rating')->where(['to_id' => $userId, 'job_id' => $jobId])->first();
    return $user['rating'];
}

function getUsersRating($userId)
{
    $user = Reviews::select('rating')->where(['to_id' => $userId])->get();
    $sum = 0;
    foreach ($user as $val) {
        $sum += $val['rating'];
    }
    $data['sum'] = $sum;
    $data['count'] = $user->count();
    return $data;
}

/**
 * to get invitation Count
 *
 * @param [type] $jobId
 * @param [type] $userId
 * @return void
 */
function getinvitationCount($userId, $jobId)
{
    $user = JobInvitation::select('id')->where(['invited_to' => $userId, 'job_id' => $jobId])->get();
    $count = $user->count();
    return $count;
}

/**
 * to get invitation Count
 *
 * @param [type] $jobId
 * @param [type] $userId
 * @return void
 */
function getRatingCount($jobId)
{
    $user = Reviews::select('id')->where('job_id', $jobId)->get();
    $count = $user->count();
    return $count;
}

function getTotalEarning($id){
    // $totalEarning = DB::table('job_bids')->where('user_id',$id)->where('status','accepted')->sum('price');
    // return $totalEarning;

    $jobState = 'completed';
    $totalEarning = Job::where('jobs.job_state',$jobState)->where('jobs.status', '!=', 'deleted')
                ->join('job_bids', 'jobs.id', '=', 'job_bids.job_id')
                ->where('job_bids.user_id', $id)->where('job_bids.status', 'accepted')->sum('jobs.fees_amount');
    return $totalEarning;

}

function getCompanyTotalEarning($id,$company_id){

$jobState = 'completed';
$totalEarning = Job::where('jobs.job_state',$jobState)->where('jobs.status', '!=', 'deleted')
            ->join('job_bids', 'jobs.id', '=', 'job_bids.job_id')
            ->join('users', 'users.id', '=', 'jobs.created_by')
            ->where('job_bids.company_id', $company_id)
            ->where('job_bids.status', 'accepted')
            ->where('jobs.status', '!=', 'deleted')
            ->where('user_role', '!=', 'individual')
            ->sum('jobs.fees_amount');
return $totalEarning;

}

function getLenderTotalEarning($id){
    $jobState = 'completed';
    $totalEarning = DB::table('jobs')->where('created_by',$id)->where('job_state' ,$jobState)->sum('fees_amount');
    return $totalEarning;
}


function compareDay($day,$compareDay){
    $string = '';
    for($i=0; $i<3;$i++){
        if($day[$i] == $compareDay[$i]){
            $string .= $day[$i];
        }else{
            $string = 'No';
        }
    }
    return $string;
}



