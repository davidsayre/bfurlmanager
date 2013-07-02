<?php

$Module = array( 'name' => 'bfURL' );

$ViewList = array();
$ViewList['list'] = array(
    'script' => 'list.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'SetValid' => 'SetValid',
                                    'SetInvalid' => 'SetInvalid' ),
    'post_action_parameters' => array( 'SetValid' => array( 'URLSelection' => 'URLSelection' ),
                                       'SetInvalid' => array( 'URLSelection' => 'URLSelection' ) ),
    'params' => array( 'ViewMode' ),
    "unordered_params" => array( "offset" => "Offset" ) );
?>
