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
    'prefix' => 'nofavicons',

    // We provide information about authorization on following commands
    'authorization' => array(),

    // We are hooked on following builds
    'build' => array
    (
        'User Settings',
    ),

    'command' => array(),
);

function nofaviconsInit(&$um)
{
    if ($um->getParam('user', 'use_favicons'))
    {
        $um->setParam('user', 'use_favicons', false);
        $um->saveUserParams();
    }
}

function nofaviconsBuildUserSettings(&$cmdWin, &$fields)
{
    unset($fields['Use Favicons']['checked']);
    $fields['Use Favicons']['disabled'] = null;
}

?>
