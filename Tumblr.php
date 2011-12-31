<?php

/**
 * Tumblr API library for PHP
 *
 * LICENSE
 * 
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @package   Tumblr
 * @copyright Copyright (c) 2011 aoiaoi
 * @license   New BSD License
 */


require_once 'Zend/Oauth/Consumer.php';

/**
 * @package   Tumblr
 * @copyright Copyright (c) 2011 aoiaoi
 * @license   New BSD License
 */
class Tumblr {
  
  /** 
   * Base URI for the REST client
   */
  const BASE_URI = 'http://api.tumblr.com';
  
  /**
   * The path for the REST client
   */
  const PATH_BLOG_INFO   = '/v2/blog/%s/info';
  const PATH_BLOG_AVATAR = '/v2/blog/%s/avatar';
  const PATH_BLOG_POSTS  = '/v2/blog/$s/posts';
  
  /**
   * The URI for OAuth
   */
  const REQUEST_TOKEN_URI = 'http://www.tumblr.com/oauth/request_token';
  const AUTHORIZE_URI     = 'http://www.tumblr.com/oauth/authorize';
  const ACCESS_TOKEN_URI  = 'http://www.tumblr.com/oauth/access_token';
  
  /**
   * The URI for the OAuth client
   */
  const OAUTH_BLOG_FOLLOWERS   = 'http://api.tumblr.com/v2/blog/%s/followers';
  const OAUTH_POSTS_QUEUE      = 'http://api.tumblr.com/v2/blog/%s/posts/queue';
  const OAUTH_POSTS_DRAFT      = 'http://api.tumblr.com/v2/blog/%s/posts/draft';
  const OAUTH_POSTS_SUBMISSION = 'http://api.tumblr.com/v2/blog/%s/posts/submission';
  const OAUTH_POST             = 'http://api.tumblr.com/v2/blog/%s/post';
  const OAUTH_POST_EDIT        = 'http://api.tumblr.com/v2/blog/%s/post/edit';
  const OAUTH_POST_REBLOG      = 'http://api.tumblr.com/v2/blog/%s/post/reblog';
  const OAUTH_POST_DELETE      = 'http://api.tumblr.com/v2/blog/%s/post/delete';
  const OAUTH_USER_INFO        = 'http://api.tumblr.com/v2/user/info';
  const OAUTH_USER_DASHBOARD   = 'http://api.tumblr.com/v2/user/dashboard';
  const OAUTH_USER_LIKES       = 'http://api.tumblr.com/v2/user/likes';
  const OAUTH_USER_FOLLOWING   = 'http://api.tumblr.com/v2/user/following';
  const OAUTH_USER_FOLLOW      = 'http://api.tumblr.com/v2/user/follow';
  const OAUTH_USER_UNFOLLOW    = 'http://api.tumblr.com/v2/user/unfollow';
  const OAUTH_USER_LIKE        = 'http://api.tumblr.com/v2/user/like';
  const OAUTH_USER_UNLIKE      = 'http://api.tumblr.com/v2/user/unlike';
  
  /**
   * An OAuth application's consumer key
   *
   * @var string
   */
  protected $_consumerKey = null;
  
  /**
   * ...
   *
   * @var string
   */
  protected $_consumerSecret = null;
  
  /**
   * Access token retrieved from OAuth provider
   *
   * @var string
   */
  protected $_accessToken = null;
  
  /**
   * ...
   *
   * @var string
   */
  protected $_accessTokenSecret = null;
  
  /**
   * Reference to instance of Zend_Oauth_Consmer
   *
   * @var Zend_Oauth_Consumer
   */
  protected $_oauthConsumer = null;
  
  /**
   * Reference to instance of Zend_Oauth_Token_Access
   *
   * @var Zend_Oauth_Token_Access
   */
  protected $_oauthTokenAccess = null;
  
  /**
   * Reference to instance of Zend_Oauth_Client
   *
   * @var Zend_Oauth_Client
   */
  protected $_oauthClient = null;

  /**
   * Reference to instance of Zend_Rest_Client
   *
   * @var Zend_Rest_Client
   */
  protected $_restClient = null;

  
  /**
   * Performs object initializations
   *
   * @param  array $configs
   * @return void
   */
  public function __construct($configs = array()) {
    if (isset($configs['consumerKey'])) {
      $this->setConsumerKey($configs['consumerKey']);
    }
    if (isset($configs['consumerSecret'])) {
      $this->setConsumerSecret($configs['consumerSecret']);
    }
    if (isset($configs['accessToken'])) {
      $this->setAccessToken($configs['accessToken']);
    }
    if (isset($configs['accessTokenSecret'])) {
      $this->setAccessTokenSecret($configs['accessTokenSecret']);
    }
    if (isset($configs['oauthTokenAccess']) && $configs['oauthTokenAccess'] instanceof Zend_Oauth_Token_Access) {
      $this->setOauthTokenAccess($configs['oauthTokenAccess']);
    }
    if (isset($configs['oauthConsumer']) && $configs['oauthConsumer'] instanceof Zend_Oauth_Consumer) {
      $this->setOauthConsumer($configs['oauthConsumer']);
    }
  }
  
  /**
   * Set your consumer key
   *
   * @param  string $consumerKey
   * @return Tumblr
   */
  public function setConsumerKey($consumerKey) {
    $this->_consumerKey = (string) $consumerKey;
    
    return $this;
  }
  
  /**
   * Returns your consumer key
   *
   * @return string
   */
  public function getConsumerKey() {
    return $this->_consumerKey;
  }
  
  /**
   * Set your consumer secret
   *
   * @param  string $consumerSecret
   * @return Tumblr
   */
  public function setConsumerSecret($consumerSecret) {
    $this->_consumerSecret = (string) $consumerSecret;
    
    return $this;
  }
  
  /**
   * Returns your consumer secret
   *
   * @return string
   */
  public function getConsumerSecret() {
    return $this->_consumerSecret;
  }
  
  /**
   * Set access token
   *
   * @param  string $accessToken
   * @return Tumblr
   */
  public function setAccessToken($accessToken) {
    $this->_accessToken = (string) $accessToken;
    
    return $this;
  }
  
  /**
   * Return access token
   *
   * @return string
   */
  public function getAccessToken() {
    return $this->_accessToken;
  }
  
  /**
   * Set access token secret
   *
   * @param  string $accessTokenSecret
   * @return Tumblr
   */
  public function setAccessTokenSecret($accessTokenSecret) {
    $this->_accessTokenSecret = (string) $accessTokenSecret;
    
    return $this;
  }
  
  /**
   * Return access token secret
   *
   * @return string
   */
  public function getAccessTokenSecret() {
    return $this->_accessTokenSecret;
  }
  
  /**
   * Set Zend_Oauth_Token_Access object
   *
   * @param  Zend_Oauth_Token_Access $oauthTokenAccess
   * @return Tumblr
   */
  public function setOauthTokenAccess(Zend_Oauth_Token_Access $oauthTokenAccess) {
    $this->_oauthTokenAccess = $oauthTokenAccess;
    
    $this->setAccessToken($oauthTokenAccess->getToken());
    $this->setAccessTokenSecret($oauthTokenAccess->getTokenSecret());
    
    return $this;
  }
  
  /**
   * Return Zend_Oauth_Token_Access object
   *
   * @return Zend_Oauth_Token_Access
   */
  public function getOauthTokenAccess() {
    return $this->_oauthTokenAccess;
  }
  
  /**
   * Set Zend_OAuth_Consumer object
   *
   * @param  Zend_Oauth_Consumer $oauthConsumer
   * @return Tumblr
   */
  public function setOauthConsumer(Zend_Oauth_Consumer $oauthConsumer) {
    $this->_oauthConsumer = $oauthConsumer;
    
    return $this;
  }
  
  /**
   * Returns a reference to the OAuth consumer, instantiating it if necessary
   *
   * @return Zend_Oauth_Consumer
   */
  public function getOauthConsumer() {
    if (null === $this->_oauthConsumer) {
      $config = array('requestTokenUrl' => self::REQUEST_TOKEN_URI,
		      'authorizeUrl'    => self::AUTHORIZE_URI,
		      'accessTokenUrl'  => self::ACCESS_TOKEN_URI,
                      'consumerKey'     => $this->getConsumerKey(),
                      'consumerSecret'  => $this->getConsumerSecret());
      
      $this->_oauthConsumer = new Zend_Oauth_Consumer($config);
    }
    
    return $this->_oauthConsumer;
  }
  
  /**
   * Returns a reference to the REST client, instantiating it if necessary
   *
   * @return Zend_Rest_Client
   */
  public function getRestClient() {
    if (null === $this->_restClient) {
      require_once 'Zend/Rest/Client.php';
      $this->_restClient = new Zend_Rest_Client(self::BASE_URI); 
    }
    
    return $this->_restClient;
  }
  
  /**
   * Returns a reference to the OAuth client, instantiating it if necessary
   *
   * @return Zend_Oauth_Client
   */
  public function getOauthClient() {
    if (null === $this->_oauthClient) {
      $oauthTokenAccess = $this->getOauthTokenAccess();
      
      if (! $oauthTokenAccess instanceof Zend_Oauth_Token_Access) {
	$oauthTokenAccess = new Zend_Oauth_Token_Access();
	$oauthTokenAccess->setToken($this->getAccessToken());
	$oauthTokenAccess->setTokenSecret($this->getAccessTokenSecret());
      }
      
      $parameters = array('consumerKey'    => $this->getConsumerKey(),
			  'consumerSecret' => $this->getConsumerSecret());
      
      $this->_oauthClient = $oauthTokenAccess->getHttpClient($parameters);
    }
    
    return $this->_oauthClient;
  }
  
  /**
   * Reset the Zend_Oauth_Client object
   *
   * @param Tumblr
   */
  public function resetOauthClient() {
    $this->_oauthClient = null;
    
    return $this;
  }
  
  /**
   * Retrieve Blog Info
   *
   * @param  string $hostname The standard or custom blog hostname
   * @return json   $response
   */
  public function retrieveBlogInfo($hostname) {
    $hostname = $this->_prepareHostname($hostname);
    
    $parameters = array('api_key' => $this->getConsumerKey());
    
    $restClient = $this->getRestClient();
    $restClient->getHttpClient()->resetParameters();
    $response = $restClient->restGet(sprintf(self::PATH_BLOG_INFO, $hostname), $parameters);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Retrieve a Blog Avatar
   *
   * @param  string $hostname The standard or custom blog hostname
   * @param  int    $size     The size of avatar. Must be one of the values: 16|24|30|40|48|64|96|128|512 (default 64)
   * @return json
   */
  public function retrieveBlogAvatar($hostname, $size = 64) {
    $hostname = $this->_prepareHostname($hostname);
    
    $parameters = array('size' => $size);
    $this->_validateBlogAvatarMethod($parameters);
    
    $restClient = $this->getRestClient();
    $restClient->getHttpClient()->resetParameters();
    $response = $restClient->restGet(sprintf(self::PATH_BLOG_AVATAR, $hostname), $parameters);
    
    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Retrieve a Blog's Followers
   *
   * Request parameters include:
   * 
   * # limit  => int How many results to return: 1-20 (default 20)
   * # offset => int Post number to start at: (default 0)
   *
   * @param  string $hostname   The standard or custom blog hostname
   * @param  array  $parameters (limit|offset)
   * @return json
   */
  public function retrieveBlogsFollowers($hostname, $parameters = array()) {
    $hostname = $this->_prepareHostname($hostname);

    $defaultParameters = array('limit'  => 20,
			       'offset' => 0);
    
    $parameters = $this->_prepareParameters($parameters, $defaultParameters);
    $this->_validateBlogsFollowersMethod($parameters);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(sprintf(self::OAUTH_BLOG_FOLLOWERS, $hostname));
    $oauthClient->setParameterGet($parameters);
    $response = $oauthClient->request(Zend_Http_Client::GET);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }

    return $response;
  }
  
  /**
   * Retrieve Published Posts
   *
   * Request parameters include:
   *
   * # type        => string The type of post to return: text|quote|link|answer|video|audio|photo
   * # id          => int    A specific post ID
   * # tag         => string Limits the response to posts with the specified tag
   * # limit       => int    How many results to return: 1-20 (default 20)
   * # offset      => int    Post number to start at: (default 0)
   * # reblog_info => bool   Return reblog information: true|false (default false)
   * # notes_info  => bool   Return notes infomation: true|false (default false)
   * # format      => string The post format to return, other than HTML: text|raw
   *
   * @param  string $hostname   The standard or custom blog hostname
   * @param  array  $parameters
   * @return json
   */
  public function retrievePublishedPosts($hostname, $parameters = array()) {
    $hostname = $this->_prepareHostname($hostname);
    
    $defaultParameters = array('limit'       => 20,
			       'offset'      => 0,
			       'reblog_info' => false,
			       'notes_info'  => false);
    
    $parameters['api_key'] = $this->getConsumerKey();
    $parameters = $this->_prepareParameters($parameters, $defaultParameters);
    $this->_validatePublishedPostsMethod($parameters);
    
    $restClient = $this->getRestClient();
    $restClient->getHttpClient()->resetParameters();
    $response = $restClient->restGet(sprintf(self::PATH_BLOG_POSTS, $hostname), $parameters);
    
    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }

  /**
   * Retrieve Queued Posts
   *
   * @param  string $hostname The standard or custom blog hostname
   * @return json
   */
  public function retrieveQueuedPosts($hostname) {
    $hostname = $this->_prepareHostname($hostname);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(sprintf(self::OAUTH_POSTS_QUEUE, $hostname));
    $response = $oauthClient->request(Zend_Http_Client::GET);
    
    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Retrieve Draft Posts
   *
   * @param  string $hostname The standard or custom blog hostname
   * @return json
   */
  public function retrieveDraftPosts($hostname) {
    $hostname = $this->_prepareHostname($hostname);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(sprintf(self::OAUTH_POSTS_DRAFT, $hostname));
    $response = $oauthClient->request(Zend_Http_Client::GET);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }

  /**
   * Retrieve Submission Posts
   *
   * @param  string $hostname The standard or custom blog hostname
   * @return json
   */
  public function retrieveSubmissionPosts($hostname) {
    $hostname = $this->_prepareHostname($hostname);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(sprintf(self::OAUTH_POSTS_SUBMISSION, $hostname));
    $response = $oauthClient->request(Zend_Http_Client::GET);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * ...
   */
  public function createBlogPost($hostname, $parameters = array()) {
    $hostname = $this->_prepareHostname($hostname);

    $defaultParameters = array('state'    => 'published',
			       'markdown' => false);
    
    $parameters = $this->_prepareParameters($parameters, $defaultParameters);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(sprintf(self::OAUTH_POST, $hostname));
    $oauthClient->setParameterPost($parameters);
    $response = $oauthClient->request(Zend_Http_Client::POST);
    
    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }

    return $response;
  }
  
  /**
   * Edit a Blog Post
   *
   * Request parameters include:
   *
   * # ...
   *
   * @param  string $hostname The standard or custom blog hostname
   * @param  int    $postId   The ID of the post to edit
   * @return json
   */
  public function editBlogPost($hostname, $postId, $parameters = array()) {
    $hostname = $this->_prepareHostname($hostname);
    
    $defaultParameters = array('state'    => 'published',
			       'markdown' => false);
    
    $parameters['id'] = $postId;
    $parameters = $this->_prepareParameters($parameters, $defaultParameters);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(sprintf(self::OAUTH_POST_EDIT, $hostname));
    $oauthClient->setParameterPost($parameters);
    $response = $oauthClient->request(Zend_Http_Client::POST);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }

    return $response;
  }
  
  /**
   * Reblog a Post
   *
   * Request parameters include:
   *
   * # comment => string A comment added to the reblogged post
   * # ...
   *
   * @param  string $hostname  The standard or custom blog hostname
   * @param  int    $postId    The ID of the post to edit
   * @param  int    $reblogKey The reblog key for the reblogged post
   * @return json
   */
  public function reblogPost($hostname, $postId, $reblogKey, $parameters = array()) {
    $hostname = $this->_prepareHostname($hostname);
    
    $defaultParameters = array('state'    => 'published',
			       'markdown' => false);
    
    $parameters['id']         = $postId;
    $parameters['reblog_key'] = $reblogKey;
    $parameters = $this->_prepareParameters($parameters, $defaultParameters);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(sprintf(self::OAUTH_POST_REBLOG, $hostname));
    $oauthClient->setParameterPost($parameters);
    $response = $oauthClient->request(Zend_Http_Client::POST);
    
    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Delete a Post
   *
   * @param  string $hostname The standard or custom blog hostname
   * @param  int    $postId   The ID of the post to delete
   * @return json
   */
  public function deletePost($hostname, $postId) {
    $hostname = $this->_prepareHostname($hostname);
    
    $parameters = array('id' => $postId);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(sprintf(self::OAUTH_POST_DELETE, $hostname));
    $oauthClient->setParameterPost($parameters);
    $response = $oauthClient->request(Zend_Http_Client::POST);
    
    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Get a User's Information
   *
   * @return json
   */
  public function retrieveUsersInfo() {
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(self::OAUTH_USER_INFO);
    $response = $oauthClient->request(Zend_Http_Client::POST);
    
    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Retrieve a User's Dashboard
   *
   * Request parameters include:
   *
   * # limit       => int    How many results to return: 1-20 (default 20)
   * # offset      => int    Post number to start at: (default 0)
   * # type        => string The type of post to return: text|quote|link|answer|video|audio|photo
   * # since_id    => int    Return posts that have appeared after this ID: (default 0)
   * # reblog_info => bool   Return reblog information: true|false (default false)
   * # notes_info  => bool   Return notes infomation: true|false (default false)
   *
   * @param  array $parameters
   * @return json
   */
  public function retrieveUsersDashboard($parameters = array()) {
    $defaultParameters = array('limit'       => 20,
			       'offset'      => 0,
			       'since_id'    => 0,
			       'reblog_info' => false,
			       'notes_info'  => false);
    
    $parameters = $this->_prepareParameters($parameters, $defaultParameters);
    $this->_validateUsersDashboardMethod($parameters);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(self::OAUTH_USER_DASHBOARD);
    $oauthClient->setParameterGet($parameters);
    $response = $oauthClient->request(Zend_Http_Client::GET);
    
    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Retrieve a User's Likes
   *
   * Request parameters include:
   *
   * # limit  => int How many results to return: 1-20 (default 20)
   * # offset => int Post number to start at: (default 0)
   *
   * @param  array $parameters
   * @return json
   */
  public function retrieveUsersLikes($parameters = array()) {
    $defaultParameters = array('limit'  => 20,
			       'offset' => 0);
    
    $parameters = $this->_prepareParameters($parameters, $defaultParameters);
    $this->_validateUsersLikesMethod($parameters);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(self::OAUTH_USER_LIKES);
    $oauthClient->setParameterGet($parameters);
    $response = $oauthClient->request(Zend_Http_Client::GET);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Retrieve the Blogs a User Is Following
   *
   * Request parameters include:
   *
   * # limit  => int How many results to return: 1-20 (default 20)
   * # offset => int Post number to start at: (default 0)
   *
   * @param  array $parameters
   * @return json
   */
  public function retrieveUsersFollowing($parameters = array()) {
    $defaultParameters = array('limit'  => 20,
			       'offset' => 0);
    
    $parameters = $this->_prepareParameters($parameters, $defaultParameters);
    $this->_validateUsersFollowingMethod($parameters);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(self::OAUTH_USER_FOLLOWING);
    $oauthClient->setParameterGet($parameters);
    $response = $oauthClient->request(Zend_Http_Client::GET);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Follow a blog
   *
   * @param  string $blogUrl The URL of the blog to follow
   * @return json
   */
  public function followBlog($blogUrl) {
    $parameters = array('url' => $blogUrl);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(self::OAUTH_USER_FOLLOW);
    $oauthClient->setParameterPost($parameters);
    $response = $oauthClient->request(Zend_Http_Client::POST);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Unfollow a blog
   *
   * @param  string $blogUrl The URL of the blog to unfollow
   * @return json
   */
  public function unfollowBlog($blogUrl) {
    $parameters = array('url' => $blogUrl);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(self::OAUTH_USER_UNFOLLOW);
    $oauthClient->setParameterPost($parameters);
    $response = $oauthClient->request(Zend_Http_Client::POST);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Like a Post
   *
   * @param  int    $postId    The ID of the post to like
   * @param  string $reblogKey The reblog key for the post id
   * @return json
   */
  public function likePost($postId, $reblogKey) {
    $parameters = array('id'         => $postId,
			'reblog_key' => $reblogKey);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(self::OAUTH_USER_LIKE);
    $oauthClient->setParameterPost($parameters);
    $response = $oauthClient->request(Zend_Http_Client::POST);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }
    
    return $response;
  }
  
  /**
   * Unlike a Post
   *
   * @param  int    $postId    The ID of the post to unlike
   * @param  string $reblogKey The reblog key for the post id
   * @return json
   */
  public function unlikePost($postId, $reblogKey) {
    $parameters = array('id'         => $postId,
			'reblog_key' => $reblogKey);
    
    $oauthClient = $this->getOauthClient();
    $oauthClient->setUri(self::OAUTH_USER_UNLIKE);
    $oauthClient->setParameterPost($parameters);
    $response = $oauthClient->request(Zend_Http_Client::POST);

    if ($response->isError()) {
      throw new Exception("An error occurred sending request. Status code: {$response->getStatus()}");
    }

    return $response;
  }
  
  /**
   * Prepare hostname for the request
   *
   * @param  string $hostname The hostname of blog
   * @return string 
   */
  protected function _prepareHostname($hostname) {
    $parsed_url = parse_url($hostname);
    
    if (isset($parsed_url['scheme'])) {
      $hostname = $parsed_url['host'];
    }
    
    return $hostname;
  }
  
  /**
   * Prepare parameters for the request
   *
   * @param  array $parameters        User Parameters
   * @param  array $defaultParameters Default Parameters
   * @return array Merged array of user and default/required parameters
   */
  protected function _prepareParameters(array $parameters, array $defaultParameters = array()) {
    return array_merge($defaultParameters, $parameters);
  }
  
  /**
   * Validate Retrieve Blog Avatar Parameters
   *
   * @param  array $parameters
   * @return void
   */
  protected function _validateBlogAvatarMethod(array $parameters) {
    $validParameters = array('size');
    $this->_compareParameters($parameters, $validParameters);
    
    if (isset($parameters['size'])) {
      $validSize = array(16, 24, 30, 40, 48, 64, 96, 128, 512);
      $this->_validateInArray('size', $parameters['size'], $validSize);
    }
  }

  /**
   * Validate Retrieve Blogs Followers Parameters
   *
   * @param  array $parameters
   * @return void
   */
  protected function _validateBlogsFollowersMethod(array $parameters) {
    $validParameters = array('limit', 'offset');
    $this->_compareParameters($parameters, $validParameters);
  }

  /**
   * Validate Retrieve Published Posts Parameters
   *
   * @param  array $parameters
   * @return void
   */
  protected function _validatePublishedPostsMethod(array $parameters) {
    $validParameters = array('api_key', 'type', 'id', 'tag', 'limit',
			     'offset', 'reblog_info', 'notes_info', 'format');
    $this->_compareParameters($parameters, $validParameters);
  }
  
  /**
   * Validate Retrieve Users Dashboard Parameters
   *
   * @param  array $parameters
   * @return void
   */
  protected function _validateUsersDashboardMethod(array $parameters) {
    $validParameters = array('limit', 'offset', 'type', 'since_id', 'reblog_info', 'notes_info');    
    $this->_compareParameters($parameters, $validParameters);
    
    if (isset($parameters['type'])) {
      $validType = array('text', 'photo', 'quote', 'link', 'chat', 'audio', 'video', 'question');
      $this->_validateInArray('type', $parameters['type'], $validType);
    }
  }
  
  /**
   * Validate Retrieve Users Likes Parameters
   *
   * @param  array $parameters
   * @return void
   */
  protected function _validateUsersLikesMethod(array $parameters) {
    $validParameters = array('limit', 'offset');
    
    $this->_compareParameters($parameters, $validParameters);
  }
  
  /**
   * Validate Retrieve Users Following Parameters
   *
   * @param  array $parameters
   * @return void
   */
  protected function _validateUsersFollowingMethod(array $parameters) {
    $validParameters = array('limit', 'offset');
    
    $this->_compareParameters($parameters, $validParameters);
  }
  
  /**
   * Utility function to check for a difference between two arrays
   *
   * @param  array $parameters      User parameters
   * @param  array $validParameters Valid parameters
   * @return void
   * @throws Exception
   */
  protected function _compareParameters(array $parameters, array $validParameters) {
    $difference = array_diff(array_keys($parameters), $validParameters);
    if ($difference) {
      throw new Exception('The following parameters are invalid: ' . implode(',', $difference));
    }
  }
  
  /**
   * Check that a named value is in the given array
   *
   * @param  string $name  Name associated with the value
   * @param  mixed  $value Value
   * @param  array  $array Array in which to check for the value
   * @return void
   * @throws Exception
   */
  protected function _validateInArray($name, $value, array $array) {
    if (! in_array($value, $array)) {
      throw new Exception("Invalid value for parameter '$name': $value");
    }
  }
}
