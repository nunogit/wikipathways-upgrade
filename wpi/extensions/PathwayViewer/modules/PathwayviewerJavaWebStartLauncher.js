$(function() {
  $('#pwImage').append('QuickLaunch').click(function() {
    // from https://docs.oracle.com/javase/tutorial/deployment/webstart/deploying.html
    // using JavaScript to get location of JNLP
    // file relative to HTML page
    // NOTE: may be neccesary to change the url, depending on how the JNLP generator
    // is made available as an extension.
    var dir = '/w/wpi/extensions/PathwayViewer/'; //location.href.substring(0, location.href.lastIndexOf('/')+1);
    var url = dir + "pathwayviewerJNLPGenerator.php?identifier=" + window.wgTitle + "&version=" + window.wgCurRevisionId;
	deployJava.launchWebStartApplication(location.origin+url);
//    deployJava.createWebStartLaunchButton(url, '1.6.0');
  });
});
