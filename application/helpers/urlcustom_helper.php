<?

if ( ! function_exists('app_url'))
{
	function app_url($uri = '', $protocol = NULL)
	{
		if(get_instance()->config->item('app_url')==''){
			$url = get_instance()->config->base_url($uri, $protocol);
		}else{
			$url = get_instance()->config->item('app_url').$uri;
		}

		if (preg_match('/\.(css|js)$/i', $uri)) {
			$filepath = FCPATH.ltrim($uri, '/');
			if (is_file($filepath)) {
				$url .= (strpos($url, '?') === false ? '?' : '&').'v='.filemtime($filepath);
			}
		}

		return $url;
	}
}

?>