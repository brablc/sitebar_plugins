<?php
/******************************************************************************
 *  SiteBar 3 - The Bookmark Server for Personal and Team Use.                *
 *  Copyright (C) 2005  Ondrej Brablc <http://brablc.com/mailto?o>            *
 *                                                                            *
 *  This program is free software; you can redistribute it and/or modify      *
 *  it under the terms of the GNU General Public License as published by      *
 *  the Free Software Foundation; either version 2 of the License, or         *
 *  (at your option) any later version.                                       *
 *                                                                            *
 *  This program is distributed in the hope that it will be useful,           *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of            *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             *
 *  GNU General Public License for more details.                              *
 *                                                                            *
 *  You should have received a copy of the GNU General Public License         *
 *  along with this program; if not, write to the Free Software               *
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA *
 ******************************************************************************/

function authenticate(&$um, $username, $pass, &$added)
{
    if (!function_exists('imap_open'))
    {
        $um->error("No IMAP support!");
        return;
    }

    SB_ErrorHandler::useHandler(false);

    $port = $um->getParam('config', 'imap_port');
    $ssl  = $um->getParam('config', 'imap_ssl');
    $server = $um->getParam('config', 'imap_server');
    $domain = $um->getParam('config', 'imap_default_domain');

    if (strlen($domain) && strstr($username,'@'.$domain))
    {
        $um->error("%s is a default domain, do not use it!", '@'.$domain);
        return;
    }

    if (strstr($username,'@')===false)
    {
        $username = $username . '@' . $domain;
    }

    $mailbox = '{' . $server . ':' . $port;

    if ($ssl)
    {
        $mailbox .= "/ssl/novalidate-cert";
    }

    $mailbox .= '}';

    $IMAP_CON = @imap_open( $mailbox, $username, $pass, OP_HALFOPEN|OP_READONLY );

    if (!$IMAP_CON)
    {
        $um->error("Could not connect to IMAP server!");

        foreach (imap_errors() as $error)
        {
            $um->error($error);
        }

        SB_ErrorHandler::useHandler();
        return false;
    }

    @imap_close($IMAP_CON);

    $added['name'] = $username;
    $added['comment'] = '';

    if (strstr($username,'@'))
    {
        $added['email'] = $username;
    }
    SB_ErrorHandler::useHandler();

    return true;
}

?>