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

require_once('./inc/errorhandler.inc.php');
require_once('./inc/page.inc.php');
require_once('./inc/usermanager.inc.php');
require_once('./inc/tree.inc.php');

$um =& SB_UserManager::staticInstance();
$tree =& SB_Tree::staticInstance();

$passkey = $um->getParam('config','service_passkey');

if ( $passkey == '' )
{
    header("HTTP/1.1 401 Unauthorized");
    echo "Please setup passkey in SiteBar Settings!";
    exit;
}

if ($um->getParam('config','service_passkey') != SB_reqVal('passkey'))
{
    header("HTTP/1.1 401 Unauthorized");
    echo "Please use correct passkey!";
    exit;
}

$stat = array();
$um->statistics($stat);
$tree->statistics($stat);

header("Content-Type: application/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r";
?>
<sitebarservice version="1.0">
<version><?php echo SB_CURRENT_RELEASE; ?></version>
<users><?php echo $stat['users']; ?></users>
<links><?php echo $stat['links_total']; ?></links>
</sitebarservice>
