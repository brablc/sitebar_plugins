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

var SB_messengerImgNew = 'plugins/messenger/msg_new.gif';
var SB_messengerImgRead = 'plugins/messenger/msg_read.gif';

function SB_messengerToggle()
{
    var checkBoxes = document.getElementsByTagName('input');

    for (var i=0; i< checkBoxes.length; i++)
    {
        var cb = checkBoxes[i];
        if (cb.className && cb.className=='checkBox')
        {
            cb.checked = !cb.checked;
        }
    }
}

function SB_messengerToggleItem(img,mid)
{
    var http = SB_xmlHttpGet();

    // We have old browser
    if (!http)
    {
        return;
    }

    http.onreadystatechange = function()
    {
        if (SB_xmlHttpReady(http))
        {
            var div = http.responseText.indexOf(';');
            var mid = http.responseText.substr(0,div);
            var img = http.responseText.substr(div+1);
            var imgObj = document.getElementById('img'+mid);
            imgObj.src = (img=='new'?SB_messengerImgNew:SB_messengerImgRead);
        }
    }

    var url = 'plugin.php?name=messenger&ajax=1&command['+(img.src.indexOf(SB_messengerImgNew)>-1?'mark':'unmark')+']=1&mid['+mid+']=1';
    SB_xmlHttpSend(http, url);
}
