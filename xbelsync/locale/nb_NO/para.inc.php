<?php

$para['xbelsync::command::tooltip_xbelsync_merge'] = <<<_P
Hvis uavkrysset, vil målmappe bli helt slettet før opplasting.
_P;

$para['xbelsync::command::tooltip_xbelsync_keep_custom_order'] = <<<_P
Hvis avkrysset, så beholdes rekkefølgen på bokmerkene fra FireFox
_P;

$para['xbelsync::command::warning'] = <<<_P
<p style='color:yellow;background:red;font-weight:bold'>
Ta backup av bokmerkene både fra Firefox og SiteBar (bruk lenka under) før du prøver deg på synkronisering!
</p>
<p>
- <a href="%s" %s>Eksportguide for Firefox</a><br>
- <a href="%s" %s>Last ned bokmerkene fra SiteBar</a>
</p>
_P;

$para['xbelsync::command::hint_merge'] = <<<_P
<p>
Det anbefales å skru av "Slå sammen" både i SiteBar og i Bokmerkesynkronisering.
Når du gjøre en forandring i SiteBar, last ned endringene til FireFox. Når du 
gjør en endring i Firefox, last opp til SiteBar.
_P;

$para['xbelsync::command::extension'] = <<<_P
<p>
Last ned og installer ekstensjonen <a target="_content" href="%s">Bokmerkesynkronisering</a> for Firefox.
Åpen så menyen "Bokmerker->Synkroniser bokmerker" og fyll inn følgende verdier:</p>
_P;

$para['xbelsync::command::test'] = <<<_P
<p>
Test innstillingene ved å klikke <a href="%s" %s>her</a>. Et dokument i formatet XBEL må lastes ned.
Ekstensjonen Bokmerkesynkronisering viser ingen meningsfulle feilmeldinger når nedlasting feiler -
det sier bare at dokumentet ikke er i formatet XBEL.
_P;

$para['xbelsync::command::setup_problem'] = <<<_P
Oppsettsproblemer!

Gå til SiteBars webgrensesnitt og velg mappe for synkronisering ved å bruke
kommandoen "Brukerinnstillinger -> XBELSync-innstillinger"!
_P;

$para['xbelsync::command::access_denied'] = <<<_P
Ingen tilgang!

Du har ingen rett til å importere bokmerker til denne mappa!
_P;

?>
