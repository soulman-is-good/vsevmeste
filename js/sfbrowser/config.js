jQuery.sfbrowser.defaults.connector = "php";
jQuery.sfbrowser.defaults.sfbpath = "/js/sfbrowser/";
jQuery.sfbrowser.defaults.base = "../../uploads/";
jQuery.sfbrowser.defaults.previewbytes = "600";
jQuery.sfbrowser.defaults.deny = ("php,php3,phtml,html,htm,js,json,java,exe,cgi,bat,sh").split(",");
jQuery.sfbrowser.defaults.browser = '<body><div id="sfbrowser"><div id="fbbg"></div><div id="fbwin"><div id="winbrowser"><div class="sfbheader"><h3>Файл браузер</h3><div id="loadbar"><div></div><span>Loading</span></div><ul id="sfbtopmenu"><li><form id="fileio" name="form" action="" method="post" enctype="multipart/form-data"><input id="fileToUpload" type="file" size="1" name="fileToUpload" class="input" /></form><a class="textbutton upload" title="Upload"><span>Upload</span></a></li><li><a class="sfbbutton maximizefb" title="Maximize">&nbsp;<span>Maximize</span></a></li><li><a class="sfbbutton cancelfb" title="Cancel">&nbsp;<span>Cancel</span></a></li></ul></div><div class="fbcontent"><div id="fbtable"><table id="filesDetails" cellpadding="0" cellspacing="0"><thead><tr><th class="file">Name</th><th class="type">Type</th><th class="size">Size</th><th class="date">Date</th><th class="dim">Dimensions</th><th class="buttons"></th></tr></thead><tbody><tr><td class="loading" colspan="6"></td></tr></tbody></table></div><div id="fbpreview"></div><div class="sfbbutton choose">Choose</div><div class="sfbbutton cancelfb">Cancel</div></div></div><div id="sfbfooter"></div><div id="resizer">&nbsp;</div></div><ul id="sfbcontext"></ul></div></body>';
jQuery.sfbrowser.defaults.debug = false;
jQuery.sfbrowser.defaults.maxsize = 2 * 1024 * 1024;
jQuery.sfbrowser.defaults.plugins = ['filetree'];