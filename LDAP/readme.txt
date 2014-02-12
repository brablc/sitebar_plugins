"LDAP" plugin allows to use LDAP server for user authentication. Fake user will
be created in SiteBar database. It would be later possible to switch back,
however all users with exception of administrator would have the same password:
NOPASSWORD.

Adminstrator account created during "Set Up" is never authorized against LDAP!
Local account is always used.

History
=======

[!] Important
[+] New
[-] Fixed error
[*] Changes

--------------------------------------------------------------------------------
Release 3.3.7_1.1                                               February 18 2006
--------------------------------------------------------------------------------

[*] Adapt to changes of email to username.
[+] Add new configuration switch "LDAP Protocol Version 3".

--------------------------------------------------------------------------------
Release 3.3.4_1.0                                                    July 2 2005
--------------------------------------------------------------------------------

[*] Rename function because of API change.
[!] Changed versioning to start with #1.

--------------------------------------------------------------------------------
Release 3.3_0.2.1                                                February 8 2005
--------------------------------------------------------------------------------

[*] Change directory structure.

--------------------------------------------------------------------------------
Release 3.2.5_0.2                                                    June 6 2004
--------------------------------------------------------------------------------

[-] Fix fatal errors.

--------------------------------------------------------------------------------
Release 3.2.5_0.1                                                     une 4 2004
--------------------------------------------------------------------------------

[!] First release. Based on code from Andreas Gohr.
