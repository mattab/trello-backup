<?php
/**
 * Backups all your Trello boards (including cards, checklists, comments, etc.) as one JSON file per board.
 *
 * See: https://github.com/mattab/trello-backup
 *
 * License: GPL v3 or later (I'm using that Wordpress function below and WP is released under GPL)
 */

if ($argc == 2) {
    $config_file = $argv[1];
} else {
    $config_file = 'config.php';
}

require_once $config_file;

// If the application_token looks incorrect we display help
if(strlen($application_token) < 30) {
    // 0) Fetch the Application tokenn
    // Source: https://trello.com/docs/gettingstarted/index.html#getting-a-token-from-a-user
    // We get the app token with "read" only access forever
    $url_token = "https://trello.com/1/authorize?key=".$key."&name=My+Trello+Backup&expiration=never&response_type=token";
    die("Go to this URL with your web browser (eg. Firefox) to authorize your Trello Backups to run:\n$url_token\n");
}

// Prepare proxy configuration if necessary
$ctx = NULL;
if (!empty($proxy)) {
    $aContext = array(
        'http' => array(
            'proxy' => 'tcp://'.$proxy,
            'request_fullurl' => true
        )
    );
    $ctx = stream_context_create($aContext);
}

// 1) Fetch all Trello Boards
$application_token = trim($application_token);
$url_boards = "https://api.trello.com/1/members/$username/boards?&key=$key&token=$application_token";
$response = file_get_contents($url_boards, false, $ctx);
$boardsInfo = json_decode($response);
if(empty($boardsInfo)) {
    die("Error requesting your boards - maybe check your tokens are correct.\n");
}

// 2) Fetch all Trello Organizations
$url_organizations = "https://api.trello.com/1/members/$username/organizations?&key=$key&token=$application_token";
$response = file_get_contents($url_organizations, false, $ctx);
$organizationsInfo = json_decode($response);
$organizations = array();
foreach($organizationsInfo as $org){
    $organizations[$org->id] = $org->displayName;
}

// 3) Fetch all Trello Boards from the organizations that the user has read access to
if($backup_all_organization_boards) {
    foreach($organizations as $organization_id => $organization_name) {
        $url_boards = "https://api.trello.com/1/organizations/$organization_id/boards?&key=$key&token=$application_token";
        $response = file_get_contents($url_boards, false, $ctx);
        $organizationBoardsInfo = json_decode($response);
        if(empty($organizationBoardsInfo)) {
            die("Error requesting the organization $organization_name boards - maybe check your tokens are correct.\n");
        } else {
            $boardsInfo = array_merge($organizationBoardsInfo, $boardsInfo);
        }
    }
}

// 4) Only backup the "open" boards
$boards = array();
foreach($boardsInfo as $board) {
    if(!$backup_closed_boards && $board->closed) {
        continue;
    }

    $boards[$board->id] = (object) array(
        "name" => $board->name,
        "orgName" => (isset($organizations[$board->idOrganization])? $organizations[$board->idOrganization] : ''),
        "closed" => (($board->closed) ? true : false)
    );
}

echo count($boards) . " boards to backup... \n";

// 5) Backup now!
foreach($boards as $id => $board) {
    $url_individual_board_json = "https://api.trello.com/1/boards/$id?actions=all&actions_limit=1000&cards=all&lists=all&members=all&member_fields=all&checklists=all&fields=all&key=$key&token=$application_token";
    $filename = "$path/trello"
		. (($board->closed) ? '-CLOSED' : '')
		. (!empty($board->orgName) ? '-org-' . sanitize_file_name($board->orgName) : '' )
		. '-board-' . sanitize_file_name($board->name)
		. '.json';
    echo "recording ".(($board->closed)?'the closed ':'')."board '".$board->name."' with organization '".$board->orgName."' in filename $filename...\n";
    $response = file_get_contents($url_individual_board_json, false, $ctx);
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
