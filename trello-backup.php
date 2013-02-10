<?php
/**
 * Backups all your Trello boards (including cards, checklists, comments, etc.) as one JSON file per board.
 * 
 * See: https://github.com/mattab/trello-backup
 * 
 * License: GPL v3 or later (I'm using that Wordpress function below and WP is released under GPL)
 */

require_once 'config.php';

// If the application_token looks incorrect we display help
if(strlen($application_token) < 30) {
	// 0) Fetch the Application tokenn
	// Source: https://trello.com/docs/gettingstarted/index.html#getting-a-token-from-a-user
	// We get the app token with "read" only access forever
	$url_token = "https://trello.com/1/authorize?key=".$key."&name=My+Trello+Backup&expiration=never&response_type=token";
	die("Go to this URL with your web browser (eg. Firefox) to authorize your Trello Backups to run:\n$url_token\n");
}

// 1) Fetch all Trello Boards and Organizations
$application_token = trim($application_token);
$url_boards = "https://api.trello.com/1/members/$username/boards?&key=$key&token=$application_token";
$response = file_get_contents($url_boards);
$boardsInfo = json_decode($response);
if(empty($boardsInfo)) {
	die("Error requesting your boards - maybe check your tokens are correct.\n");
}
$url_organizations = "https://api.trello.com/1/members/$username/organizations?&key=$key&token=$application_token";
$response = file_get_contents($url_organizations);
$organizationsInfo = json_decode($response);
if(empty($organizationsInfo)) {
    die("Error requesting your organizations - maybe check your tokens are correct.\n");
}

// 2) Only backup the "open" boards
$boards = array();
foreach($boardsInfo as $board) {
	if(!$backup_closed_boards && $board->closed) {
		continue;
	}

    $orgName = '';
    foreach($organizationsInfo as $org){
        if($org->id == $board->idOrganization) {
            $orgName = $org->displayName;
            break;
        }
    }
    if(empty($orgName)) $orgName = 'My Boards';

	$boards[$board->id] = (object) array(
        "name" => $board->name,
        "orgName" => $orgName
    );
}

echo count($boards) . " boards to backup... \n";

// 3) Backup now!
foreach($boards as $id => $board) {
	$url_individual_board_json = "https://api.trello.com/1/boards/$id?actions=all&actions_limit=1000&cards=all&lists=all&members=all&member_fields=all&checklists=all&fields=all&key=$key&token=$application_token";
	$filename = './trello-org-'.sanitize_file_name($board->orgName).'-board-'.sanitize_file_name($board->name).'.json';
	echo "recording board '$board->name' with organization '$board->orgName' in filename $filename...\n";
	$response = file_get_contents($url_individual_board_json);
	$decoded = json_decode($response);
	if(empty($decoded)) {
		die("The board '$board->name' or organization '$board->orgName' could not be downloaded, response was : $response ");
	}
	file_put_contents( $filename, $response );
}
echo "your Trello boards are now safely downloaded!\n";

/**
 * Found in Wordpress:
 * 
 * Sanitizes a filename replacing whitespace with dashes
 *
 * Removes special characters that are illegal in filenames on certain
 * operating systems and special characters requiring special escaping
 * to manipulate at the command line. Replaces spaces and consecutive
 * dashes with a single dash. Trim period, dash and underscore from beginning
 * and end of filename.
 *
 * @param string $filename The filename to be sanitized
 * @return string The sanitized filename
 */
function sanitize_file_name( $filename ) {
    $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}");
    $filename = str_replace($special_chars, '', $filename);
    $filename = preg_replace('/[\s-]+/', '-', $filename);
    $filename = trim($filename, '.-_');
    return $filename;
}
