<?php

function getdotenv($key)
{
    $matches = [];
    $env = file_get_contents('.env');
    preg_match('/' . $key . '=(.*)/', $env, $matches);
    return $matches[1];
}

function setdotenv($key, $value)
{
    $env = file_get_contents('.env');
    $newenv = preg_replace('/' . $key . '=(.*)/',  $key . '=' . $value, $env);
    file_put_contents('.env', $newenv);
}
