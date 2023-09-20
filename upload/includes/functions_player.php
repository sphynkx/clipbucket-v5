<?php
function flashPlayer($param): bool
{
    global $Cbucket;
    $param['player_div'] = $param['player_div'] ? $param['player_div'] : 'videoPlayer';
    $height = $param['height'] ? $param['height'] : config('player_height');
    $width = $param['width'] ? $param['width'] : config('player_width');
    $param['height'] = $height;
    $param['width'] = $width;
    if (!$param['autoplay']) {
        $param['autoplay'] = config('autoplay_video');
    }
    assign('player_params', $param);


    $funcs = $Cbucket->actions_play_video;
    if (!empty($funcs) && is_array($funcs)) {
        foreach ($funcs as $func) {
            if (is_array($func)) {
                $class = $func['class'];
                $method = $func['method'];
                if (method_exists($class, $method)) {
                    $player_code = $class::$method($param);
                }
            } else {
                if (function_exists($func)) {
                    $player_code = $func($param);
                }
            }
            if( !empty($player_code) && !is_bool($player_code) ) {
                assign('player_js_code', $player_code);
                Template(PLAYER_DIR . '/player.html', false);
                return false;
            }
        }
    }
    return false;
}
