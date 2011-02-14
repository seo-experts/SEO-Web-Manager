<?php
    include '../RMScripts/db.php';
?>

<!--
<form name="company" action="company" method="POST" enctype="multipart/form-data">
    <input type="text" name="Company" value="" />
    <input type="submit" value="Submit Company" name="submit" />
</form> -->



<fieldset>
 <form action="" method="post" class="submitComp" id="cost">

     <input type="text" name="compname" value="" />
<input name="submitComp" class="styledsubmitComp" type="submit" id="submitted" value="Add Company" />
</form>
</fieldset>


   <form action="" method="post" class="compList" id="cost">

     <select class="styledDrop" name="compDrop" id="compDrop">
        <?php

            $query = "SELECT company FROM campaign";
            $result = mysql_query($query)
            or die(mysql_error());
            while ($row = mysql_fetch_assoc($result)) {
            echo '<option value="'.$row['company'].'">'.$row['company'].'</option>';
            }


        ?>
     </select>

<input name="submitDrop" class="styledCompList" type="submit" id="submitted" value="Select Company" />
</form>

<?php
if(isset($_POST["submitComp"])!='')
{
    addCompany($_POST["compname"]);
}
if(isset($_POST["submitDrop"])!='')
{

    displayLinkForm($_POST["compDrop"]);
    displayCompany($_POST["compDrop"]);

}

if(isset($_POST["submitLink"])!='')
{
    addLink();
    displayLinkForm($_POST["compName"]);
    displayCompany($_POST["compName"]);
}





function addCompany($name)
{
    
   $query = "INSERT INTO campaign (company) VALUES ('$name')";


$result = mysql_query($query)
        or die(mysql_error());
}


function addLink()
{

    $compName = $_POST["compName"];
    $linkName =  $_POST["linkName"];
    $comment = $_POST["comment"];
    //if((substr($linkName, 0, 7) =='http://') || (substr($linkName, 0, 3) =='www'))
   // {


    $query = "SELECT linkDomain FROM links";
    $result2 = mysql_query($query)
        or die(mysql_error());
    $linkDomain='';
    if(substr($linkName, 0, 7) =='http://')
    {
        $linkDomain = parse_url_domain($linkName);
    }
    elseif(substr($linkName, 0, 3) =='www')
    {
        $linkDomain = getdomain($linkName);
        
    }
    else
    {
        $linkDomain=$linkName;
    }
    
    
   
   
    $newFlag = 0;
    while ($row = mysql_fetch_assoc($result2)) {
        $dbUrl = $row['linkDomain'];
       
    
        if($dbUrl==$linkDomain)
        {
            $newFlag=1;
        }
    }
  

    if($newFlag == 0)
    {
    $query = "INSERT INTO links (companyId,linkDomain,link,comment) VALUES ('$compName','$linkDomain','$linkName','$comment')";
    $result = mysql_query($query)
        or die(mysql_error());
    }
    else
    {
        echo 'Domain already used for this company';
    }
   // }
    //else
   // {
    //    echo 'Please use domains of the http:// or www type. Also it must end in .com';
   // }
       
}

function displayCompany($name)
{
    echo '<h2>'.$name.'</h2>';
    $query = "SELECT link,comment FROM links WHERE companyId ='$name'";
   
            $result = mysql_query($query)
            or die(mysql_error());
            while ($row = mysql_fetch_assoc($result)) {
            echo $row['link']."     ";
            echo $row['comment'];
            echo '<br/>';
            }
    
}

function displayLinkForm($compName)
{
    echo '<form action="" method="post" class="submitComp" id="cost">


     <label for="linkName">'.$compName.'</label>
     <input type="hidden" name="compName" value="'.$compName.'" />
     <input type="text" name="linkName" value="" />
     <input type="text" name="comment" value="" />
<input name="submitLink" class="styledsubmitComp" type="submit" id="submitted" value="Add Link" />
</form>';
}

function getdomain($url)
{
$explode = explode(".", $url);
$tld = $explode[2];
$tld = explode("/", $tld);
$name = $explode[1];
return $name.'.'.$tld[0];
}


function parse_url_domain ($url) {
$parsed = parse_url($url);
$hostname = $parsed['host'];
if(substr($hostname,-3,3)=='com')
{
    return $hostname;
}
else
{
    return $hostname.'.com';
}
}
?>
