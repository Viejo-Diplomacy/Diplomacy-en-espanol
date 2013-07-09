<?php

/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: ranks.lang.php 2012-04-29 10:58Z EdsonOrdaz $
 */
 
$l['ranks'] = "Ranks";
$l['ranks_plug_description'] = "Add ranges for users to get certain achievements.";
$l['ranks_tab_des'] = "List of ranges. ranges shown by level up or down according to your configure it.";

//new rank
$l['newrank'] = "New Rank";
$l['newrank_tab_des'] = "Add a new range of achievements. achievements will be shown here created (posts achievements, issues, reputation, time online and time of registration, not custom achievements).";
$l['save'] = "Save";
$l['none'] = "None";

$l['nameofrank'] = "Name of rank";
$l['nameofrankdes'] = "Enter the name of the rank.";
$l['descriptionofrank'] = "Description";
$l['descriptionofrankdes'] = "Enter a description of this rank.";
$l['newrankpostsdes'] = "Select the achievement of posts that should be the user.";
$l['newrankthreadsdes'] = "Select the achievement of threads that should be the user.";
$l['newrankreputationdes'] = "Select the achievement of reputation that should be the user.";
$l['newranktimeonlinedes'] = "Select the achievement of time online that should be the user.";
$l['newrankregdatedes'] = "Select the achievement of time registered that should be the user.";

$l['imagedesnewrank'] = "Select the image you will be given as a range from your computer.";
$l['level'] = "Level";
$l['levelnewrank'] = "Enter the level you will have this range (the level can not be at zero level or be repeated).";

//validate and process rank
$l['notname'] = "You did not enter a rank name.";
$l['notdescription'] = "You must enter a description of the rank.";
$l['notlevel'] = "The level of the rank must be greater than zero.";
$l['notimage'] = "You have not selected an image from your computer.";
$l['repeatlevel'] = "The rank {1} already has the level {2}.";
$l['successnewrank'] = "The rank has been created successfully.";
$l['notcopyimage'] = "Unable to copy the image of the rank.";
$l['extnotvalidateimg'] = "Extension of the image is not valid.";
$l['notloadingimage'] = "Unable to load the image.";


//home ranks
$l['namedescription'] = "Name/Description";
$l['emptyranks'] = "There is no established range.";
$l['deletsuccessrank'] = "The rank was successfully deleted.";
$l['confirmdeleterankpoop'] = "Want to delete the range {1}?";
$l['successorderascdesc'] = "The ranges are shown by level of form {1}";
$l['asc'] = "ascendant";
$l['desc'] = "descending";
$l['showdesctable'] = "Display in descending order";
$l['showasctable'] = "View in ascending order";

//edit rank
$l['notexistrankedit'] = "There is no range selected.";
$l['imageactual'] = "Current picture";
$l['imageactualdes'] = "If you do not want to change this picture leaves the empty field below to maintain this image.";
$l['successeditrank'] = "The range has been edited successfully.";
$l['editrank'] = "Edit Rank";
$l['editrank_tab_des'] = "Edit the range changing its configuration. Run the task again to remove this range who do not bundle the achievements.";



?>