<?php

namespace App\Http\Resources;
use App\Models\Post;
use App\Models\Likes;
use App\Models\Comments;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

     // return parent::toArray($request);


      return [
        'id' => $this->id,
        'admin_id' => $this->admin_id,
        'title' => $this->title,
        'content'=>$this->content,
        'likes_count'=>Likes::where('post_id',$this->id )->get()->count(),
        'comments_count'=>Comments::where('post_id',$this->id )->get()->count(),
        'status'=>$this->status,
       'user_comments' =>  CommentResource::collection(Comments::where('post_id',$this->id )->get()),
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
    }







function restyle_text($input){
$input = number_format($input);
$input_count = substr_count($input, ',');
if($input_count != '0'){
if($input_count == '1'){
return substr($input, 0, -4).'k';
} else if($input_count == '2'){
return substr($input, 0, -8).'mil';
} else if($input_count == '3'){
return substr($input, 0, -12).'bil';
} else {
return;
}
} else {

return $input;
}
}



function convert($timestamp){
    $time = strtotime($timestamp);
    $time_ago = $this->time_Ago($time);
    return $time_ago;
}



    function time_Ago($time) {

        // Calculate difference between current
        // time and given timestamp in seconds
        $diff	 = time() - $time;

        // Time difference in seconds
        $sec	 = $diff;

        // Convert time difference in minutes
        $min	 = round($diff / 60 );

        // Convert time difference in hours
        $hrs	 = round($diff / 3600);

        // Convert time difference in days
        $days	 = round($diff / 86400 );

        // Convert time difference in weeks
        $weeks	 = round($diff / 604800);

        // Convert time difference in months
        $mnths	 = round($diff / 2600640 );

        // Convert time difference in years
        $yrs	 = round($diff / 31207680 );

        // Check for seconds
        if($sec <= 60) {
            return "$sec seconds ago";
        }

        // Check for minutes
        else if($min <= 60) {
            if($min==1) {
                return "one minute ago";
            }
            else {
                return "$min minutes ago";
            }
        }

        // Check for hours
        else if($hrs <= 24) {
            if($hrs == 1) {
                return "an hour ago";
            }
            else {
                return "$hrs hours ago";
            }
        }

        // Check for days
        else if($days <= 7) {
            if($days == 1) {
                return "Yesterday";
            }
            else {
                return "$days days ago";
            }
        }

        // Check for weeks
        else if($weeks <= 4.3) {
            if($weeks == 1) {
                return "a week ago";
            }
            else {
                return "$weeks weeks ago";
            }
        }

        // Check for months
        else if($mnths <= 12) {
            if($mnths == 1) {
                return "a month ago";
            }
            else {
                return "$mnths months ago";
            }
        }

        // Check for years
        else {
            if($yrs == 1) {
                return "one year ago";
            }
            else {
                return "$yrs years ago";
            }
        }
    }

}












// // Initialize current time
// $curr_time = "2013-07-10 09:09:09";

// // The strtotime() function converts
// // English textual date-time
// // description to a UNIX timestamp.
// $time_ago = strtotime($curr_time);

// // Display the time ago
// return time_Ago($time_ago) . "\n";


// // Initialize current time
// $curr_time="2019-01-05 09:09:09";

// // The strtotime() function converts
// // English textual date-time
// // description to a UNIX timestamp.
// $time_ago =strtotime($curr_time);

// // Display the time ago
// return time_Ago($time_ago);
// ?>
