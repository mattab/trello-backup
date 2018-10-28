<?php
// See also README.md

// Key from https://trello.com/1/appKey/generate
$key = 'here_put_your_Key';

// Your Application Token (set $key only, then run the trello-backup.php to obtain your application_token to set below)
$application_token = 'Here_your_app_token';

// By default we don't backup closed boards (less clutter)
$backup_closed_boards = false;

// Backup all Trello Boards from the organizations that the user has read access to
$backup_all_organization_boards = false;

// Backup all cards' attachments in a subfolder for each Trello board
$backup_attachments = false;

// Backup Google Drive attachments as link (.url file)
$backup_gdrive_attachments = false;

// Where to store the backup files (by default, trello boards JSON files will be stored in this directory)
$path = dirname(__FILE__);

// HTTP proxy, if one is required, in the format 'host:port', e.g. 'proxy.example.com:80' or '192.168.1.254:8080'
$proxy= '';

// Array of boards to not backup regardless of other settings
$ignore_boards = array('Welcome Board');

// Array of boards to download only regardless of other settings
// NO other boards will be downloaded
$boards_to_download = array();

// Timestamp format, e.g. 'Y-m-d_H-i-s'
$filename_append_datetime = false;
