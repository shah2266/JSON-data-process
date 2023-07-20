<?php
require_once 'db_config.php';
require_once 'DataProcess.php';

// Create an instance of the Database class
$db = new Database();

// Create an instance of the DataProcess class
$data = new DataProcess('Code Challenge (Events).json', $db);

// Insert json data into the database
$data->insert_json_data();

// Apply filter and get filtered data
$data->apply_filter();

// Display data
$data->render();

