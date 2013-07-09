<?php
 error_reporting(E_ALL);

 $to = 'albertonucagmail.com';

 if(mail($to, 'Testing mail', 'This is a mailing test to see if PHP mail works.'))
 {
      echo 'Mail was sent by PHP';
 }
 else
 {
      echo 'PHP could not send the mail';
 }
 ?>