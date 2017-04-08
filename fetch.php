<?php

    function get_web_page( $url )
    {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  => "GET",        //set request type post or get
            CURLOPT_POST           => false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }

	if (!isset($_GET["url"]))
	{
		http_response_code(404);
		die();
	}

	$url = $_GET["url"];
	$result = get_web_page( $url );
	
	if (isset($_GET["debug"]))
	{
		print_r($result);
		die();
	}
	
	if ( $result['errno'] != 0 )
	{
		http_response_code(500);
		echo $result['errmsg'];
	}
	else if ( $result['http_code'] != 200 )
	{
		http_response_code($result['http_code']);
		echo $result['http_code'];
	}
	else
	{
		header("Content-Type: " . $result["content_type"]);
		echo $result['content'];
	}
?>