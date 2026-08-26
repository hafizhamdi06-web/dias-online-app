<?

if ( ! function_exists('app_url'))
{
	function app_url($uri = '', $protocol = NULL)
	{
		if(get_instance()->config->item('app_url')==''){
			return get_instance()->config->base_url($uri, $protocol);			
		}else{
			return get_instance()->config->item('app_url').$uri;
		}
	}
}

?>