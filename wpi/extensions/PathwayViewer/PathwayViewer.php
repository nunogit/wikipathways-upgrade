<?php
require_once('DetectBrowserOS.php');

/*
 * Enable pvjs (interactive pathway viewer/editor)
 * Used in both pathway page and widget.
 */

$wgExtensionFunctions[] = 'wfPathwayViewer';
$wgHooks['LanguageGetMagic'][]  = 'wfPathwayViewer_Magic';

$wgResourceModules['PathwayViewer'] = array(
	'position' => 'bottom',
	'scripts' => array(
		// TODO remove the polyfill bundle below once the autopolyfill
		// work is complete. Until then, leave it as-is.
		'./modules/polyfills.bundle.min.js',
		'./modules/pvjs.core.min.js',
		'./modules/pvjs.custom-element.min.js',
		'./modules/PathwayViewer.js',
		// JS related to Java webstart for currently visited pathway
		'./modules/PathwayViewerJavaWebStartLauncher.js',
		'./modules/deployJava.js',
	),
	//'styles' => array( 'modules/ext.PathwayViewer.css' ),
/*
	'messages' => array(
		'myextension-foo-label',
	),
//*/
	'dependencies' => array(
		// This wikibits dependency is actually WORKING; TODO reuse this pattern 
		// Refer to /resources/Resources.php for other deps to include here!
		'mediawiki.legacy.wikibits',
		'd3',
		'mithril',
		/*
		'jquery.mousewheel',
		'jquery.layout',
		//*/
	),

	'localBasePath' => __DIR__,
	'remoteExtPath' => 'wpi/extensions/PathwayViewer',
);

function wfPathwayViewer() {
	global $wgParser;
	$wgParser->setFunctionHook( "PathwayViewer", "PathwayViewer::enable" );
}

function wfPathwayViewer_Magic( &$magicWords, $langCode ) {
	$magicWords['PathwayViewer'] = array( 0, 'PathwayViewer' );
	return true;
}

class PathwayViewer {
	static function enable(&$parser, $pwId, $imgId) {
		global $wgOut, $wgStylePath, $wpiJavascriptSources, $wgScriptPath,
			$wpiJavascriptSnippets, $jsRequireJQuery, $wgRequest, $wgJsMimeType;

		$wgOut->addModules( 'PathwayViewer' );
		try {
			$revision = $wgRequest->getval('oldid');

			$pathway = Pathway::newFromTitle($pwId);

			if($revision) {
				$pathway->setActiveRevision($revision);
			}
		} catch(Exception $e) {
			return "invalid pathway title: $e";
		}
	}

}
