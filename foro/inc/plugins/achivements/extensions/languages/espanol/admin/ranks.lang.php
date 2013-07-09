<?php

/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: ranks.lang.php 2012-04-29 10:58Z EdsonOrdaz $
 */
 
$l['ranks'] = "Rangos";
$l['ranks_plug_description'] = "Agrega rangos a los usuarios al obtener ciertos logros.";
$l['ranks_tab_des'] = "Lista de rangos. los rangos se muestra por nivel de forma ascendente o descendente segun tu lo configures.";

//new rank
$l['newrank'] = "Nuevo Rango";
$l['newrank_tab_des'] = "Agrega un nuevo rango por logros. aqui se mostraran los logros creados (logros por posts, temas, reputacion, tiempo en linea y tiempo de registrado, no por logros personalizados).";
$l['save'] = "Guardar";
$l['none'] = "Ninguno";

$l['nameofrank'] = "Nombre del rango";
$l['nameofrankdes'] = "Ingresa el nombre del rango.";
$l['descriptionofrank'] = "Descripcion";
$l['descriptionofrankdes'] = "Ingresa la descripcion de este rango.";
$l['newrankpostsdes'] = "Selecciona el logro por posts que debe tener el usuario.";
$l['newrankthreadsdes'] = "Selecciona el logro por posts que debe tener el usuario.";
$l['newrankreputationdes'] = "Selecciona el logro por reputacion que debe tener el usuario.";
$l['newranktimeonlinedes'] = "Selecciona el logro por tiempo en linea que debe tener el usuario.";
$l['newrankregdatedes'] = "Selecciona el logro por tiempo de registrado que debe tener el usuario.";

$l['imagedesnewrank'] = "Selecciona la imagen que se les dara como rango desde tu ordenador.";
$l['level'] = "Nivel";
$l['levelnewrank'] = "Ingresa el nivel que tendra este rango (El nivel no puede quedar en cero ni ser un nivel repetido).";

//validate and process rank
$l['notname'] = "No has escrito un nombre del rango.";
$l['notdescription'] = "Debes escribir una descripcion del rango.";
$l['notlevel'] = "El nivel del rango debe ser mayor a cero.";
$l['notimage'] = "No has seleccionado una imagen de tu ordenador.";
$l['repeatlevel'] = "El rango {1} ya tiene el nivel {2}.";
$l['successnewrank'] = "El rango se ha creado correctamente.";
$l['notcopyimage'] = "No se ha podido copiar la imagen del rango.";
$l['extnotvalidateimg'] = "Extension de la imagen no es valida.";
$l['notloadingimage'] = "No se ha podido cargar la imagen.";


//home ranks
$l['namedescription'] = "Nombre/Descripcion";
$l['emptyranks'] = "No hay ningun rango creado.";
$l['deletsuccessrank'] = "El rango se ha eliminado correctamente.";
$l['confirmdeleterankpoop'] = "Quieres eliminar el rango {1}?";
$l['successorderascdesc'] = "Los rangos se muestran por nivel de forma {1}";
$l['asc'] = "ascendente";
$l['desc'] = "descendente";
$l['showdesctable'] = "Mostrar en orden descendente";
$l['showasctable'] = "Mostrar en orden ascendente";

//edit rank
$l['notexistrankedit'] = "No existe el rango seleccionado.";
$l['imageactual'] = "Imagen Actual";
$l['imageactualdes'] = "Si no quieres cambiar esta imagen deja el campo de abajo vacio para mantener esta imagen.";
$l['successeditrank'] = "El rango se ha editado correctamente.";
$l['editrank'] = "Editar Rango";
$l['editrank_tab_des'] = "Edita el rango cambiando su configuracion. Ejecuta de nuevo la tarea para quitar este rango a quien no junte los logros.";



?>