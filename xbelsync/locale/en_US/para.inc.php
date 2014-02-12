<?php

$para['xbelsync::command::tooltip_xbelsync_merge'] = <<<_P
If unchecked, the target folder will be completely deleted before upload.
_P;

$para['xbelsync::command::tooltip_xbelsync_keep_custom_order'] = <<<_P
If checked, the order of bookmarks from Firefox will be kept.
_P;

$para['xbelsync::command::warning'] = <<<_P
<p style='color:yellow;background:red;font-weight:bold'>
Make backup of both Firefox and SiteBar bookmarks (use links below) before playing with synchronization!
</p>
<p>
- See <a href="%s" %s>Export Guide for Firefox</a><br>
- <a href="%s" %s>Download SiteBar Bookmarks</a>
</p>
_P;

$para['xbelsync::command::hint_merge'] = <<<_P
<p>
It is recommended to switch Merging in both SiteBar and Bookmark Synchronizer off.
When you do a change in SiteBar, download changes to your Firefox, when you do a change
in Firefox, upload it to SiteBar.
_P;

$para['xbelsync::command::extension'] = <<<_P
<p>
Download and install the <a target="_content" href="%s">Bookmarks Synchronizer</a> extension
for Firefox. Open menu "Bookmarks->Synchronize Bookmarks" and fill in following values:</p>
_P;

$para['xbelsync::command::test'] = <<<_P
<p>
Test the settings by clicking <a href="%s" %s>here</a>. A document in XBEL format must be downloaded.
Bookmark Synchronizer extension does not display any meaningful error messages on failed download - it just says,
that document is not in XBEL format.
_P;

$para['xbelsync::command::setup_problem'] = <<<_P
Setup problem!

Please go to SiteBar web interface and select folder for
synchronization using "User Setting -> XBELSync Settings" command!
_P;

$para['xbelsync::command::access_denied'] = <<<_P
Access denied!

You do not have right to import bookmarks into this folder!
_P;

?>
