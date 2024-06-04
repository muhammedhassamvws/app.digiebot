<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Server file
class Push_notifications {

	// (Android)API access key from Google API's Console.
	//private static $API_ACCESS_KEY = 'AIzaSyCSGxyIPETpAw9wsyvvTyuCbLqNCa66TWM';
	//private static $API_ACCESS_KEY = 'AIzaSyCPCDTGzP_hOjWuDbj-vmQetuq2mPCN6Ws';
	private static $API_ACCESS_KEY	= 'AAAAmF5pYLQ:APA91bGIRLWN7ryoVX_DlXu0NjJSjEzDeZsWFgXnhE_tAPKJyapfaCitFopy6-DBs0RpxQfHjvbHKVQyNwTr4ZhQb_Gq-JSef7Nh_2VOvgJ49rlTBb7ycwapReSVkCNTgyKieSxQBJxH';

	//(iOS) Private key's passphrase.
	// private static $passphrase		= 'pakistan1';
	private static $passphrase		= '1234';
	private $android_api_key;

	// Change the above three vriables as per your app.

	public function __construct() {
		//exit('Init function is not allowed');
		//date_default_timezone_set('America/Los_Angeles');
		$CI	=& get_instance();
		/*$CI->load->model('Settings_Model');

		$this->android_api_key	= $CI->Settings_Model->get_option('android_api_key');*/
	}

	public function notify( $user_id, $data){
        $CI =& get_instance();
		$user_subscription	= true;
		if($user_subscription){
			$user_ios_tokens		= $CI->Mod_app_services->get_user_ios_token($user_id);
			$user_android_tokens	= $CI->Mod_app_services->get_user_android_token($user_id);
			if( $user_ios_tokens ||  $user_android_tokens ){
				$badge_number	= $user_ios_tokens[0]->badge_number;
				//echo '<pre>';print_r($user_ios_tokens);exit;
				foreach( $user_ios_tokens as $device ){
					$i	= 1;
					if($device->device_token){
						$badge_number			= $badge_number + 1;
						//$data['aps']['badge']	= 10;
						$notification_send		= $this->iOS( $data, $device->device_token, $badge_number );
						$i++;
					}
				}

				$CI->Mod_app_services->update_badge_number($user_id, $badge_number);

				foreach( $user_android_tokens as $device ){
					if($device->device_token){
						$notification_send	= $this->android_notification( $data, $device->device_token );
					}
				}
				return true;
			} else{
				return false;
			}
		} else{
			return false;
		}
    }


    // Sends Push notification for Android users
    function android_notification($data, $deviceToken){

		$api_access_key	= 'AAAAmF5pYLQ:APA91bGIRLWN7ryoVX_DlXu0NjJSjEzDeZsWFgXnhE_tAPKJyapfaCitFopy6-DBs0RpxQfHjvbHKVQyNwTr4ZhQb_Gq-JSef7Nh_2VOvgJ49rlTBb7ycwapReSVkCNTgyKieSxQBJxH';

		$msg		= array(
						'body'			=> $data['msg_desc'],
						'title'			=> $data['title'],
						'icon'			=> 'default',//Default Icon
						'sound'			=> 'default',//Default sound
						'click_action'	=> 'FROM_DIGIEBOT'
					);
		$fields		= array(
						'to'			=> $deviceToken,
						'priority' 		=> 'high',
						'data' 			=> $msg
					);
        $headers	= array(
						'Authorization: key=' . $api_access_key,
						'Content-Type: application/json'
					);
        #Send Reponse To FireBase Server
        $ch	= curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result	= curl_exec($ch );
        curl_close( $ch );


		// TODO: Check this later 25-10-19 [Umer Abbas]
        // $post_data = print_r($result, true);
		// $fp = fopen('sms_errors.txt', 'a') or exit("Unable to open file!");
		// fwrite($fp, $post_data);
		// fclose($fp);

        if (isset($result->failure) && $result->failure > 0){

            return 'error';

        }else{

        	//return 'Message successfully delivered' . PHP_EOL;
            return 'success';
        }

    }

	// Sends Push notification for Android users
	function android_notification_topic($data){

			$api_access_key	= 'AAAAJoVGwq4:APA91bHl_SRZ_d-pfm_W-5KXSpSLCvnRFlYX5dvdB9VxwOLnbTE8Vasce8J5e4XDVREb_KuOVktVN-pZC3EWACNF30RdY8X-x-fBc1eRlSm2l5F-4huQ5m4e303ivhFFSNqwOrHt5EfZ';

			$payload = array(
				'to'=>'/topics/Topicnoti',
				'data'=>array(
					"title"=> $data['title'],
					"url"=> $data['url'],
					"priority" => $data['priority'],
					"last_run" => $data['last_run'],
					"cron_duration" => $data['cron_duration'],
					)
			);
		$headers = array(
			'Authorization:key='.$api_access_key,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $payload ) );
		$result = curl_exec($ch );
		$result = json_decode($result);
		curl_close( $ch );
		
		if (isset($result->failure) && $result->failure > 0){
	
			return 'error';
	
		}else{
	
			//return 'Message successfully delivered' . PHP_EOL;
			return 'success';
		}

	}
	

    // Sends Push notification for iOS users
	static public function iOS($data, $devicetoken) {

		//$pem	=	require_once('pushcert(2).pem');
		//echo 'pem: '.$pem;exit;
		$deviceToken = $devicetoken;

		$ctx = stream_context_create();
		// ck.pem is your certificate file
		//stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushcert(2).pem');
		// stream_context_set_option($ctx, 'ssl', 'local_cert', APPPATH.'/libraries/pushcerdevt.pem');
		stream_context_set_option($ctx, 'ssl', 'local_cert', APPPATH.'/libraries/digieAPNs.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', self::$passphrase);

		// Open a connection to the APNS server
		$fp = stream_socket_client(
			'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp)
			//exit("Failed to connect: $err $errstr" . PHP_EOL);
			return 'error'.$errstr;

		// Create the payload body
		$body['aps'] = array(
			'alert' => array(
			    'body'			=> $data['msg_desc'],
					'title'			=> $data['title'],
					'click_action'	=> 'FROM_DIGIEBOT'
			 ),
			'badge'			=> (int)"0",
			'icon'			=> 'default',//Default Icon
			'sound'			=> 'default',//Default sound
		);
		$body['date_time']			= date('Y-m-d H:i:s');

		// Encode the payload as JSON
		$payload = json_encode($body);

		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));

		// Close the connection to the server
		fclose($fp);

		if (!$result)
			//return 'Message not delivered' . PHP_EOL;
			return 'error';
		else
			//return 'Message successfully delivered' . PHP_EOL;
			return 'success';
	}

	function send_ios_notification($deviceToken,$data){
		// $passphrase = 'pakistan1';
		$passphrase = '1234';
		$ctx = stream_context_create();
		// stream_context_set_option($ctx, 'ssl', 'local_cert', APPPATH.'/libraries/pushcerdevt.pem');
		stream_context_set_option($ctx, 'ssl', 'local_cert', APPPATH.'/libraries/digieAPNs.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		// Open a connection to the APNS server
		$fp = stream_socket_client(
		'ssl://gateway.sandbox.push.apple.com:2195', $err,  // For development
		// 'ssl://gateway.push.apple.com:2195', $err, // for production
		$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$fp)
		exit("Failed to connect: $err $errstr" . PHP_EOL);
		//echo 'Connected to APNS' . PHP_EOL;
		// Create the payload body
		$body['aps'] = array(
			'alert' => array(
				'body'			=> $data['msg_desc'],
					'title'			=> $data['title'],
					'click_action'	=> 'FROM_DIGIEBOT'
				),
			'badge'			=> (int)"0",
			'icon'			=> 'default',//Default Icon
			'sound'			=> 'default',//Default sound
		);
		$body['date_time']			= date('Y-m-d H:i:s');
		// Encode the payload as JSON
		$payload = json_encode($body);
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', trim($deviceToken)) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		if (!$result){
		//echo 'Message not delivered' . PHP_EOL;
		}
		else
		{
		//echo 'Message successfully delivered' . PHP_EOL;
		return $result;
		}
		// Close the connection to the server
		fclose($fp);
	}
}
?>
