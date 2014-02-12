"Mail Gate" is a very simple solution for people who want  to  be  reached  from
web, but do not want to disclose their email address to  avoid  spoiling  it  by
spammers.

If your email has already been spoiled, and you do not want to loose emails  but
do not want to classify spam, you may create an auto responder  with  URL  which
point to a web form in SiteBar. On submit mail will be sent to the user's email.

The URL to the Mail Gate is basically the following:

http://localhost/sitebar/command.php?command=Mail%20Gate&uid=UID

Where UID is user's uid in SiteBar. It is possible to add  additional  parameter
&forward=URL where URL is the place the user will be redirected after successful
email submission.

You will have to enable "Use E-mail Features" in "SiteBar Settings" and you can
customize "Mail Gate" settings from "User Settings".

History
=======

[!] Important
[+] New
[-] Fixed error
[*] Changes

--------------------------------------------------------------------------------
Release 3.3.7_1.0.4                                             February 18 2006
--------------------------------------------------------------------------------

[-] Improved this document.
[*] Adapt to SiteBar API change.

--------------------------------------------------------------------------------
Release 3.3_1.0.3                                                  April 10 2005
--------------------------------------------------------------------------------

[-] Wrong detection whether user can use MailGate or not (PHP error displayed).

--------------------------------------------------------------------------------
Release 3.3_1.0.2                                                February 8 2005
--------------------------------------------------------------------------------

[*] Change directory structure.

--------------------------------------------------------------------------------
Release 3.2_1.0.1                                                  April 27 2004
--------------------------------------------------------------------------------

[*] Release renamed to continue SiteBar min version.
[-] Wrong email validation. Valid emails with "-" in domain name were rejected.
[+] Allow IP address and domain display based on user preference.
[+] Redirect to refering page if available.
[+] Redirect to custom page using &forward=URL
[+] Allow CC to be sent to sender (must be allowed by admin).

--------------------------------------------------------------------------------
Release 1.0                                                         April 7 2004
--------------------------------------------------------------------------------

[!] First public release.
