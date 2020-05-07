
<?php

function get_username($email){

    $username = explode('@', $email);
    $username = strtolower($username[0]);
    echo $username;

}

get_username('bynalab@outlook.com');

echo date("F");