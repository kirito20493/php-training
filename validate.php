<?php
function isUserName($username)
{
    if(preg_match("/^[a-zA-Z0-9-']*$/",$_POST['username']))
        return true;
}


function setValue($labelField)
{
    global $$labelField;
    if(isset($$labelField)) echo $$labelField;
}
?>