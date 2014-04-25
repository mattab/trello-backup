<?php
// See also README.md

// Where to store backups
$path = '.';

// Key from https://trello.com/1/appKey/generate
$key = 'here_put_your_Key';

// Your Trello username
$username = 'here_your_username_WITHOUT_the_@_symbol';

// Your Application Token (set $key and $username only, then run the trello-backup.php to obtain your application_token to set below
$application_token = 'Here_your_app_token';

// By default we don't backup closed boards (less clutter)
$backup_closed_boards = false;

// Backup all Trello Boards from the organizations that the user has read access to
$backup_all_organization_boards = false;

// HTTP proxy, if one is required, in the format 'host:port', e.g. 'proxy.example.com:80' or '192.168.1.254:8080'
$proxy= '';

