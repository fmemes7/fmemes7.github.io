<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('getDomainOnly')) { 
	function getDomainOnly($url) {
		$url=str_replace("www.","",$url);
		$url=str_replace("WWW.","",$url);		
	    if (!preg_match("@^https?://@i", $url) && !preg_match("@^ftps?://@i", $url)) {
	        $url = "http://" . $url;
	    }
	  	$parsed=@parse_url($url);		
		return $parsed['host'];	  
	}
}
if ( ! function_exists('convertToGridData'))
{   
    function convertToGridData($totalInfo,$totalResult=10) 
    {
        $result["total"] = $totalResult;
		$items = array();		
		foreach($totalInfo as $index=>$info){
			if($index!=='extra_index'){
				$info_obj=(object)$info;
				array_push($items, $info_obj);
			}			
		}
		$result["rows"] = $items;
		return json_encode($result);
    }
}