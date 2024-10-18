<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Mail\DistressPriorityMail;
use App\Models\DistressMessage;
use App\Models\GuardianDistressMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mail\GuardianDistressMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Http\Resources\GuardianDistressMessageResource;

class DistressMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }



//#########Delete Messages

  /**
     * Delete Distress Message
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function deleteDistressMessage ($id) {
      if(DistressMessage::where('id', $id)->exists()) {
        $message = DistressMessage::find($id);
        $message->delete();

          $response['responseMessage'] = 'Message Deleted';
                $response['responseCode'] = 00;
                return response()->json($response, 200);
      } else {

         $response['responseMessage'] = 'Failed';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
      }
    }




  /**
     * Delete Distress Message
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function deleteGuardianDistressMessage($id) {
      if(GuardianDistressMessage::where('id', $id)->exists()) {
        $message = GuardianDistressMessage::find($id);
        $message->delete();

          $response['responseMessage'] = 'Message Deleted';
                $response['responseCode'] = 00;
                return response()->json($response, 200);
      } else {

         $response['responseMessage'] = 'Failed';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
      }
    }











    //###############################SEND NOTIFICATION ###############################################
    public function sendNotification($userId, $title, $content, $mobile_id)
    {
        $msg = new Notifications();
        $msg->admin_id = "2020";
        $msg->user_id = $userId;
        $msg->title = $title;
        $msg->content = $content;
        $msg->status = 'unread';
        $msg->mobile_id = $mobile_id;
        $msg->save();
    }



    //########################################################


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request)
    {
        $user_id = $request->user_id;
        $title = $request->title;
        $content = $request->content;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $time = $request->time_of_message;
        $phoneNo = $request->phone_number;
        $priority = $request->priority;
        $location = $request->location;
        $guardianIds = DB::table('guardians')->where('ward_id', $user_id)->pluck('guardian_id');

        $time = Carbon::now();
        $distressMessage = null;
        if ($guardianIds == '[]') {


            $distressMessage = DistressMessage::create([
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'longitude' => $longitude,
                'location' => $location,
                'latitude' => $latitude,
                'time_of_message' => $time,
                'phone_number' => $phoneNo,
                'priority' => $priority
            ]);

            $username = DB::table('users')->where('id', $user_id)->pluck('username');
            $this->sendAdminConfirmEMail('support@whispertohumanity.com', $content, $longitude, $latitude, $phoneNo, $username);

            $response['responseMessage'] = 'Message Sent To SIYP Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        }


        foreach ($guardianIds as $id) {
            $guard = new GuardianDistressMessage();



            $distressMessage = DistressMessage::create([
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'longitude' => $longitude,
                'location' => $location,
                'latitude' => $latitude,
                'time_of_message' => $time,
                'phone_number' => $phoneNo,
                'priority' => $priority
            ]);



            $guard->distress_message_id =  $distressMessage->id;

            $guard->guardian_id = $id;
            $guard->ward_id = $user_id;
            $guard->title = $title;
            $guard->content = $content;
            $guard->longitude = $longitude;
            $guard->latitude = $latitude;
            $guard->time_of_message = Carbon::now();
            $guard->phone_number = $phoneNo;
            $guard->priority = $priority;
            $guard->status = 'unread';
            $guard->save();


            //Get guardian EMail
            $email = DB::table('users')->where('id', $id)->pluck('email');
            //Get username
            $username = DB::table('users')->where('id', $id)->pluck('username');
            //Send Email
            //->sendEMail($email, $content, $longitude, $latitude, $phoneNo, $username);


            //Send Push Notification using One signal




        }



        //Send Email
        $email = DB::table('users')->where('id', $user_id)->pluck('email');
        $this->sendConfirmEMail($email, $content);
        $this->sendConfirmEMail('support@whispertohumanity.com', $content);


        //send notification to guardian
        $notId = $id;
        $ward_username = DB::table('users')->where('id', $user_id)->value('username');
        $title = "Distress message from " . $ward_username;
        $content = "You are getting this message because you are a guardian to " . $ward_username . ".  " . $ward_username . "raised a panic alarm and needs your help.         I am currently at this location:" . $location;
        $this->sendNotification($notId, $title, $content, "9");


        $response['responseMessage'] = 'Message Sent To Guardians and Whisper Response Team';
        $response['responseCode'] = 00;
        return response()->json($response, 200);
    }





    ################################View Media#######################################

    public function viewAudio($id)
    {
        $Audio = DB::table('distress_messages')->where('id', $id)->pluck('audio');

        $audio =  $Audio[0];
        if ($Audio != null) {
            return response()->download(public_path("users-distress-audio/" . $audio), 'Distress Audio');
        } else {
            return "No Audio";
            //  return response()->download(public_path("users-images/avatar.jpg"), 'User Image');
        }
    }




    public function viewPhoto($id)
    {
        $Photo = DB::table('distress_messages')->where('id', $id)->pluck('photo');

        $photo =  $Photo[0];
        if ($Photo != null) {
            return response()->download(public_path("users-distress-photos/" . $photo), 'Distress Photo');
        } else {
            return "No Photo";
            //  return response()->download(public_path("users-images/avatar.jpg"), 'User Image');
        }
    }






    function generatePin($number)
    {
        // Generate set of alpha characters
        $alpha = array();
        for ($u = 65; $u <= 90; $u++) {
            // Uppercase Char
            array_push($alpha, chr($u));
        }

        // Just in case you need lower case
        // for ($l = 97; $l <= 122; $l++) {
        //    // Lowercase Char
        //    array_push($alpha, chr($l));
        // }

        // Get random alpha character
        $rand_alpha_key = array_rand($alpha);
        $rand_alpha = $alpha[$rand_alpha_key];

        // Add the other missing integers
        $rand = array($rand_alpha);
        for ($c = 0; $c < $number - 1; $c++) {
            array_push($rand, mt_rand(0, 9));
            shuffle($rand);
        }

        return implode('', $rand);
    }




    public function testPath()
    {
        $t =  public_path();
        echo $t;
    }






public function mime2ext($mime){
    $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
    "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
    "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
    "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
    "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
    "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
    "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
    "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
    "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
    "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
    "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
    "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
    "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
    "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
    "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
    "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
    "pdf":["application\/pdf","application\/octet-stream"],
    "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
    "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
    "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
    "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
    "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
    "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
    "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
    "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
    "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
    "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
    "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
    "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
    "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
    "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
    "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
    "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
    "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
    "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
    "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
    "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
    "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
    "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
    "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
    "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
    "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
    "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
    "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
    "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
    $all_mimes = json_decode($all_mimes,true);
    foreach ($all_mimes as $key => $value) {
        if(array_search($mime,$value) !== false) return $key;
    }
    return false;
}


































/*
to take encoded files as a parameter,decoded,save as a file,and return json message
*/
public function upload_file($encoded_string){
    $target_dir = ''; // add the specific path to save the file
    $decoded_file = base64_decode($encoded_string); // decode the file
    $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
    $extension = $this->mime2ext($mime_type); // extract extension from mime type
    $file = uniqid() .'.'. $extension; // rename file as a unique name
    $file_dir = $target_dir . uniqid() .'.'. $extension;
    try {

        //$radString = $this->generatePin(5);
        //$fileName = $user_id . "-" . $radString . ".mp4";
        $path = public_path() . '/distress-videos/'.$file;



        file_put_contents($path, $decoded_file); // save

        return $file;
    } catch (\Exception $e) {
        header('Content-Type: application/json');
        echo json_encode($e->getMessage());
    }

}





/*
to take encoded files as a parameter,decoded,save as a file,and return json message
*/
public function upload_Audio_file($encoded_string){
    $target_dir = ''; // add the specific path to save the file
    $decoded_file = base64_decode($encoded_string); // decode the file
    $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
    $extension = $this->mime2ext($mime_type); // extract extension from mime type
    $file = uniqid() .'.'. $extension; // rename file as a unique name
    $file_dir = $target_dir . uniqid() .'.'. $extension;
    try {

        //$radString = $this->generatePin(5);
        //$fileName = $user_id . "-" . $radString . ".mp4";
        $path = public_path() . '/distress-audio/'.$file;



        file_put_contents($path, $decoded_file); // save

        return $file;
    } catch (\Exception $e) {
        header('Content-Type: application/json');
        echo json_encode($e->getMessage());
    }

}











/*
to take encoded files as a parameter,decoded,save as a file,and return json message
*/
public function upload_Photo_file($encoded_string){
    $target_dir = ''; // add the specific path to save the file
    $decoded_file = base64_decode($encoded_string); // decode the file
    $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
   $extension = $this->mime2ext($mime_type); // extract extension from mime type
    $file = uniqid() .'.'. $extension; // rename file as a unique name
    $file_dir = $target_dir . uniqid() .'.'. $extension;
    try {

        //$radString = $this->generatePin(5);
        //$fileName = $user_id . "-" . $radString . ".mp4";
        $path = public_path() . '/distress-photos/'.$file;



        file_put_contents($path, $decoded_file); // save

        return $file;
    } catch (\Exception $e) {
        header('Content-Type: application/json');
        echo json_encode($e->getMessage());
    }

}







    #################SEND VIDEO DISTRESS MESSAGE #########################
    /**
     * upload distress video
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function distressVideo64(Request $request)
    {
        $input = $request->all();




        $user_id = $request->user_id;
        $content = $request->content;
        $title = $request->title;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $time_of_message = Carbon::now();
        $phone_number = $request->phone_number;
       // $photo = $request->photo;
        $video = $request->video;
       // $audio = $request->audio;
        $location = $request->location;
        $priority = $request->priority;


        $encoded_video =  $request->video;
        if ($encoded_video != '') {
            $radString = $this->generatePin(5);


                $encodeString = str_replace("null","",$encoded_video);

            $fileName = $this->upload_file($encodeString);


        //     $fname = public_path() . '/distress-videos/test.txt';
        // file_put_contents($fname, $encoded_video);





            $guardianIds = DB::table('guardians')->where('ward_id', $user_id)->pluck('guardian_id');


            if ($guardianIds == '[]') {
                $msg = new DistressMessage();
                $msg->user_id = $user_id;
                $msg->content = $content;
                $msg->title = $title;
                $msg->longitude = $longitude;
                $msg->location;
                $msg->latitude = $latitude;
                $msg->time_of_message = Carbon::now();
                $msg->phone_number = $phone_number;
                $msg->priority = $priority;
                $msg->video = $fileName;
                $msg->save();

                $username = DB::table('users')->where('id', $user_id)->pluck('username');
                $this->sendAdminConfirmEMail('support@whispertohumanity.com', $content,  $username);

                $response['responseMessage'] = 'Message Sent To SIYP Response Team';
                $response['responseCode'] = 00;
                return response()->json($response, 200);
            }

            $distressMessage = DistressMessage::create([
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'longitude' => $longitude,
                'location' => $location,
                'latitude' => $latitude,
                'time_of_message' => $time_of_message,
                'phone_number' => $phone_number,
                'priority' => $priority,
                'video'=>$fileName,
            ]);


            foreach ($guardianIds as $id) {






                $guard = new GuardianDistressMessage();

                $guard->distress_message_id =  $distressMessage->id;
                $guard->guardian_id = $id;
                $guard->ward_id = $user_id;
                $guard->title = $title;
                $guard->content = $content;
                $guard->longitude = $longitude;
                $guard->latitude = $latitude;
                $guard->time_of_message = Carbon::now();
                $guard->phone_number = $phone_number;
                $guard->video = $fileName;
                $guard->priority = $priority;
                $guard->status = 'unread';
                $guard->save();


                //Get guardian EMail
                $email = DB::table('users')->where('id', $id)->pluck('email');
                //Get username
                $username = DB::table('users')->where('id', $id)->pluck('username');
                //Send Email
                $this->sendEMail($email, $content, $username);


                //Send Push Notification using One signal


            }





            // Send Email
            $email = DB::table('users')->where('id', $user_id)->pluck('email');
            $this->sendConfirmEMail($email, $content);
            $this->sendConfirmEMail('support@whispertohumanity.com', $content);

            //   Send Push Notification



            //send notification to guardian
            $notId = $id;
            $ward_username = DB::table('users')->where('id', $user_id)->value('username');
            $title = "Distress message from " . $ward_username;
            $content = "You are getting this message because you are a guardian to " . $ward_username . ". " . $ward_username . "raised a panic alarm and needs your help";
            $this->sendNotification($notId, $title, $content, "5");


            $response['responseMessage'] = 'Message Sent To Guardians and Whisper Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        } else {

            $response['responseMessage'] = 'video empty';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }






    ########################################################






    ######################SEND AUDIO #########################################################
    /**
     * upload distress video
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function distressAudio64(Request $request)
    {
        $input = $request->all();

        $user_id = $request->user_id;
        $content = $request->content;
        $title = $request->title;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $location = $request->location;
        $time_of_message = Carbon::now();
        $phone_number = $request->phone_number;
        $audio = $request->audio;
        $priority = $request->priority;

        $encoded_audio =  $request->audio;
        if ($encoded_audio != '') {





              $encodeString = str_replace("null","",$encoded_audio);

            $fileName = $this->upload_Audio_file($encodeString );


            $guardianIds = DB::table('guardians')->where('ward_id', $user_id)->pluck('guardian_id');

            if ($guardianIds == '[]') {
                $msg = new DistressMessage();
                $msg->user_id = $user_id;
                $msg->content = $content;
                $msg->title = $title;
                $msg->location;
                $msg->longitude = $longitude;
                $msg->latitude = $latitude;
                $msg->time_of_message = Carbon::now();
                $msg->phone_number = $phone_number;
                $msg->audio = $fileName;
                $msg->priority = $priority;
                $msg->save();

                $username = DB::table('users')->where('id', $user_id)->pluck('username');
                $this->sendAdminConfirmEMail('support@whispertohumanity.com', $content, $username);

                $response['responseMessage'] = 'Message Sent To Whisper Response Team';
                $response['responseCode'] = 00;
                return response()->json($response, 200);
            }

            $distressMessage = DistressMessage::create([
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'longitude' => $longitude,
                'location' => $location,
                'latitude' => $latitude,
                'time_of_message' => $time_of_message,
                'phone_number' => $phone_number,
                'priority' => $priority,
                'audio'=>$fileName
            ]);




            foreach ($guardianIds as $id) {



                    $guard = new GuardianDistressMessage();

                 $guard->distress_message_id =  $distressMessage->id;
                $guard->guardian_id = $id;
                $guard->ward_id = $user_id;
                $guard->title = $title;
                $guard->content = $content;
                $guard->longitude = $longitude;
                $guard->latitude = $latitude;
                $guard->time_of_message = Carbon::now();
                $guard->phone_number = $phone_number;
                $guard->audio = $fileName;
                $guard->priority = $priority;
                $guard->status = 'unread';
                $guard->save();


                //Get guardian EMail
                $email = DB::table('users')->where('id', $id)->pluck('email');
                //Get username
                $username = DB::table('users')->where('id', $id)->pluck('username');
                //Send Email
                $this->sendEMail($email, $content, $username);


                //Send Push Notification using One signal



            }





            // Send Email
            $email = DB::table('users')->where('id', $user_id)->pluck('email');
            //$this->sendConfirmEMail($email, $content);
            // $this->sendConfirmEMail('support@whispertohumanity.com', $content);

            //   Send Push Notificatio

            //send notification to guardian
            $notId = $id;
            $ward_username = DB::table('users')->where('id', $user_id)->value('username');
            $title = "Distress message from " . $ward_username;
            $content = "You are getting this message because you are a guardian to " . $ward_username . ". " . $ward_username . "raised a panic alarm and needs your help. I am currently in this location:" . $location;
            $this->sendNotification($notId, $title, $content, "5");



            $response['responseMessage'] = 'Message Sent To Guardians and Whisper Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        } else {

            $response['responseMessage'] = 'audio empty';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }

    #############################################################################################








    ##########################################SEND PHOTO###############################################
    // public function base64_to_jpeg($base64_string, $output_file) {
    //     // open the output file for writing
    //     $ifp = fopen( $output_file, 'wb' );

    //     // split the string on commas
    //     // $data[ 0 ] == "data:image/png;base64"
    //     // $data[ 1 ] == <actual base64 string>
    //     $data = explode( ',', $base64_string );

    //     // we could add validation here with ensuring count( $data ) > 1
    //     fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    //     // clean up the file resource
    //     fclose( $ifp );

    //     return $output_file;
    // }


    function save_base64_image($base64_image_string, $fileName_without_extension, $path)
    {
        // Obtain the original content (usually binary data)
        $bin = base64_decode($base64_image_string);

        // Gather information about the image using the GD library
        $size = getImageSizeFromString($bin);

        // Check the MIME type to be sure that the binary data is an image
        if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
            die('Base64 value is not a valid image');
        }

        // Mime types are represented as image/gif, image/png, image/jpeg, and so on
        // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
        $ext = substr($size['mime'], 6);

        // Make sure that you save only the desired file extensions
        if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
            die('Unsupported image type');
        }

        // Specify the location where you want to save the image
        $img_file = "$path.{$ext}";

        // Save binary data as raw data (that is, it will not remove metadata or invalid contents)
        // In this case, the PHP backdoor will be stored on the server

        file_put_contents($img_file, $bin);



        return $img_file;
    }






function base64_to_jpeg($base64_string, $output_file) {
  $file = 'Your base64 file string';
$file_data = base64_decode($file);
$f = finfo_open();
$mime_type = finfo_buffer($f, $file_data, FILEINFO_MIME_TYPE);
$file_type = explode('/', $mime_type)[0];
$extension = explode('/', $mime_type)[1];

echo $mime_type; // will output mimetype, f.ex. image/jpeg
echo $file_type; // will output file type, f.ex. image
echo $extension; // will output extension, f.ex. jpeg

$acceptable_mimetypes = [
    'application/pdf',
    'image/jpeg',
];

// you can write any validator below, you can check a full mime type or just an extension or file type
if (!in_array($mime_type, $acceptable_mimetypes)) {
    throw new \Exception('File mime type not acceptable');
}

// or example of checking just a type
if ($file_type !== 'image') {
    throw new \Exception('File is not an image');
}
}

    /**
     * upload distress video
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function distressPhoto64(Request $request)
    {
        $input = $request->all();




        $user_id = $request->user_id;
        $content = $request->content;
        $title = $request->title;
        $location = $request->location;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $time_of_message = Carbon::now();
        $phone_number = $request->phone_number;
        $photo = $request->photo;
        $priority = $request->priority;

        $encoded_photo =  $request->photo;
        if ($encoded_photo != '') {
      $encodeString = str_replace("null","",$encoded_photo);

      $fileName = $this->upload_Photo_file($encodeString);

            $guardianIds = DB::table('guardians')->where('ward_id', $user_id)->pluck('guardian_id');

            if ($guardianIds == '[]') {
                $msg = new DistressMessage();
                $msg->user_id = $user_id;
                $msg->content = $content;
                $msg->title = $title;
                $location = $location;
                $msg->longitude = $longitude;
                $msg->latitude = $latitude;
                $msg->time_of_message = Carbon::now();
                $msg->phone_number = $phone_number;
                $msg->photo = $fileName;
                $msg->priority = $priority;
                $msg->save();

                $username = DB::table('users')->where('id', $user_id)->pluck('username');
                $this->sendAdminConfirmEMail('support@whispertohumanity.com', $content,  $username);

                $response['responseMessage'] = 'Message Sent To Whisper Response Team';
                $response['responseCode'] = 00;
                return response()->json($response, 200);
            }



            $distressMessage = DistressMessage::create([
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'longitude' => $longitude,
                'location' => $location,
                'latitude' => $latitude,
                'time_of_message' => $time_of_message,
                'phone_number' => $phone_number,
                'priority' => $priority,
                'photo'=>$fileName
            ]);

            foreach ($guardianIds as $id) {
                $guard = new GuardianDistressMessage();

                $guard->distress_message_id =  $distressMessage->id;

                $guard->guardian_id = $id;
                $guard->ward_id = $user_id;
                $guard->title = $title;
                $location = $location;
                $guard->content = $content;
                $guard->longitude = $longitude;
                $guard->latitude = $latitude;
                $guard->time_of_message = Carbon::now();
                $guard->phone_number = $phone_number;
                $guard->photo = $fileName;
                $guard->priority = $priority;
                $guard->status = 'unread';
                $guard->save();


                //Get guardian EMail
                $email = DB::table('users')->where('id', $id)->pluck('email');
                //Get username
                $username = DB::table('users')->where('id', $id)->pluck('username');
                //Send Email
                $this->sendEMail($email, $content,  $username);


                //Send Push Notification using One signal




            }





            // Send Email
            $email = DB::table('users')->where('id', $user_id)->pluck('email');
            $this->sendConfirmEMail($email, $content);
            $this->sendConfirmEMail('support@whispertohumanity.com', $content);

            //   Send Push Notification


            //send notification to guardian
            $notId = $id;
            $ward_username = DB::table('users')->where('id', $user_id)->value('username');
            $title = "Distress message from " . $ward_username;
            $content = "You are getting this message because you are a guardian to " . $ward_username . ". " . $ward_username . "raised a panic alarm and needs your help";
            $this->sendNotification($notId, $title, $content, "5");


            $response['responseMessage'] = 'Message Sent To Guardians and SIYP Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        } else {

            $response['responseMessage'] = 'photo empty';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }

    ######################################################################################




























    /**
     * Ward declare false alarm
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMediaMessage(Request $request)
    {

        $input = $request->all();
        $extension = $request->file('photo')->extension();


        $user_id = $request->user_id;
        $content = $request->content;
        $title = $request->title;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $time_of_message = Carbon::now();
        $phone_number = $request->phone_number;
        $photo = $request->photo;
        $video = $request->video;
        $audio = $request->audio;
        $priority = $request->priority;



        //If Audio Exists , save  File
        $audio_extension = $request->file('audio')->extension();


        if (isset($audio_extension)) {

            $audioName = time() . "." . $audio_extension;
            $audioName = "userID(" . $user_id . ")" . $audioName;
            $audioPath = $request->file('audio')->move(public_path("/users-distress-audio"), $audioName);
        }


        //     //IF Video Exists, Save File
        //   $video_extension = $request->file('video')->extension();
        // if (isset($video_extension)) {
        //     $videoName = time() . "." . $video_extension;
        //     $videoName = "userID(" . $user_id . ")" . $videoName;
        //     $videoPath = $request->file('video')->move(public_path("/users-distress-video"), $videoName);
        // }



        //     //If photo Exists, Save File
        $photo_extension = $request->file('photo')->extension();
        if (isset($photo_extension)) {
            $photoName = time() . "." . $photo_extension;
            $photoName = "userID(" . $user_id . ")" . $photoName;
            $photoPath = $request->file('photo')->move(public_path("/users-distress-photos"), $photoName);
        }








        $guardianIds = DB::table('guardians')->where('ward_id', $user_id)->pluck('guardian_id');

        if ($guardianIds == '[]') {
            $msg = new DistressMessage();
            $msg->user_id = $user_id;
            $msg->content = $content;
            $request->title = $title;
            $msg->longitude = $longitude;
            $msg->latitude = $latitude;
            $msg->time_of_message = Carbon::now();
            $msg->phone_number = $phone_number;
            $msg->priority = $priority;
            $msg->save();

            $username = DB::table('users')->where('id', $user_id)->pluck('username');
            $this->sendAdminConfirmEMail('support@whispertohumanity.com', $content, $longitude, $latitude, $phone_number, $username);

            $response['responseMessage'] = 'Message Sent To SIYP Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        }

        foreach ($guardianIds as $id) {
            $guard = new GuardianDistressMessage();


            $con = array('time_of_message' => $time_of_message, 'photo', $photo);
            $dm_id = DB::table('distress_messages')->where($con)->value('distress_message_id');

            $guard->distress_message_id =  $dm_id;

            $guard->guardian_id = $id;
            $guard->ward_id = $user_id;
            $request->title = $title;
            $guard->content = $content;
            $guard->longitude = $longitude;
            $guard->latitude = $latitude;
            $guard->time_of_message = Carbon::now();
            $guard->phone_number = $phone_number;
            $guard->photo = $photoPath;
            $guard->audio = $audioPath;
            $guard->priority = $priority;
            $guard->status = 'unread';
            $guard->save();


            //Get guardian EMail
            $email = DB::table('users')->where('id', $id)->pluck('email');
            //Get username
            $username = DB::table('users')->where('id', $id)->pluck('username');
            //Send Email
            $this->sendEMail($email, $content, $longitude, $latitude, $phone_number, $username);


            //Send Push Notification using One signal
        }




        $msg = new DistressMessage();
        $msg->user_id = $user_id;
        $request->title = $title;
        $msg->content = $content;
        $msg->longitude = $longitude;
        $msg->latitude = $latitude;
        $msg->time_of_message = $time_of_message;
        $msg->phone_number = $phone_number;
        $msg->photo = $photoPath;
        // $msg->video = $videoPath;
        $msg->audio = $audioPath;
        $msg->priority = $priority;
        $msg->save();

        // Send Email
        $email = DB::table('users')->where('id', $user_id)->pluck('email');
        $this->sendConfirmEMail($email, $content);
        $this->sendConfirmEMail('support@whispertohumanity.com', $content);

        //   Send Push Notification
        $response['responseMessage'] = 'Message Sent To Guardians and Whisper Response Team';
        $response['responseCode'] = 00;
        return response()->json($response, 200);
    }






    ################################################   EMAILS  ####################################################################



    //Send Email
    public function sendEMail($email, $content,  $username)
    {

        $details = [
            'title' => 'Distress Message From' . $username,
            'body' => $content

        ];
        Mail::to($email)->send(new GuardianDistressMail($details));
    }


    //Send Admin Distress Mail

    public function sendAdminConfirmEMail($email, $content, $username)
    {
        $details = [
            'title' => 'Distress Message From' . $username,
            'body' => $content
        ];
        Mail::to($email)->send(new GuardianDistressMail($details));
    }



    //Send Confirmation Email
    public function sendConfirmEMail($email, $content)
    {

        $details = [
            'title' => 'Distress Message Sent To Your Guardians',
            'body' => $content
        ];
        Mail::to($email)->send(new GuardianDistressMail($details));
    }





    // Distress Priority CHange Email

    public function sendDistressPriorityEMail($email, $priority)
    {
        $details = [
            'title' => 'Distress Message Sent To Your Guardians',
            'body' => $priority
        ];
        Mail::to($email)->send(new DistressPriorityMail($details));
    }


    ####################################################################################################################




















    /**
     * Ward declare false alarm
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeDistressPriority(Request $request)
    {
        $user_id = $request->user_id;
        $priority = $request->priority;
        $distress_id = $request->distress_id;

        $cond = array(
            'id' => $distress_id,
            'user_id' => $user_id
        );


        //alert guardian new priority and send email
        $guardianIds = DB::table('guardians')->where('ward_id', $user_id)->pluck('guardian_id');

        if ($guardianIds == '[]') {

            $reqdata['priority'] = $priority;
            DistressMessage::where($cond)->update($reqdata);
            // $this->sendDistressPriorityEMail('support@whispertohumanity.com', $priority);
            $response['responseMessage'] = 'Distress Message Status Changed';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        } else {

            $reqdata['priority'] = $priority;
            $user = DistressMessage::where($cond)->update($reqdata);

            foreach ($guardianIds as $id) {

                $conditions = array(
                    'guardian_id' => $id,
                    'ward_id' => $user_id
                );


                $reqdata['priority'] = $priority;
                GuardianDistressMessage::where($conditions)->update($reqdata);

                //Get guardian EMail
                $email = DB::table('users')->where('id', $id)->pluck('email');
                //  $this->sendDistressPriorityEMail($email, $priority);
                $response['responseMessage'] = 'Distress Message Status Changed';
                $response['responseCode'] = 00;
                return response()->json($response, 200);
            }
        }

        //alert
    }


    /**
     * Guardian Fetch Distress Nessages Sent to Guardian
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function guardianViewAllDistress($id)
    {

        $messages = GuardianDistressMessage::orderBy('created_at', 'DESC')->where('guardian_id', $id)->get();

        if ($messages != '[]') {
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['data'] =  GuardianDistressMessageResource::collection(($messages));
            return response()->json($response, 200);
        } else {
            $response['responseMessage'] = 'you have no distress message';
            $response['responseCode'] = -1001;

            return response()->json($response, 200);
        }
    }





    /**
     * Ward Fetch Distress Nessages Sent to Guardian
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function wardViewAllDistress($id)
    {

        $messages = DistressMessage::orderBy('created_at', 'DESC')->where('user_id', $id)->get();

        if ($messages != '[]') {
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['data'] = $messages;
            return response()->json($response, 200);
        } else {
            $response['responseMessage'] = 'you have no distress message';
            $response['responseCode'] = -1001;

            return response()->json($response, 200);
        }
    }









    /**
     * Guardian Fetch Distress Nessages Sent to Guardian
     *
     *
     * @param  int  $id
     * @param string $priority
     * @return \Illuminate\Http\Response
     */
    public function guardianViewDistressOnPriority($id, $priority)
    {
        $condition = array(
            'guardian_id' => $id,
            'priority' => $priority
        );
        $messages = GuardianDistressMessage::orderBy('created_at', 'DESC')->where($condition)->get();

        if ($messages != '[]') {
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['priority'] = $priority;
            $response['data'] = $messages;
            return response()->json($response, 200);
        } else {
            $response['responseMessage'] = 'you have no distress message';
            $response['responseCode'] = -1001;

            return response()->json($response, 200);
        }
    }









    /**
     * Ward Fetch Distress Nessages Sent to Guardian
     *
     *
     * @param  int  $id
     * @param string $priority
     * @return \Illuminate\Http\Response
     */
    public function wardViewDistressOnPriority($id, $priority)
    {
        $condition = array(
            'user_id' => $id,
            'priority' => $priority
        );
        $messages = DistressMessage::where($condition)->get();

        if ($messages != '[]') {
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['priority'] = $priority;
            $response['data'] = $messages;
            return response()->json($response, 200);
        } else {
            $response['responseMessage'] = 'you have no distress message';
            $response['responseCode'] = -1001;

            return response()->json($response, 200);
        }
    }



















    /**
     * Guardian Fetch Distress Nessages Sent to Guardian
     *
     *
     * @param  int  $id
     * @param int $msg_id
     * @return \Illuminate\Http\Response
     */
    public function guardianViewSingleMessage($id, $msg_id)
    {

        $condition = array(
            'id' => $msg_id,
            'guardian_id' => $id,
        );

        GuardianDistressMessage::where('id', $msg_id)->update(array('status' => 'read'));

        $messages = GuardianDistressMessage::where($condition)->first();
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = $messages;
        return response()->json($response, 200);
    }




    /**
     * Ward Fetch Distress Nessages Sent to Guardian
     *
     *
     * @param  int  $id
     * @param int $msg_id
     * @return \Illuminate\Http\Response
     */
    public function wardViewSingleMessage($id, $msg_id)
    {

        $condition = array(
            'id' => $msg_id,
            'user_id' => $id,
        );


        $messages = DistressMessage::where($condition)->first();
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = $messages;
        return response()->json($response, 200);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }




















    //UPLAOD FILES



     /**
     * Ward declare false alarm
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function testing(Request $request)
    {

      $file = $request->file('image');

      //Display File Name
      echo 'File Name: '.$file->getClientOriginalName();
      echo '<br>';

      //Display File Extension
      echo 'File Extension: '.$file->getClientOriginalExtension();
      echo '<br>';

        $input = $request->all();
        echo $request->content;
    }









  /**
     * Ward declare false alarm
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMediaPhotoMessage(Request $request)
    {

        $user_id = $request->user_id;




      //  $photo_extension = $request->file('photo')->extension();

        $fileName=null;
         $file = $request->file('image');

            $photo_extension =  $file->getClientOriginalExtension();

        if (isset($photo_extension)) {

           $radString = $this->generatePin(5);
           $fileName = $user_id . "-" . $radString. ".".$photo_extension;


            $photoPath = $request->file('image')->move(public_path("/distress-photos"), $fileName);




        }

   $input = $request->all();

        $user_id = $request->user_id;
        $content = $request->content;
        $title = $request->title;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $time_of_message = Carbon::now();
        $phone_number = $request->phone_number;
        $photo = $fileName;
        $priority = 'urgent';
        $location= null;




        $guardianIds = DB::table('guardians')->where('ward_id', $user_id)->pluck('guardian_id');

        if ($guardianIds == '[]') {
            $msg = new DistressMessage();
            $msg->user_id = $user_id;
            $msg->content = $content;
            $msg->title = $title;
            $location = $location;
            $msg->longitude = $longitude;
            $msg->latitude = $latitude;
            $msg->time_of_message = Carbon::now();
            $msg->phone_number = $phone_number;
            $msg->photo = $photo;
            $msg->priority = $priority;
            $msg->save();

            $username = DB::table('users')->where('id', $user_id)->pluck('username');
            $this->sendAdminConfirmEMail('support@whispertohumanity.com', $content,  $username);

            $response['responseMessage'] = 'Message Sent To SIYP Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        }



        $distressMessage = DistressMessage::create([
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'longitude' => $longitude,
            'location' => $location,
            'latitude' => $latitude,
            'time_of_message' => $time_of_message,
            'phone_number' => $phone_number,
            'priority' => $priority
        ]);

        foreach ($guardianIds as $id) {
            $guard = new GuardianDistressMessage();

            $guard->distress_message_id =  $distressMessage->id;

            $guard->guardian_id = $id;
            $guard->ward_id = $user_id;
            $guard->title = $title;
            $location = $location;
            $guard->content = $content;
            $guard->longitude = $longitude;
            $guard->latitude = $latitude;
            $guard->time_of_message = Carbon::now();
            $guard->phone_number = $phone_number;
            $guard->photo = $photo;
            $guard->priority = $priority;
            $guard->status = 'unread';
            $guard->save();


            //Get guardian EMail
            $email = DB::table('users')->where('id', $id)->pluck('email');
            //Get username
            $username = DB::table('users')->where('id', $id)->pluck('username');
            //Send Email
            $this->sendEMail($email, $content,  $username);


            //Send Push Notification using One signal




        }


        $response['responseMessage'] = 'Message Sent To SIYP Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);

}




























/**
     * Ward declare false alarm
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMediaVideoMessage(Request $request)
    {

        $user_id = $request->user_id;




      //  $photo_extension = $request->file('photo')->extension();

        $fileName=null;
         $file = $request->file('video');

            $photo_extension =  $file->getClientOriginalExtension();

        if (isset($photo_extension)) {

           $radString = $this->generatePin(5);
           $fileName = $user_id . "-" . $radString. ".".$photo_extension;


            $photoPath = $request->file('video')->move(public_path("/distress-videos"), $fileName);




        }

   $input = $request->all();

        $user_id = $request->user_id;
        $content = $request->content;
        $title = $request->title;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $time_of_message = Carbon::now();
        $phone_number = $request->phone_number;
        $video = $fileName;
        $priority = 'urgent';
        $location= null;




        $guardianIds = DB::table('guardians')->where('ward_id', $user_id)->pluck('guardian_id');

        if ($guardianIds == '[]') {
            $msg = new DistressMessage();
            $msg->user_id = $user_id;
            $msg->content = $content;
            $msg->title = $title;
            $location = $location;
            $msg->longitude = $longitude;
            $msg->latitude = $latitude;
            $msg->time_of_message = Carbon::now();
            $msg->phone_number = $phone_number;
            $msg->video = $video;
            $msg->priority = $priority;
            $msg->save();

            $username = DB::table('users')->where('id', $user_id)->pluck('username');
            $this->sendAdminConfirmEMail('support@whispertohumanity.com', $content,  $username);

            $response['responseMessage'] = 'Message Sent To Whisper Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        }



        $distressMessage = DistressMessage::create([
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'longitude' => $longitude,
            'location' => $location,
            'latitude' => $latitude,
            'time_of_message' => $time_of_message,
            'phone_number' => $phone_number,
            'priority' => $priority
        ]);

        foreach ($guardianIds as $id) {
            $guard = new GuardianDistressMessage();

            $guard->distress_message_id =  $distressMessage->id;

            $guard->guardian_id = $id;
            $guard->ward_id = $user_id;
            $guard->title = $title;
            $location = $location;
            $guard->content = $content;
            $guard->longitude = $longitude;
            $guard->latitude = $latitude;
            $guard->time_of_message = Carbon::now();
            $guard->phone_number = $phone_number;
            $guard->video = $video;
            $guard->priority = $priority;
            $guard->status = 'unread';
            $guard->save();


            //Get guardian EMail
            $email = DB::table('users')->where('id', $id)->pluck('email');
            //Get username
            $username = DB::table('users')->where('id', $id)->pluck('username');
            //Send Email
            $this->sendEMail($email, $content,  $username);


            //Send Push Notification using One signal




        }


        $response['responseMessage'] = 'Message Sent To Whisper Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);

}







/**
     * Ward declare false alarm
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMediaAudioMessage(Request $request)
    {

        $user_id = $request->user_id;




      //  $photo_extension = $request->file('photo')->extension();

        $fileName=null;
         $file = $request->file('audio');

            $photo_extension =  $file->getClientOriginalExtension();

        if (isset($photo_extension)) {

           $radString = $this->generatePin(5);
           $fileName = $user_id . "-" . $radString. ".".$photo_extension;


            $photoPath = $request->file('audio')->move(public_path("/distress-audio"), $fileName);




        }

   $input = $request->all();

        $user_id = $request->user_id;
        $content = $request->content;
        $title = $request->title;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $time_of_message = Carbon::now();
        $phone_number = $request->phone_number;
        $audio = $fileName;
        $priority = 'urgent';
        $location= null;




        $guardianIds = DB::table('guardians')->where('ward_id', $user_id)->pluck('guardian_id');

        if ($guardianIds == '[]') {
            $msg = new DistressMessage();
            $msg->user_id = $user_id;
            $msg->content = $content;
            $msg->title = $title;
            $location = $location;
            $msg->longitude = $longitude;
            $msg->latitude = $latitude;
            $msg->time_of_message = Carbon::now();
            $msg->phone_number = $phone_number;
            $msg->audio = $audio;
            $msg->priority = $priority;
            $msg->save();

            $username = DB::table('users')->where('id', $user_id)->pluck('username');
            $this->sendAdminConfirmEMail('support@whispertohumanity.com', $content,  $username);

            $response['responseMessage'] = 'Message Sent To SIYP Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        }



        $distressMessage = DistressMessage::create([
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'longitude' => $longitude,
            'location' => $location,
            'latitude' => $latitude,
            'time_of_message' => $time_of_message,
            'phone_number' => $phone_number,
            'priority' => $priority
        ]);

        foreach ($guardianIds as $id) {
            $guard = new GuardianDistressMessage();

            $guard->distress_message_id =  $distressMessage->id;

            $guard->guardian_id = $id;
            $guard->ward_id = $user_id;
            $guard->title = $title;
            $location = $location;
            $guard->content = $content;
            $guard->longitude = $longitude;
            $guard->latitude = $latitude;
            $guard->time_of_message = Carbon::now();
            $guard->phone_number = $phone_number;
            $guard->audio = $audio;
            $guard->priority = $priority;
            $guard->status = 'unread';
            $guard->save();


            //Get guardian EMail
            $email = DB::table('users')->where('id', $id)->pluck('email');
            //Get username
            $username = DB::table('users')->where('id', $id)->pluck('username');
            //Send Email
            $this->sendEMail($email, $content,  $username);


            //Send Push Notification using One signal




        }


        $response['responseMessage'] = 'Message Sent To SIYP Response Team';
            $response['responseCode'] = 00;
            return response()->json($response, 200);

}






}
