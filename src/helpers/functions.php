<?php

function basePath()
{
    return realpath(__DIR__ . '/../../');
}

/**
 * @deprecated
 * @return mixed
 */
function guestFileExtension()
{
    $key = array_keys($_FILES)[0];
    $filename = $_FILES[$key]['name'];
    return pathinfo($filename, PATHINFO_EXTENSION);
}

/**
 * @deprecated
 * @return mixed
 */
function getFileTmpLocation()
{
    $key = array_keys($_FILES)[0];
    return $_FILES[$key]['tmp_name'];
}

/**
 * @deprecated
 * @return mixed
 */
function getObjectName($object_key)
{
    $keys = explode('/', $object_key);
    return $keys[1] !== "" ? basename($object_key) : "";
}