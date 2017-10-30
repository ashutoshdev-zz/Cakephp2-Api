<?php
print_r($_POST);
$postdata = (file_get_contents("php://input"));
  //print_r($postdata);
//echo json_encode($postdata);
require_once('lib/OAuth.php');
$CONSUMER_KEY = "K8yX_N0p8P_B5gnb3q0g-A";
$CONSUMER_SECRET = "oCynrfgzRLv0Pyvfwgjp3GxgDOw";
$TOKEN = "cecjWjVMaul9-4O9lSgfjtppUWWa2Dt6";
$TOKEN_SECRET = "nU_CTk0jHdZHKIBkqV3MadwNSuk";
$lat=$postdata->data->Restaurant->latitude;
$lng=$postdata->data->Restaurant->longitude;

$API_HOST = 'api.yelp.com';
$DEFAULT_TERM = "restaurants";
$DEFAULT_LL= $postdata->data->Restaurant->latitude.','.$postdata->data->Restaurant->latitude;
//$DEFAULT_LL= '19.432608,-99.133209';
$SEARCH_LIMIT = 20;

$SEARCH_PATH = '/v2/search/';
$BUSINESS_PATH = '/v2/business/';


/** 
 * Makes a request to the Yelp API and returns the response
 * 
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the APi after the domain
 * @return   The JSON response from the request      
 */
function request($host, $path) {
    $unsigned_url = "https://" . $host . $path;

    // Token object built using the OAuth library
    $token = new OAuthToken($GLOBALS['TOKEN'], $GLOBALS['TOKEN_SECRET']);

    // Consumer object built using the OAuth library
    $consumer = new OAuthConsumer($GLOBALS['CONSUMER_KEY'], $GLOBALS['CONSUMER_SECRET']);

    // Yelp uses HMAC SHA1 encoding
    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

    $oauthrequest = OAuthRequest::from_consumer_and_token(
        $consumer, 
        $token, 
        'GET', 
        $unsigned_url
    );
    
    // Sign the request
    $oauthrequest->sign_request($signature_method, $consumer, $token);
    
    // Get the signed URL
    $signed_url = $oauthrequest->to_url();
    
    // Send Yelp API Call
    try {
        $ch = curl_init($signed_url);
        if (FALSE === $ch)
            throw new Exception('Failed to initialize');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);

        if (FALSE === $data)
            throw new Exception(curl_error($ch), curl_errno($ch));
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (200 != $http_status)
            throw new Exception($data, $http_status);

        curl_close($ch);
    } catch(Exception $e) {
        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);
    }
    
    return $data;
}

/**
 * Query the Search API by a search term and location 
 * 
 * @param    $term        The search term passed to the API 
 * @param    $location    The search location passed to the API 
 * @return   The JSON response from the request 
 */
function search($term, $location,$ll ) {
    $url_params = array();
    
    $url_params['term'] = $term ?: $GLOBALS['DEFAULT_TERM'];
    $url_params['location'] = $location?: $GLOBALS['DEFAULT_LOCATION'];
    $url_params['ll'] = $ll?: $GLOBALS['DEFAULT_LL'];
    $url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];
    $search_path = $GLOBALS['SEARCH_PATH'] . "?" . http_build_query($url_params);
    
    return request($GLOBALS['API_HOST'], $search_path);
}

/**
 * Query the Business API by business_id
 * 
 * @param    $business_id    The ID of the business to query
 * @return   The JSON response from the request 
 */
//function get_business($business_id) {
  //  $business_path = $GLOBALS['BUSINESS_PATH'] . urlencode($business_id);
    
  //  return request($GLOBALS['API_HOST'], $business_path);
//}

/**
 * Queries the API by the input values from the user 
 * 
 * @param    $term        The search term to query
 * @param    $location    The location of the business to query
 */
function query_api($term, $location, $ll) {     
    $response = json_decode(search($term, $location, $ll));
     //print_r($response);exit;

    //$business_id = $response->businesses[2]->id;
    
   // print sprintf(
      //  "%d businesses found, querying business info for the top result \"%s\"\n\n",         
      //  count($response->businesses),
      //  $business_id
    //);
    
   // $response = get_business($business_id);
    
    //print sprintf("Result for business \"%s\" found:\n", $business_id);
   // print "$response\n";

    echo json_encode($response);
}

/**
 * User input is handled here 
 */
$longopts  = array(
    "term::",
    "location::",
);
    
$options = getopt("", $longopts);

$term = $options['term'] ?: '';
$location = $options['location'] ?: '';

query_api($term, $location,$ll);
?>
