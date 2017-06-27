<?php namespace App\Http\Controllers;

use Google_Client;
use Google_Service_YouTube;

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
		$client = $this->getClient();
		$authurl = $client->createAuthUrl();
		// echo "<a href=\"$authurl\">$authurl</a><br>";
		return view('oauth.login')->with('authurl',$authurl);
	}

	public function authenticated(){
		$input = \Input::all();
		// echo var_dump($input);
		$client = $this->getClient();
		if(isset($input['error'])){
			$authurl = $client->createAuthUrl();
			return view('oauth.error')->with('error',$input['error'])->with('authurl',$authurl);
		}
		$auth_code = $input['code'];
		$accessToken =$client->fetchAccessTokenWithAuthCode($auth_code);
		// echo var_dump($accessToken);
		// echo "<br>Refresh: ".$client->getRefreshToken()."<br>";
		$client->setAccessToken($accessToken);
		if($client->isAccessTokenExpired()){
			$accessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		}
		$userData = $this->getUserFromToken($client, $accessToken['id_token']);
		// echo "<br>";
		// echo var_dump($userData);
		// echo"<br>";
		\Session::put('user',$userData);
		\Session::put('accessToken',$accessToken);
		\Session::put('refreshToken', $client->getRefreshToken());
		return \Redirect::route('viewSubscriptions');
	}

	public function viewSubscriptions(){
		if(\Session::has('user')){
			// authenticated already	
			$pageToken = \Input::get('pageToken',null);		
			$client = $this->getClient();
			$accessToken = \Session::get('accessToken');
			$client->setAccessToken($accessToken);
			if($client->isAccessTokenExpired()){
				$accessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			}
			$userData = $this->getUserFromToken($client, $accessToken['id_token']);
			$service = new \Google_Service_YouTube($client);
			$part  = 'snippet,contentDetails';
			$params = array('mine'=>true);
			if($pageToken!=null){
				$params['pageToken'] = $pageToken;
			}
			$subscriptions = $service->subscriptions->listSubscriptions($part, $params);			
			\Session::put('user',$userData);
			\Session::put('accessToken',$accessToken);
			\Session::put('refreshToken', $client->getRefreshToken());
			// dd($subscriptions);
			return view('oauth.index')->with('userData',$userData)->with('subscriptions',$subscriptions);
		}
		return \Redirect::to('oauth');
	}

	public function getClient(){
		$OAUTH2_CLIENT_ID = $this->getClientId();
		$OAUTH2_CLIENT_SECRET = $this->getClientSecret();
		$client = new Google_Client();
		$client->setScopes(array('https://www.googleapis.com/auth/youtube.force-ssl','https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));
		$client->setRedirectUri(route('authenticated_google'));
		$client->setClientId($OAUTH2_CLIENT_ID);
		$client->setClientSecret($OAUTH2_CLIENT_SECRET);
		$client->setAccessType('offline');
		// remove after testing if creates bad experience. Needed to force refresh tokens everytime.
		$client->setPrompt('consent');	
		return $client;
	}

	public function getYoutubeReadClient(){
		$API_KEY = $this->getYoutubeAPIKey();
		$client = new Google_Client();
		// $client->addScopes(Google_Service_YouTube::YOUTUBE_READONLY);
		// $client->setAccessType('offline');
		$client->setDeveloperKey($API_KEY);
		return $client;
	}

	public function getYoutubeAPIKey(){
		return env('YOUTUBE_API_KEY','yourkey');
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

	function viewChat(){
		return view('chats.index');
	}

	function fetchChats(){
		$url = \Input::get('urlbar',"");
		$pageToken = \Input::get('pageToken',null);
		$youtubeurl = $this->verifyYoutubeURL($url);
		if($youtubeurl===false){
			return \Redirect::back()->with('error','Enter valid youtube live stream url');
		}
		$comments = ($this->getCommentsFromYouTube());
		// return dd($comments);
		return view('chats.list')->with('videoUrl',$url)->with('comments',$comments);	
	}

	public function getCommentsFromYouTube(){
		$url = \Input::get('urlbar',"");
		$pageToken = \Input::get('pageToken',null);
		$youtubeurl = $this->verifyYoutubeURL($url);
		if($youtubeurl===false){
			$result = array('error'=>'invalid params');
			return json_encode($result);//\Redirect::back()->with('error','Enter valid youtube live stream url');
		}
		$client = $this->getYoutubeReadClient();
		$videoId = $youtubeurl[2];
		$youtube = new Google_Service_YouTube($client);
		$params = array('videoId'=>$videoId,'textFormat'=>'plainText');
		if($pageToken!=null){
			$params['pageToken'] = $pageToken;
		}
		$comments = $youtube->commentThreads->listCommentThreads('snippet',$params);
		// dd($comments);
		$json_comments = $this->convertCommentToJson($comments);
		return $json_comments;
	}

	public function convertCommentToJson($comments){
		$result = array();
		if(isset($comments['nextPageToken'])){
			$result['nextPageToken'] = $comments['nextPageToken'];
		}
		if(isset($comments['prevPageToken'])){
			$result['prevPageToken'] = $comments['prevPageToken'];
		}
		$result['modelData'] = $comments['modelData'];
		return $result;
		
	}

	function verifyYoutubeURL($url){
		$matches = array();
		$is_valid = preg_match('/http[s]?:\/\/(www|m)\.youtube\.com\/watch\?v=([a-zA-Z0-9_]+)/i', $url, $matches);
		if($is_valid==1){
			return $matches;
		}
		return false;
	}
}
