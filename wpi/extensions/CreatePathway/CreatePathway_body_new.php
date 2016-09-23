<?php

class LegacyCreatePathway extends LegacySpecialPage {
	function __construct() {
		parent::__construct( "CreatePathwayPage", "CreatePathway" );
	}
}


class CreatePathway extends SpecialPage {
	private $this_url;
	private $create_priv_msg;

	function __construct(  ) {
		parent::__construct( __CLASS__ );
	}

	function execute( $par ) {
		global $wgRequest, $wgOut, $wpiScriptURL, $wgUser, $wgParser;
		$this->setHeaders();
		$this->this_url = SITE_URL . '/index.php';

		$this->create_priv_msg = wfMessage( 'create_private')->parse();

		if(wfReadOnly()) {
			$wgOut->readOnlyPage( "" );
		}

		if( !$wgUser->isAllowed( 'createpathway' ) ) {
			if( !$wgUser->isLoggedIn() ) { /* Two different messages so we can keep the old error */
				$wgOut->showPermissionsErrorPage( array( array( 'wpi-createpage-not-logged-in' ) ) );
			} else {
				$wgOut->showPermissionsErrorPage( array( array( 'wpi-createpage-permission' ) ) );
			}
			return;
		}

		$uploading = $wgRequest->getVal('upload');
		$private2 = $wgRequest->getVal('private2');

		if($uploading == '1') { //Upload button pressed
			$this->doUpload($uploading, $private2);
		} else {
			$this->showForm();
		}
	}

	function doUpload($uploading, $private2) {
		global $wgRequest, $wgOut, $wpiScriptURL, $wgUser;
		
		try {
			//Check for something... anything
			if ( count( $_FILES ) && isset( $_FILES["gpml"] ) ) {
				$size = $_FILES['gpml']['size'];
				//Check file size
				if ($size > 1000000) {
					$size = $size / 1000000;
					$wgOut->addWikiText("== Warning ==\n<font color='red'>File too large! ''($size MB)''</font>\n'''Please select a GPML file under 1MB.'''\n----\n");
					$wgOut->addWikiText("----\n");
					$this->showForm('','',false,'', $uploading, $private2);
				}
				$file = $_FILES['gpml']['name'];
				//Check for gpml extension
				if(!preg_match("/.gpml$/i", $file)){
					$wgOut->addWikiText("== Warning ==\n<font color='red'>Not a GPML file!</font>\n'''Please select a GPML file for upload.'''\n----\n");
					$wgOut->addWikiText("----\n");
					$this->showForm('','',false,'', $uploading, $private2);
				} else {
					//It looks good, let's create a new pathway!
					$gpmlTempFile = $_FILES['gpml']['tmp_name'];
					$GPML = fopen($gpmlTempFile, 'r');
					$gpmlData = fread($GPML, filesize($gpmlTempFile));
					fclose($GPML);
					$pathway = Pathway::createNewPathway($gpmlData);
					$title = $pathway->getTitleObject();
					$name = $pathway->getName();
					// TODO: check if unique name and species are set in GPML file
					if($private2) $pathway->makePrivate($wgUser);
					$wgOut->addWikiText("'''<font color='green'>Pathway successfully upload!</font>'''\n'''Check it out:  [[$title|$name]]'''\n----\n");
					$this->showForm('','',false,'', $uploading, $private2);
				}
			} else {
				$wgOut->addWikiText("== Warning ==\n<font color='red'>No file detected!</font>\n'''Please try again.'''\n----\n");
				$this->showForm('','',false,'', $uploading, $private2);
			}
		} catch(Exception $e) {
			$wgOut->addWikiText("== Error ==\n<b><font color='red'>{$e->getMessage()}</font></b>\n\n<pre>$e</pre>\n'''Please try again.'''\n----\n");
			$this->showForm('','',false,'', $uploading, $private2);
		}
	}

	function showForm($pwName = '', $pwSpecies = '', $override = '', $private = '', $uploading = 0, $private2 = '') {
		global $wgRequest, $wgOut, $wpiScriptURL;

		if($private2) $private2 = 'CHECKED';
		$html_upload = "<FORM action='$this->this_url' method='post' enctype='multipart/form-data'>
				<table style='margin-left: 0px;'><td>
					<INPUT type='file' name='gpml' size='40'>
					<tr><td>
					<INPUT type='checkbox' name='private2' value='1' $private2> $this->create_priv_msg
					<input type='hidden' name='title' value='Special:CreatePathway'>
					<input type='hidden' name='upload' value='1'>
					<tr><td><INPUT type='submit' value='Upload pathway'></table></FORM>";
			
		$wgOut->addHTML("
			<P><B>This interface lets you upload a pathway in gpml format. If you have a gpml file already, use the interface below.</B><P>
			<P>Here's how to use PathVisio to create a pathway: <OL>
			<LI>Install <a href='http://www.pathvisio.org'>PathVisio</a></LI>
			<LI>Create your pathway. More about editing in PathVisio <a href='http://www.pathvisio.org/documentation/editing-and-viewing-pathways/'>here</a>.</LI>
			<LI>Upload your pathway through the interface below</LI>
			</OL>
			<P><B>Alternative:</B> Upload your pathway directly from PathVisio using the <a href ='http://plugins.pathvisio.org/wp-client'>WikiPathways client plugin</a>.
			<BR>To do this, first install the plugin in the PathVisio Plugin Manager. Then, to upload your pathway, go to “Plugins > WikiPathways > Upload New”.
						<HR>
						<FORM><TABLE width='100%'><TBODY>
						$html_upload  
						</TBODY></TABLE></FORM>"
					);
	}
}
