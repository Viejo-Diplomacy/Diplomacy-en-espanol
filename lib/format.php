<script type="text/javascript" src="/javascript/jquery.selectify.js"></script>
<script type="text/javascript">
			jQuery( function( $ ) {
				$( '#myDropdown' ).selectify({
					arrowSymbol : ''
				});
				$( 'input[name=changeStyleAttr]' ).click( function() {
					if( this.value == 'Remove Style' ) {
						$( '#myDropdown' ).show().next().hide();
						this.value = 'Add Style';
					} else {
						$( '#myDropdown' ).hide().next().show();
						this.value = 'Remove Style';
					}
				});
			});
</script>

<!--logotipo diplomacy-->
<div ><br />
<a href="./index.php"><img src="images/diplomacy_soldado.png"></a>
</div>
<!--logotipo diplomacy-->


<!-- Enlaces-->
<style>
		.clear{clear:both;}
		.none{display:none}
		.container a {text-decoration: none;}
		ul.uploadifyDropdown{width:120px!important;float:right;position:relative; text-align:center;font-size:16px; font-family: 'Kaushan Script', normal; color:#e0a35e;background:#596170;margin-right:0px;list-style: none;}
		ul.uploadifyDropdown li[name=selected]{font-weight:normal;text-align:center;font-size:16px; color:#e0a35e;border:0px solid #596170;cursor:default;padding:2px;-moz-border-radius:2px;border-radius:2px;webkit-border-radius:2px;background:#596170}
		ul.uploadifyDropdown li[name=selected] span.selectText{padding:2px 4px;}
		ul.uploadifyDropdown li[name=selected] a.changeValue{color:#e0a35e;float:right;border-left:0px solid #ddd;padding:2px 4px;}
		ul.uploadifyDropdown li.generated{text-decoration: none;text-align:center;font-size:16px; border-right:0px solid #ddd;padding:2px 4px;border-left:0px solid #ddd;}
		ul.uploadifyDropdown li.generated:last-child{border-bottom:0px solid #ddd;-moz-border-radius:2px;border-radius:2px;webkit-border-radius:2px}
		ul.uploadifyDropdown li.generated:hover{color:#fff;background:#596170}
		ul.uploadifyDropdown li[name=selected] a.changeValue:focus{outline:0}
</style>
<script type="text/javascript" src="jquery-1.8.3.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
			$('#dropdown').hide();
			$('#drop').click(function() {
				$('#dropdown').toggle();
			});
			
	});
</script>
<div ><br />
<div class="clear"></div>
<div class="container">
<ul class="uploadifyDropdown">
<li><a href="intro.php">Reglamento</a></li>
<li><a href="variants.php">Variantes</a></li>
<li><a href="clasificacion.php">Clasificaci&oacute;n</a></li>
<li><a href="hof.php">Ranking <small>ELO</small></a></li>
<li><a href="liga.php">Liga <img src="images/icons/liga.png"title="Liga"></a></li>
<u><li id="drop" style="cursor:pointer;">M&aacute;s opciones</li></u>
			<div id="dropdown">
				<li><a href="estadisticas.php">Estad&iacute;sticas</a></li>
				<li><a href="profile.php">Buscar usuario</a></li>
				<li><a href="http://www.webdiplo.com/foro/Thread-%C3%8Dndice-de-art%C3%ADculos">Estrategia y articulos</a></li>
				<li><a href="Diplomacia_espanol.pdf">Descargar reglas</a></li>
				<li><a href="help.php">M&aacute;s ayuda</a></li>
				<li><a href="modforum.php">Moderadores <img src="/images/icons/mod-alert.png" height="16px" title="Escribe a los moderadores si tienes cualquier problema en una partida o en el funcionamiento del servidor. Si son dudas de juegos, ponlas en el foro"></a></li>
			</div>



<br><br>
<div><a href="modforum.php"><img src="/images/icons/mod-alert.png" height="16px" title="Escribe a los moderadores si tienes cualquier problema en una partida o en el funcionamiento del servidor. Si son dudas de juegos, ponlas en el foro"></a></div>
</ul>
</div>
<div class="clear"></div>
</div>

<!-- Enlaces-->

<div>
</br>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-8214698600966725";
/* 1webdiplo */
google_ad_slot = "6378931334";
google_ad_width = 120;
google_ad_height = 240;
//-->
</script>
</div>

<div>
<img src="images/artilleria.png">
</br>
</br>
</div>

<div>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</br>
</br>
</br>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-8214698600966725";
/* 2webdiplo */
google_ad_slot = "7855664539";
google_ad_width = 120;
google_ad_height = 240;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</br>
</br>
</br>

</div>