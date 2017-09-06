<?php

$config = array();
$config['name'] = "My Cars";
$config['author'] = "car";
$config['ui'] = true; 
$config['ui_admin'] = true; 
$config['position'] = "98";
$config['version'] = "0.01";
$config['tables'] = array(
    'cars' => array(
        'id' => 'integer',
        'name' => array('type' => 'integer', 'default' => 0),
        'price' =>  array('type' => 'float', 'not_null'),
        'description' => 'text',
        'created_by' => 'integer',
        'created_at' => 'dateTime',
    ),
    "cars_lists_tasks" => array(
        'id' => "integer",
        'cars_id' => "integer",
        'task_name' => "text",
        'task_content' => "text",
        'is_completed' => array('type' => 'integer', 'default' => 0),
        'created_by' => "integer",
        'created_at' => "dateTime",
    )
);


?>