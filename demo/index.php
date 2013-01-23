<?php

require '../autoloader.php';

$yaml = new Yampee_Yaml_Yaml();

// Parse
$array = $yaml->parse(file_get_contents('/path/to/file.yaml'));

// Dump
$yamlString = $yaml->dump($array);