"IMAP" plugin allows to use IMAP server for user authentication. Fake user will
be created in SiteBar database. It would be later possible to switch back,
however all users with exception of administrator would have the same password:
NOPASSWORD.

Adminstrator account created during "Set Up" is never authenticated against IMAP!
Local account is always used.

History
=======

[!] Important
[+] New
[-] Fixed error
[*] Changes

--------------------------------------------------------------------------------
Release 3.3.7_1.1                                                     May 1 2006
--------------------------------------------------------------------------------

[+] Added new parameter "IMAP Default Domain". When specified, users having
    this domain must not use it. 
    
    !!! If you turn this on, when already having users, you will have to 
    !!! change user names so that they do not contain the domain.

--------------------------------------------------------------------------------
Release 3.3.7_1.0.1                                             February 18 2006
--------------------------------------------------------------------------------

[*] Adapt to changes of email to username.
[-] [B80] Wrong parameter name used for SSL.

--------------------------------------------------------------------------------
Release 3.3.4_1.0                                                   June 28 2005
--------------------------------------------------------------------------------

[!] First release.
