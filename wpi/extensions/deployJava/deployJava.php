<?php
global $wgOut;

$wgResourceModules['deployJava'] = array(
    'scripts' => array( 'deployJava.js' ),
    'position' => 'bottom',
    'localBasePath' => __DIR__,
    'remoteExtPath' => 'deployJava'
);

//$this->getOutput()->addModules( 'deployJava' );
$wgOut->addModules( 'deployJava' );

?>
