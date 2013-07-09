<?php

/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: achivements.lang.php 2012-05-27 10:58Z EdsonOrdaz $
 */
 
$l['achivements'] = "Logros";
$l['achivements_description'] = "Gran sistema de logros automaticos.";
$l['desctaks'] = "Agrega automaticamente medallas a los usuarios.";

/***** MODULE_META *****/
$l['posts'] = "Posts";
$l['threads'] = "Temas";
$l['reputation'] = "Reputaci&oacute;n";
$l['timeonline'] = "Tiempo en L&iacute;nea";
$l['regdate'] = "Tiempo de Registrado";
$l['custom'] = "Personalizado";
$l['setts'] = "Configuracion";

$l['permisions_posts'] = "&#191;Pueden configurar logros por posts?";
$l['permisions_threads'] = "&#191;Pueden configurar logros por temas?";
$l['permisions_reputation'] = "&#191;Pueden configurar logros por reputaci&oacute;n?";
$l['permisions_timeonline'] = "&#191;Pueden configurar logros posr tiempo en l&iacute;nea?";
$l['permisions_regdate'] = "&#191;Pueden configurar logros por tiempo de registrado?";
$l['permisions_custom'] = "&#191;Pueden configurar logros personalizados?";
$l['permisions_settings'] = "&#191;Pueden configurar las configuraci&oacute;nes de logros?";

/***** ACHIVEMENTS *****/
/***********************/
//Global achivements
$l['newachivements'] = "Nuevo Logro";
$l['newnnameachivements'] = "Nombre";
$l['newnnameachivements_des'] = "Ingresa el nombre que tendra este logro.";
$l['description'] = "Descripci&oacute;n";
$l['description_des'] = "Ingresa la descripci&oacute;n del logro.";
$l['image'] = "Imagen";
$l['image_des'] = "Selecciona la imagen desde tu ordenador que tendra el logro.";
$l['savenewachivements'] = "Guardar Logro";
$l['notname'] = "No has ingresado un nombre para el logro.";
$l['notdescription'] = "No has ingresado una descripci&oacute;n para el logro.";
$l['errornotcopyachivement'] = "Error al copiar la imagen del logro.";
$l['extimageachivementerror'] = "Extenci&oacute;n de la imagen no es valida.";
$l['errorloadingachivement'] = "Error al cargar la imagen del logro.";
$l['successachivement'] = "El logro se ha guardado correctamente.";
$l['successdeleteachivement'] = "El logro se ha eliminado correctamente.";
$l['noneeditachivement'] = "No existe el logro que tratas de editar.";
$l['confirmdeleteachivements'] = "&#191;Deseas eliminar el logro {1}?";
$l['imageusedactual'] = "Imagen actual";
$l['imageusedactual_des'] = "Esta es la imagen que usas actualmente si no deseas remplazarla deja el campo de abajo vacio.";
$l['imagenewup'] = "Nueva Imagen";
$l['successeditachivements'] = "El logro {1} se ha editado correctamente.";
$l['edittab'] = "Edita el logro.";
$l['hours'] = "Horas";
$l['hour'] = "Hora";
$l['days'] = "D&iacute;as";
$l['day'] = "D&iacute;a";
$l['weeks'] = "Semanas";
$l['week'] = "Semana";
$l['months'] = "Meses";
$l['month'] = "Mes";
$l['years'] = "A&ntilde;os";
$l['year'] = "A&ntilde;o";


//Posts
$l['posts_modules_destab'] = "Aqui se muestran todos los logros por posts. Estos logros se dan de forma automatica cuando el usuario junta los posts requeridos.";
$l['posts_modules'] = "Logros por Posts";
$l['newachivementsbyposts'] = "Nuevo logro por posts";
$l['newpostsachivements'] = "Posts";
$l['newpostsachivements_des'] = "Ingresa el numero de posts que el usuario debe tener para recibir este logro.";
$l['emptyposts_modules'] = "No hay ningun logro por posts";
$l['newachivements_postdes'] = "Agrega un nuevo logro por posts.";
$l['notposts'] = "No has ingresado un numero de posts para dar el logro.";

//Threads
$l['threads_modules_destab'] = "Aqui se muestran todos los logros por temas. Estos logros se dan de forma automatica cuando el usuario junta los posts requeridos.";
$l['threads_modules'] = "Logros por Temas";
$l['newachivementsbythreads'] = "Nuevo logro por temas";
$l['newthreadsachivements'] = "Temas";
$l['newthreadsachivements_des'] = "Ingresa el numero de temas que el usuario debe tener para recibir este logro.";
$l['emptythreads_modules'] = "No hay ningun logro por temas";
$l['newachivements_threadsdes'] = "Agrega un nuevo logro por tema.";
$l['notthreads'] = "No has ingresado un numero de temas para dar el logro.";

//Reputation
$l['reputation_modules_destab'] = "Aqui se muestran todos los logros por reputaci&oacute;n. Estos logros se dan de forma automatica cuando el usuario junta los posts requeridos.";
$l['reputation_modules'] = "Logros por Reputaci&oacute;n";
$l['newachivementsbyreputation'] = "Nuevo logro por reputaci&oacute;n";
$l['newreputationachivements'] = "Reputaci&oacute;n";
$l['newreputationachivements_des'] = "Ingresa el numero de reputaci&oacute;n que el usuario debe tener para recibir este logro.";
$l['emptyreputation_modules'] = "No hay ningun logro por reputaci&oacute;n";
$l['newachivements_reputationdes'] = "Agrega un nuevo logro por reputaci&oacute;n.";
$l['notreputation'] = "No has ingresado un numero de reputaci&oacute;n para dar el logro.";

//Time Online
$l['timeonline_modules_destab'] = "Aqui se muestran todos los logros de tiempo en l&iacute;nea. Estos logros se dan de forma automatica cuando el usuario junta los posts requeridos.";
$l['timeonline_modules'] = "Logros de Tiempo en l&iacute;nea";
$l['newachivementsbytimeonline'] = "Nuevo logro de tiempo en l&iacute;nea";
$l['timeonline_des'] = "Ingresa el numero de tiempo en l&iacute;nea que debe tener el usuario para recibir este logro.";
$l['emptytimeonline_modules'] = "No hay ningun logro de tiempo en l&iacute;nea";
$l['newachivements_timeonlinedes'] = "Agrega un nuevo logro por tiempo en l&iacute;nea.";
$l['nottimeonline'] = "No has ingresado un numero de tiempo en l&iacute;nea para dar el logro.";

//Reg Date
$l['regdate_modules_destab'] = "Aqui se muestran todos los logros de tiempo de registrado. Estos logros se dan de forma automatica cuando el usuario junta los posts requeridos.";
$l['regdate_modules'] = "Logros de Tiempo de registrado";
$l['newachivementsbyregdate'] = "Nuevo logro de tiempo de registrado";
$l['regdate_des'] = "Ingresa el numero de tiempo de registrado que debe tener el usuario para recibir este logro.";
$l['emptyregdate_modules'] = "No hay ningun logro de tiempo de registrado";
$l['newachivements_regdatedes'] = "Agrega un nuevo logro por tiempo de registrado.";
$l['notregdate'] = "No has ingresado un numero de tiempo de registrado para dar el logro.";


//Custom
$l['custom_modules_destab'] = "Aqui se muestran todos los logros personalizados. Estos logros se dan de forma automatica cuando el usuario junta los posts requeridos.";
$l['custom_modules'] = "Logros Personalizados";
$l['newachivementsbycustom'] = "Nuevo logro personalizados";
$l['emptycustom_modules'] = "No hay ningun logro personalizado";
$l['newachivements_customdes'] = "Agrega un nuevo logro personalizado.";
$l['reason'] = "Raz&oacute;n";
$l['reason_des'] = "Ingresa la raz&oacute;n de porque le das el logro al usuario.";
$l['user'] = "Usuario";
$l['user_des'] = "Ingresa el nombre del usuario al que daras este logro personalizado.";
$l['notreason'] = "Debes ingresar una raz&oacute;n para dar el logro.";
$l['notuserexist'] = "No existe el usuario al que quieres dar el logro.";

//Settings
$l['setts_modules_destab'] = "Panel de configuracion del plugin achivements.";
$l['rebuildthreads'] = "Recount Threads";
$l['confirmrecountthreads'] = "Quieres reconstruir el conteo de temas?";
$l['success_recount_threads'] = "El conteo de temas se ha reconstruido correctamente.";
$l['enable'] = "Activar";
$l['enable_des'] = "Activa/desactiva el plugin. Si se desactiva no se pierden los datos de los usuarios (los logros se siguen mostrando a menos que desactives esas opciones).";
$l['showachvprofile'] = "Logros en Perfil";
$l['showachvprofiledes'] = "Mostrar logros en el perfil de los usuarios?";
$l['showachvpostbit'] = "Logros en Postbit";
$l['showachvpostbitdes'] = "Mostrar logros en el postbit de los usuarios?";
$l['sendmpachivements'] = "Enviar MP";
$l['sendmpachivementsdes'] = "Marca si quieres que el usuario reciba un MP al recibir alguno de los siguientes logros";
$l['titlemp'] = "Titulo del MP";
$l['titlempdes'] = "Escribe el titulo del mensaje privado.";
$l['bodymp'] = "Mensaje del MP";
$l['bodympdes'] = "Escribe el mensaje del mensaje privado. Escribe
<ul>
<li>{user} para mostrar el nombre del usuario</li>
<li>{bbname} para mostrar el nombre del foro</li>
<li>{bburl} para mostrar la url del foro</li>
<li>{name} para mostrar el nombre del logro</li>
<li>{description} para mostrar la descripcion/razon del logro</li>
<li>{image} para mostrar la imagen del logro</li>
</ul>";
$l['usersendmp'] = "Escribe el nombre del usuario que enviara el MP.";
$l['usersendmpnotexists'] = "El usuario que enviara el MP no existe.";
$l['notsubjectmp'] = "No has escrito un titulo al MP";
$l['notbodymp'] = "No has escrito el mensaje del MP";
$l['rebuild'] = "Refresacar logros";
$l['rebuilddes'] = "Si eliminaste un logro y al usuario le aparece su logro vacio activa esta opcion, ve a tareas programas y ejecuta la tarea; Despues regresa y desactiva esta opcion.<br /><b><font color='red'>NOTA*</font></b> Al activar esta opcion enviara mensajes privados de a los usuarios de todos sus logros que han obtenido aunque sean viejos (si esta activada la opcion de enviar MP)";
$l['maxpostbit'] = "Logros maximos de postbit";
$l['maxpostbitdes'] = "Ingresa el numero de logros maximos que se mostraran en el postbit del usuario.";

$l['savesettings'] = "Guardar configuracion";


//values MP
$l['subjectvalue'] = "Has recibido un logro";
$l['bodyvalue'] = "Hola [b]{user}[/b] este es un mensaje automatico de [url={bburl}][b]{bbname}[/b][/url] Te agradeceremos que no sea respondido.

-----------------------------

Nombre del logro: {name}
Descripcion: {description}
Imagen del logro: {image}

-----------------------------

El logro se te ha otorgado de forma automatica para quitarlo o ocultarlo de tu postbit/perfil ve a panel de control y editalos";


//\\ *************** NEW VERSION 2.0 *************** //\\
/********************* Extensions **********************/
$l['newextensions'] = "Nueva Extension";
$l['extensions'] = "Extensiones";
$l['extension'] = "Extensi&oacute;n";
$l['version'] = "Version";
$l['created_by'] = "Creado por";
$l['configs'] = "Configuraci&oacute;nes";
$l['permisions_extensions'] = "&#191;Pueden configurar las extensiones?";
$l['permisions_config'] = "&#191;Pueden configurar las configuraciones de extensiones?";
$l['extensions_modules_destab'] = "Aqui se mostrara la lista de extensiones que puedes agregar para extender el funcionamiento del plugin
Achivements. Para instalar una extencion debes meterla a inc/plugins/achivements/extensions/ y aqui se mostrara la extension para
poder activarla.";
$l['configs_modules_destab'] = "Aqui se encuentran las configuraciones de cada extension (si la extension tiene opciones para configurarse).";

$l['controls'] = "Controles";
$l['no_extensions'] = "No hay extensiones para instalar.";
$l['enable_disable'] = "Activar/Desactivar";
$l['activate'] = "Activar";
$l['deactivate'] = "Desactivar";
$l['invalidpostkey'] = "El c&oacute;digo de autorizaci&oacute;n no coincide. Por favor, aseg&uacute;rate de que est&aacute;s accediendo correctamente a la p&aacute;gina.";
$l['error_invalid_extension'] = "La extension seleccionada no existe.";
$l['success_extension_activated'] = "La extension seleccionada se ha activado correctamente.";
$l['success_extension_deactivated'] = "La extension seleccionada se ha desactivado correctamente.";
$l['error_versions'] = "La version de esta extension no es compatible con la version actual del plugin achivements.";


//config
$l['configextensions'] = "Ajustes de extensiones";
$l['groupsconfigs'] = "Grupos de ajustes";
$l['configview'] = "{1} Ajuste";
$l['no_settingsextensions'] = "No hay extensiones con configuraci&oacute;n.";
$l['save_settings'] = "Guardar Ajuste";
$l['error_invalid_gid'] = "No se encontro el ajuste con la busqueda especificada.";
$l['error_no_settings_found'] = "No se encontraron ajustes dentro de este grupo.";
$l['success_settings_updated'] = "Los ajustes se han actualizado correctamente.";

//*******  VERSION 2.1  ************

$l['giveuser'] = "Dar a otro usuario";
$l['notcustomexist'] = "No existe el logro personalizado seleccionado.";
$l['giveachivements'] = "Dar logro";
$l['taskoffline'] = "Dar logros a usuarios offline";
$l['taskofflinedes'] = "Quieres dar logros tambien a los usuarios que no han estado activos antes de la ultima ejecucion de la tarea?";
$l['noexistachivementdelete'] = "No existe el logro que tratas de eliminar.";

//search custom
$l['search_customdes'] = "Busca algun logro ingresando el nombre del logro o el nombre del usuario que recibio el logro. No es necesario ingresar el nombre completo puedes ingresar solo una parte del nombre y aparecera la lista de todos los logros que contengan esas letras.";
$l['search'] = "Buscar";
$l['searchbyach'] = "Buscar por nombre de logro";
$l['searchbyuser'] = "Buscar por nombre de usuario";
$l['nameachivement'] = "Nombre de logro";
$l['nameachivementdes'] = "Ingresa el nombre del logro o una parte del nombre.";
$l['searchbyname'] = "Buscar por Nombre";
$l['searchbyuserbutton'] = "Buscar por Usuario";
$l['nameuser'] = "Nombre de usuario";
$l['nameuserdes'] = "Ingresa el nombre del usuario, Puedes escribir solo un fragmento del nombre y te saldra la opcion de auto complementar.";
$l['emptysearchbyname'] = "No hay ningun logro que contenga '<strong>{1}</strong>'";
$l['emptysearchbyuser'] = "El usuario no tiene ningun logro personalizado.";

//v2.2
//new system custom achivements

$l['give_user'] = "Dar a usuario";
$l['quit_user'] = "Quitar a usuario";
$l['successachcustom'] = "El logro personalizado {1} se entrego correctamente al usuario {2}.";
$l['repeatcustom'] = "El usuario {1} ya tiene el logro personalizado {2}.";
$l['user_desquitcustom'] = "Ingresa el nombre del usuario al que le quitaras este logro personalizado.";
$l['quitcustom'] = "Quitar logro personalizado: {1}";
$l['quitachivement'] = "Quitar logro";
$l['notcustomuser'] = "El usuario {1} no tiene el logro personalizado {2}";
$l['successachivementcustomdelete'] = "Se le ha quitado a {1} el logro {2}";
$l['giveuserform'] = "Dar logro personalizado: {1}";
$l['viewachivements'] = "Ver Logros";
$l['viewachivements_des_tab'] = "Ingresa el nombre del usuario y ve todos sus logros personalizados que tiene.";
$l['user_view'] = "Ingresa el nombre del usuario del que quieres ver todos sus logros";
$l['notuserexistview'] = "No existe el usuario del que quieres ver sus logros.";
$l['custom_modulesbyuser'] = "Logros personalizados de {1}";

//tab log custom achivement
$l['log'] = "Historial";
$l['logdestab'] = "Historial de logros personalizados! Aqui se mostraran los creados, eliminados y los logros que se entregan a usuarios y que se les quita. Tambien si se hace desde panel de moderacion";
$l['emptycustomlog_modules'] = "Esta vacio el historial";
$l['date'] = "Fecha";
$l['information'] = "Informacion";
$l['ip'] = "IP";
$l['clearlog'] = "Vaciar historial";
$l['clearlogconfirm'] = "Quieres vaciar el historial?";
$l['successclearlog'] = "El historial se ha vaciado correctamente.";

$l['logadd'] = "Crear nuevo logro: <strong>{1}</strong>";
$l['logdelete'] = "Eliminar logro: <strong>{1}</strong>";
$l['loggive'] = "El logro <strong>{1}</strong> se ha entregado al usuario {2}";
$l['logrevoke'] = "El logro <strong>{1}</strong> se le ha quitado al usuario {2}";
$l['logtruncate'] = "Historial eliminado";

//new config
$l['canmodcpachs'] = "Dar logros personalizados desde ModCP?";
$l['canmodcpachsdes'] = "Quieres que se puedan dar logros personalizados desde el ModCP? Esto permitira que los moderadores y super moderadores puedan otorgar logros personalizados.";

$l['modcp'] = "Mostrar en ModCP";
$l['modcpdes'] = "Quieres que este logro se muestre en el panel de moderacion? (si esta activada la opcion)";
$l['mod_cp'] = "ModCP";

//edit custom achivement
$l['editcustom'] = "Editar logro personalizado: {1}";
$l['namereason'] = "Nombre / Razon";
$l['langmod'] = "El logro {1} ahora se {2} en el panel de moderacion";
$l['show'] = "Muestra";
$l['hide'] = "Oculta";

//*********** VERSION 2.4 ***************//
$l['permisions_achivements'] = "&#191;Pueden configurar logros por logros?";
$l['achivements_modules'] = "Logros por logros";
$l['achivements_modules_destab'] = "Aqui se muestran todos los logros por logros. Estos logros se dan de forma automatica cuando el usuario junta los logros requeridos.";
$l['newachivements_achivementsdes'] = "Agrega un nuevo logro por logros.";
$l['newachivementsbyachivements'] = "Nuevo logro por logros";
$l['achivementsdes'] = "Selecciona los logros que necesita juntar el usuario para obtener este logro.";
$l['namedescription'] = "Nombre/Descripcion";
$l['emptyachivements_modules'] = "No nay ningun logro por logros.";
$l['notachivements'] = "Sin logros";

?>