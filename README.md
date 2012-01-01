# [Tumblr](http://www.tumblr.com "") API(v2) library for PHP 

## USAGE

    require_once 'Tumblr.php';

### Example #1-1 : If you do not have an access token
    $configs = array('consumerKey'    => $consumerKey,
                     'consumerSecret' => $consumerSecret);
    $tumblr = Tubmlr($configs);

    // get Zend_Oauth_Consumer instance
    $oauthConsumer = $tumblr->getOauthConsumer();

    // fetch a request token from Tumblr
    $requestToken = $oauthConsumer->getRequestToken();

    // persist the token to storage
    $_SESSION['TUMBLR_REQUEST_TOKEN'] = serialize($requestToken);

    // redirect the user
    $oauthConsumer->redirect();

### Example #1-2 : If you do not have an access token
    $configs = array('consumerKey'    => $consumerKey,
                     'consumerSecret' => $consumerSecret);
    $tumblr = new Tumblr($configs);

    if (!empty($_GET) && isset($_SESSION['TUMBLR_REQUEST_TOKEN'])) {
      $oauthConsumer = $tumblr->getOauthConsumer();
    
      // fetch a access token from Tumblr
      $accessToken = $oauthConsumer->getAccessToken($_GET, unserialize($_SESSION['TUMBLR_REQUEST_TOKEN']));

      $_SESSION['TUMBLR_ACCESS_TOKEN'] = serialize($accessToken);

      // Now that we have an Access Token, we can discard the Request Token
      $_SESSION['TUMBLR_REQUEST_TOKEN'] = null;
    }
    
    $accessToken = unserialize($_SESSION['TUMBLR_ACCESS_TOKEN']);
    
    // setting up a Zend_Oauth_Token_Access instance
    $tumblr->setOauthTokenAccess($accessToken);
    
    $dashboard = $tumblr->retrieveUsersDashboard();

### Example #2 : If you do not have an access token

    require_once 'Zend/Oauth/Consumer.php';
    
    $configs = array('requestTokenUrl' => 'http://www.tumblr.com/oauth/request_token',
                     'authorizeUrl'    => 'http://www.tumblr.com/oauth/authorize',
                     'accessTokenUrl'  => 'http://www.tumblr.com/oauth/access_token',
                     'consumerKey'     => $consumerKey,
                     'consumerSecret'  => $consumerKeySecret);
    $oauthConsumer = new Zend_Oauth_Consumer($configs);
    
    $configs = array('oauthConsumer' => $oauthConsumer);
    $tumblr = new Tumblr($configs);
    
    $oauthConsumer = $tumblr->getOauthConsumer();
    
    ... // reference to Example #1-1 and #1-2


### Example #3 : If you already have an access token

    $configs = array('consumerKey'       => $consumerKey,
                     'consumerSecret'    => $consumerSecret,
                     'accessToken'       => $accessToken,
                     'accessTokenSecret' => $accessTokenSecret);
    $tumblr = Tumblr($configs);
    
    $dashboard = $tumblr->retrieveUsersDashboard();

### Example #4 : If you already have an access token

    $tumblr = Tumblr();
    
    $tumblr->setConsumerKey($consumerKey);
    $tumblr->setConsumerSecret($consumerSecret);
    $tumblr->setAccessToken($accessToken);
    $tumblr->setAccessTokenSecret($accessTokenSecret);
    
    $dashboard = $tumblr->retrieveUsersDashboard();

## LIBRARY METHODS
### retrieveBlogInfo() - Retrieve Blog Info
    $tumblr = new Tumblr($configs);
    $info   = $tumblr->retrieveBlogInfo($hostname);
### retrieveBlogAvatar() - Retrieve a Blog Avatar
    $tumblr = new Tumblr($configs);
    $avatar = $tumblr->retrieveBlogAvatar($hostname, $size);
### retrieveBlogsFollowers() - Retrieve a Blog's Followers
    $tumblr    = new Tumblr($configs);
    $followers = $tumblr->retrieveBlogsFollowers($hostname, $parameters);
### retrievePublishedPosts() - Retrieve Published Posts
    $tumblr = new Tumblr($configs);
    $posts  = $tumblr->retrievePublishedPosts($hostname, $parameters);
### retrieveQueuedPosts() - Retrieve Queued Posts
    $tumblr = new Tumblr($configs);
    $queued = $tumblr->retrieveQueuedPosts($hostname);
### retrieveDraftPosts() - Retrieve Draft Posts
    $tumblr = new Tumblr($configs);
    $draft  = $tumblr->retrieveDraftPosts($hostname);
### retrieveSubmissionPosts() - Retrieve Submission Posts
    $tumblr     = new Tumblr($configs);
    $submission = $tumblr->retrieveSubmissionPosts($hostname);
### createBlogPost() - Create a New Blog Post
    $tumblr = new Tumblr($configs);
    $post   = $tumblr->createBlogPost($hostname, $parameters);
### editBlogPost() - Edit a Blog Post
    $tumblr = new Tumblr($configs);
    $edit   = $tumblr->editBlogPost($hostname, $postId, $parameters);
### reblogPost() - Reblog a Post
    $tumblr = new Tumblr($configs);
    $reblog = $tumblr->reblogPost($hostname, $postId, $reblogKey, $parameters);
### deletePost() - Delete a Post
    $tumblr = new Tumblr($configs);
    $delete = $tumblr->deletePost($hostname, $postId);
### retrieveUsersInfo() - Get a User's Information
    $tumblr = new Tumblr($configs);
    $info   = $tumblr->retrieveUsersInfo();
### retrieveUsersDashboard() - Retrieve a User's Dashboard
    $tumblr    = new Tumblr($configs);
    $dashboard = $tumblr->retrieveUsersDashboard($parameters);
### retrieveUsersLikes() - Retrieve a User's Likes
    $tumblr = new Tumblr($configs);
    $likes  = $tumblr->retrieveUsersLikes($parameters);
### retrieveUsersFollowing() - Retrieve the Blogs a User Is Following
    $tumblr    = new Tumblr($configs);
    $following = $tumblr->retrieveUsersFollowing($parameters);
### followBlog() - Follow a blog
    $tumblr = new Tumblr($configs);
    $follow = $tumblr->followBlog($blogUrl);
### unfollowBlog() - Unfollow a blog
    $tumblr   = new Tumblr($configs);
    $unfollow = $tumblr->unfollowBlog($blogUrl);
### likePost() - Like a Post
    $tumblr = new Tumblr($configs);
    $like   = $tumblr->likePost($postId, $reblogKey);
### unlikePost() - Unlike a Post
    $tumblr = new Tumblr($configs);
    $unlike = $tumblr->unlikePost($postId, $reblogKey);

## LIBRARY REQUIREMENTS

[Zend_Rest_Client](http://framework.zend.com/manual/en/zend.rest.client.html)
[Zend_Oauth_Consumer](http://framework.zend.com/manual/en/zend.oauth.introduction.html)

## TUMBLR API DOCUMENTATION

Please see Tumblr API appendix for more detailed information:
http://www.tumblr.com/docs/en/api/v2

## DEVELOPMENT ENVIRONMENT
PHP 5.3.8
Zend Framework 1.11.0

## COPYRIGHT

This library is Copyright (c) 2011 aoiaoi and is licensed under the New BSD License.
Tumblr is Copyright (c) Tumblr, Inc. It is NOT affiliated with Tumblr, Inc.
