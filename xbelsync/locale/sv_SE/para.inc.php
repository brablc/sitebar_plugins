<?php

$para['xbelsync::command::tooltip_xbelsync_merge'] = <<<_P
Om EJ vald kommer målmappen raderas före uppladdning.
_P;

$para['xbelsync::command::tooltip_xbelsync_keep_custom_order'] = <<<_P
Om vald kommer ordningen av bokmärken från Firefox behållas.
_P;

$para['xbelsync::command::warning'] = <<<_P
<p style='color:yellow;background:red;font-weight:bold'>Gör en säkerhetskopia av bokmärken för både Firefox och SiteBar (använd länkarna nedan) innan du börjar experimentera med synkronisering!
</p>
<p>
- <a href="%s" %s>Exportguide för Firefox</a><br>
- <a href="%s" %s>Ladda ner SiteBar Bokmärken</a>
</p>
_P;

$para['xbelsync::command::hint_merge'] = <<<_P
<p>
Du rekommenderas slå av ihopslagning i SiteBar och Bookmark Synchronizer.
När du gör en ändring i SiteBar, ladda ner ändringar till Firefox.
När du gör en ändring i Firefox, ladda upp ändringar till SiteBar.
_P;

$para['xbelsync::command::extension'] = <<<_P
<p>
Ladda ner och installera <a target="_content" href="%s">Bookmark Synkroniserings</a>-tillägget
för Firefox. Öppna menyn "Bokmärken->Synkronisera Bokmärken" och fyll i följande värden:
</p>
_P;

$para['xbelsync::command::test'] = <<<_P
<p>
Testa inställningarna genom att klicka <a href="%s" %s>här</a>. Ett dokument i XBEL-format måste laddas ner.
Bookmark Synkroniseringstillägget visar inget meningsfullt felmeddelande vid misslyckad nedladdning - det säger bara
att dokumentet inte är i XBEL-format.
_P;

$para['xbelsync::command::setup_problem'] = <<<_P
Inställningsproblem!

Var god gå till SiteBars webbgränssnitt och välj mapp för synkronisering genom kommandot "Användarinställningar->XBELSync Inställningar"!
_P;

$para['xbelsync::command::access_denied'] = <<<_P
Åtkomst nekad!

Du har inte rättigheter att importera bokmärken till denna mapp.
_P;

?>
