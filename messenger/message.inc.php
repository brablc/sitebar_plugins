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

$messageCountNew = messengerGetNewCount($this->um->uid);

if ($messageCountNew!=0)
{
    $readurl = SB_Page::relBaseUrl().'plugin.php?name=messenger';
    $target  = SB_Page::target();
    $img     = SB_Page::relBaseUrl().'plugins/messenger/msg_new.gif';
    $message = '';

    if ($messageCountNew == 1)
    {
        $message = SB_T("You have a new message!");
    }
    else
    {
        $message = SB_T("You have %d new messages!", array($messageCountNew));
    }

    echo <<<_DOC
<div

class="cmnSubTitle"
style="
    margin: 2px 2px 5px 2px;
    padding: 1px;
    width: 180px;
    z-index: 0;
    border: 2px outset gray;
    color: black;
"
>
<a style="width:100%; color:black; text-decoration:none;" href="$readurl" $target>$message<img src="$img"></a>
</div>
_DOC;

}
