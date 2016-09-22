<?php

$wgResourceModules['d3'] = array(
    'scripts' => array( 'd3.js' ),
    'position' => 'bottom',
    'localBasePath' => __DIR__,
    'remoteExtPath' => 'd3'
);

$this->getOutput()->addModule( 'd3' );

?>
