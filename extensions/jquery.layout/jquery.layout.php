<?php

$wgResourceModules['jquery.layout'] = array(
    'scripts' => array( 'jquery.layout.js' ),
    'position' => 'bottom',
    'localBasePath' => __DIR__,
    'remoteExtPath' => 'jquery.layout'
);

$this->getOutput()->addModules( 'jquery.layout' );

?>
