<?php namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Youtube;

class OAuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$SECTION_TYPES = array("allPlaylists", "completedEvents", "likedPlaylists",
						  "likes", "liveEvents", "multipleChannels", "multiplePlaylists",
						  "popularUploads", "recentActivity", "recentPosts", "recentUploads",
						  "singlePlaylist", "upcomingEvents");		
		$client = $this->getClient();
		$authurl = $client->createAuthUrl();
		echo "<a href=\"$authurl\">$authurl</a><br>";
		return view('home');
	}

	public function authenticated(){
		$input = \Input::all();
		// echo var_dump($input);
		$auth_code = $input['code'];
		$client = $this->getClient();
		$accessToken =$client->fetchAccessTokenWithAuthCode($auth_code);
		// echo var_dump($accessToken);
		$client->setAccessToken($accessToken);
		if($client->isAccessTokenExpired()){
			$accessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		}
		$userData = $this->getUserFromToken($client, $accessToken['id_token']);
		// echo "<br>";
		// echo var_dump($userData);
		// echo"<br>";
		$service = new Google_Service_Youtube($client);
		$part  = 'snippet,contentDetails';
		$params = array('mine'=>true);
		$subscriptions = $service->subscriptions->listSubscriptions($part, $params);
		// echo var_dump($subscriptions);
		return view('home');
	}

	public function getClient(){
		$OAUTH2_CLIENT_ID = $this->getClientId();
		$OAUTH2_CLIENT_SECRET = $this->getClientSecret();
		$client = new Google_Client();
		// $client->setApplicationName('StreamFFF interview app');
		$client->setScopes(array('https://www.googleapis.com/auth/youtube.force-ssl','https://www.googleapis.com/auth/userinfo.email'));
		$client->setRedirectUri(route('authenticated_google'));
		$client->setClientId($OAUTH2_CLIENT_ID);
		$client->setClientSecret($OAUTH2_CLIENT_SECRET);
		$client->setAccessType('offline');
		return $client;
	}

	public function getClientId(){
		return env('OAUTH2_CLIENT_ID','yourclientid');
	}

	public function getClientSecret(){
		return env('OAUTH2_CLIENT_SECRET','yourclientsecret');
	}

	function getUserFromToken($client, $token) {
	  $ticket = $client->verifyIdToken($token);
	  if ($ticket) {
	    return $ticket;
	  }
	  return false;
	}
}
