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

$plugin = array
(
    'prefix' => 'service',

    'authorization' => array(),

    // We are hooked on following builds
    'build' => array
    (
        'SiteBar Settings',
    ),

    // We are hooked on following executions
    'command' => array
    (
        'SiteBar Settings',
    ),
);

function serviceBuildSiteBarSettings(&$cmdWin, &$fields)
{
    $fields['Service Passkey'] = array('name'=>'service_passkey');
}

function serviceCommandSiteBarSettings(&$cmdWin)
{
    $values = array
    (
        'service_passkey',
    );

    foreach ($values as $check)
    {
        $cmdWin->um->setParam('config',$check, SB_reqVal($check));
    }

    $cmdWin->um->saveConfiguration();
}

?>
