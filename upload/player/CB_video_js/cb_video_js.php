<?php
/*
	Player Name: VideoJS
	Description: Official CBV5 player
	Author: Arslan Hassan & MacWarrior
	Version: CB5.5.0
    Released: 2021-12-31
    Website: https://github.com/MacWarrior/clipbucket-v5
 */

$cb_video_js = false;

if (!function_exists('cb_video_js'))
{
	define("CB_VJS_PLAYER",basename(dirname(__FILE__)));
	define("CB_VJS_PLAYER_DIR",PLAYER_DIR.DIRECTORY_SEPARATOR.CB_VJS_PLAYER);
	define("CB_VJS_PLAYER_URL",PLAYER_URL.'/'.CB_VJS_PLAYER);
	assign('cb_vjs_player_dir',CB_VJS_PLAYER_DIR);
	assign('cb_vjs_player_url',CB_VJS_PLAYER_URL);

	function cb_video_js($in): bool
    {
		global $cb_video_js;
		$cb_video_js = true;
		
		$vdetails = $in['vdetails'];

		$video_play = get_video_files($vdetails,true,true);
	
		vids_assign($video_play);

		if(!strstr($in['width'],"%")){
			$in['width'] = $in['width'].'px';
        }
		if(!strstr($in['height'],"%")){
			$in['height'] = $in['height'].'px';
        }

		assign('height',$in['height']);
        assign('width',$in['width']);
		assign('player_config',$in);
		assign('vdata',$vdetails);
		
		Template(CB_VJS_PLAYER_DIR.DIRECTORY_SEPARATOR.'cb_video_js.html',false);
		return true;
	}

	/*
	* This Function is written to get quality of current file
	*/
	function get_cbvjs_quality($src): string
    {
		
		$quality = explode('-', $src);
	    $quality = end($quality);
	    $quality = explode('.',$quality);
	    return $quality[0];
	}

	/*
	* This Function is written to set default resolution for cb_vjs_player
	*/
	function get_cbvjs_quality_type($video_files){
		if ($video_files)
		{
            $res = [];
            foreach ($video_files as $file) {
                $res[] = get_cbvjs_quality($file);
            }
            $all_res = $res;

            $player_default_resolution = config('player_default_resolution');

            if (in_array($player_default_resolution, $all_res)){
                $quality = $player_default_resolution;
            } else {
                $quality = 'low';
            }

			return $quality;	
		}
		return false;
	}

	/**
	 * Used to return functions of custom/premium plugins
	 *
	 * @param   : { Array } { function } { videoid }
	 *
	 * @return bool : { functions/Boolean }
	 * @example : get_my_function($params) { will check the required function name and return the case }
	 * @since   : 01st August, 2016 ClipBucket 2.8.1
	 * @author  : Fahad Abbas
	 */
	function get_my_function($params){

		$function = $params['function'];
		$videoid = $params['videoid'];

		if (!$function){
			return False;
		}

		switch ($function) {

			case 'get_ultimate_ads':
				if( defined('CB_ULTIMATE_ADS') && CB_ULTIMATE_ADS == 'installed'){

					global $CbUads;
					$ads_array = array("filter_ad"=>true,"status"=>"1","non_expiry"=>'true');
					$current_ad = $CbUads->get_ultimate_ads($ads_array);
					
					if ( !empty($current_ad) ){
						return $current_ad;
					}
					return false;
				}
				return false;

			case 'get_timeCommnets':
				if( defined('CB_TIMECOMMENTS_PLUGIN') && CB_TIMECOMMENTS_PLUGIN == 'installed'){
					$timecomments = get_timeComments($videoid);
					if (!empty($timecomments)){
						return json_encode($timecomments);
					}
					return false;
				}
				return false;

			case 'get_video_editor':
				if( defined('IA_ADS_INSTALLED') && IA_ADS_INSTALLED == 'installed' ){
					$video_editor_enabled = video_editor_enabled();
					return $video_editor_enabled;
				}
				return false;

			case 'get_svg_manager':
				if( defined('IA_ADS_INSTALLED') && IA_ADS_INSTALLED == 'installed' ){
					$svg_manager = svg_manager();
					return $svg_manager;
				}
				return false;

			case 'get_slot':
				if( defined('IA_ADS_INSTALLED') && IA_ADS_INSTALLED == 'installed' ){
					global $ia_ads;
					$slot_paramas['videoid'] = $videoid;
					
					if ( !empty($_GET['slot_id']) ){
						$slot_paramas['slot_id'] = $_GET['slot_id'];
					} else {
						$slot_paramas['state'] = '1';
					}
					$slot_id = $ia_ads->get_slot($slot_paramas)[0]['slot_id'];
					if (!empty($slot_id)){
						$instances = $ia_ads->get_instance(array("slot_id"=>$slot_id,'order'=>'starttime ASC'));	
					}
					return $instances;
				}
				return false;

			default:
			    break;
		}
	}

	register_actions_play_video('cb_video_js');
}
