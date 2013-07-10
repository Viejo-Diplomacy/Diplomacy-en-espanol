<?php

require_once('header.php');

libHTML::starthtml();

print '<script type="text/javascript" src="contrib/js/tablekit.js"></script>';
print libHTML::pageTitle('LIGA ','Clasificaci&oacute;n y partidas de la temporada <strong>Primavera 2013</strong>. M&aacute;s informaci&oacute;n en <a href="http://www.webdiplo.com/foro/thread-64.html" target="_blank"> este enlace</a>');
 
print '<center><iframe width="750" height="600" frameborder="0" src="https://docs.google.com/spreadsheet/pub?key=0AqmQd0O6jvrWdDhDbFJmU2UxS1pCUGo0SHA2SENyRnc&single=true&gid=0&range=A1%3AP32&output=html&widget=false"></iframe></center>';
print '<center><iframe width="750" height="600" frameborder="0" src="https://docs.google.com/spreadsheet/pub?key=0AqmQd0O6jvrWdDhDbFJmU2UxS1pCUGo0SHA2SENyRnc&single=true&gid=4&range=A1%3AK29&output=html&widget=false"></iframe></center>';

print '<center><iframe width="750" height="700" frameborder="0" src="https://docs.google.com/spreadsheet/pub?key=0AqmQd0O6jvrWdFJaQ1VfRkphWGFLcmhXSFNFeVZDRVE&single=true&gid=9&range=A1%3AN41&output=html&widget=true"></iframe></center>';
print '<center><iframe width="750" height="700" frameborder="0" src="https://docs.google.com/spreadsheet/pub?key=0AqmQd0O6jvrWdFJaQ1VfRkphWGFLcmhXSFNFeVZDRVE&single=true&gid=8&range=A1%3AN41&output=html&widget=true"></iframe></center>';

print '</div>';
libHTML::footer();

?>
