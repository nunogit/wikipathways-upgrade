<?php

$wgResourceModules['mithril'] = array(
    'scripts' => array( 'mithril.js' ),
    'position' => 'bottom',
    'localBasePath' => __DIR__,
    'remoteExtPath' => 'mithril'
);

$this->getOutput()->addModules( 'mithril' );

?>
