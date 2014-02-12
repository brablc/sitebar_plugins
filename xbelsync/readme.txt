"xbelsync" plugin allows bookmarks synchronization in XBEL format.
It is inteded to be used with 3rd party extensions allowing WebDAV
synchronization in XBEL format:

SyncPlaces - https://addons.mozilla.org/firefox/addon/8426

Configure the plugin using "User Settings->XBELSync Settings" command.

History
=======

[!] Important
[+] New
[-] Fixed error
[*] Changes

--------------------------------------------------------------------------------
Release 3.4_1.3                                               <not released yet>
--------------------------------------------------------------------------------

[?] Decide extension and put in command.inc.php
[*] Adapted for PlaceSync and SyncPlaces extensions (see above).
[-] Compatibility with 3.3.13 version.
[+] [B103] New parameter "Keep Custom Order", by default set to on.

--------------------------------------------------------------------------------
Release 3.3.7_1.1                                               February 18 2006
--------------------------------------------------------------------------------

[+] Support "User" and "Password" fields and HTTP based authentication.
[*] Change callback to show label.
[+] Add backup warning.
[+] Add support for WebPanel and FeedURL attributes by using xbel_mozilla
    writer.

--------------------------------------------------------------------------------
Release 3.3.5_1.0.1                                            September 22 2005
--------------------------------------------------------------------------------

[*] Remove hit counting from uploaded URLs.
[*] Change error handling on upload.

--------------------------------------------------------------------------------
Release 3.3.5_1.0                                              September 18 2005
--------------------------------------------------------------------------------

[!] First release.
