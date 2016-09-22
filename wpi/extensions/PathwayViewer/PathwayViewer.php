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
		// TODO shouldn't the following three belong in "dependencies"?
		'./modules/d3.js',
		'./modules/mithril.js',
		'./modules/deployJava.js',
		// TODO remove the polyfill bundle below once the autopolyfill
		// work is complete. Until then, leave it as-is.
		'./modules/polyfills.bundle.min.js',
		'./modules/pvjs.core.min.js',
		'./modules/pvjs.custom-element.min.js',
		'./modules/PathwayviewerJavaWebStartLauncher.js',
		'./modules/PathwayViewer.js',
	),
	//'styles' => array( 'modules/ext.PathwayViewer.css' ),
/*
	'messages' => array(
		'myextension-foo-label',
	),
//*/
	'dependencies' => array(
		// TODO how does this work? I tried creating these as modules, but they aren't recognized.
		/*
		'jquery.mousewheel',
		'jquery.layout',
		'd3',
		'mithril',
		// JS to trigger Java webstart for currently visited pathway
		'deployJava',
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
