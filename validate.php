<?php
function isUserName($username)
{
    if(preg_match("/^[a-zA-Z0-9-']*$/",$_POST['username']))
        return true;
}
function isPassWord($password)
{
    if(preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/",$_POST['password']))
        return true;
}


function setValue($labelField)
{
    global $$labelField;
    if(isset($$labelField)) echo $$labelField;
}
?>