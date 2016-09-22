<?php

$wgResourceModules['jquery.mousewheel'] = array(
    'scripts' => array( 'jquery.mousewheel.js' ),
    'position' => 'bottom',
    'localBasePath' => __DIR__,
    'remoteExtPath' => 'jquery.mousewheel'
);

$this->getOutput()->addModules( 'jquery.mousewheel' );

?>
