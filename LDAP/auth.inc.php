<?php
/******************************************************************************
 *  SiteBar 3 - The Bookmark Server for Personal and Team Use.                *
 *  Copyright (C) 2004  Ondrej Brablc <http://brablc.com/mailto?o>            *
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

/******************************************************************************
 Original code Andreas Gohr <http://www.splitbrain.org >
 ******************************************************************************/

function authenticate(&$um, $username, $pass, &$added)
{
    if (!function_exists('ldap_connect'))
    {
        $um->error("No LDAP support!");
        return;
    }

    SB_ErrorHandler::useHandler(false);

    $LDAP_CON = @ldap_connect($um->getParamB64('config', 'ldap_server'));

    if (!$LDAP_CON)
    {
        $um->error("Could not connect to LDAP server!");
        SB_ErrorHandler::useHandler();
        return false;
    }

    if ($um->getParam('config', 'ldap_protocol_version_3'))
    {
        if (!@ldap_set_option($LDAP_CON, LDAP_OPT_PROTOCOL_VERSION, 3))
        {
            $um->error("Cannot set protocol version 3!");
            SB_ErrorHandler::useHandler();
            return false;
        }
    }

    //anonymous bind to lookup users
    if (!@ldap_bind($LDAP_CON))
    {
        $um->error("Can not bind anonymously!");
        SB_ErrorHandler::useHandler();
        return false;
    }

    //get dn for given user
    $filter = sprintf($um->getParamB64('config', 'ldap_user_filter'), $username);
    $sr = @ldap_search($LDAP_CON, $um->getParamB64('config', 'ldap_user_tree'), $filter);;
    $result = @ldap_get_entries($LDAP_CON, $sr);

    if ($result['count']!=1)
    {
        SB_ErrorHandler::useHandler();
        return false;
    }

    $dn = $result[0]['dn'];

    // Return information about user
    $added['name'] = $result[0]['givenname'][0].' '.$result[0]['sn'][0];
    $added['comment'] = '';
    $added['email'] = $result[0]['email'][0];

    $ok = @ldap_bind($LDAP_CON,$dn,$pass);
    SB_ErrorHandler::useHandler();

    return $ok;
}

?>