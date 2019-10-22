<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//defining constants for the script
define('DB_LOCAL_CONNECTION', 'mysql');
define('DB_LOCAL_HOST', '127.0.0.1:8889');
//define('DB_LOCAL_PORT', '8889');
define('DB_LOCAL_DATABASE', 'payu_task');
define('DB_LOCAL_USERNAME', 'root');
define('DB_LOCAL_PASSWORD', 'root');

define('BASE_PATH', __DIR__);
define('BASE_URL', 'http://payutask.loc/');

/* 
 * DB connection class
 */

class db_connect{
    protected $db;
    public function __construct(
            $host=null, 
            $dbName=null, 
            $userName=null, 
            $password=null
    ) {
        try {
            $this->db = new mysqli($host, $userName, $password, $dbName);
            return $this;
        }
        catch (Exception $e) {
            echo "some problem occured" . 
                    $e->getMessage() . $e->getFile() . $e->getLine();
        }
    }
    
    function query($query){
        return $this->db->query($query);
    }
    
    function fetch_assoc($result) {
        return mysqli_fetch_all($result);
    }
            
}

//get the arguments passed from crontab command
parse_str($argv[1], $params);
$id = $params['id'];
echo "\n****************script running for id ========$id******************\n";
//initiating the db connection
$dbConnection = new db_connect(DB_LOCAL_HOST, DB_LOCAL_DATABASE, DB_LOCAL_USERNAME, DB_LOCAL_PASSWORD);

//get details of the task to execute
$getTaskDetailsQuery = sprintf("SELECT * FROM cronjobs WHERE id='%s'", $id);
$taskDetailsOutput = $dbConnection->query($getTaskDetailsQuery);

$taskDetails = mysqli_fetch_assoc($taskDetailsOutput);
//print_r($taskDetails);echo "\n";
echo BASE_PATH . "/storage/downloads\n";
//preparing data for export
$generateFileValues = [
    'fileName' => $taskDetails['id'] . time() . "." . $taskDetails['file_type'],
    'queryToExecute' => $taskDetails['query'],
    'fileType' => $taskDetails['file_type'],
    'user_id' => $taskDetails['user_id'],
    'downloadLocation' => BASE_PATH . "/../public/storage/",    
    'url' => BASE_URL . "storage/",
];

//preparing query for job
if ($generateFileValues['fileType'] == 'csv') {
    $query = "SELECT * INTO OUTFILE '".$generateFileValues['downloadLocation'].
            $generateFileValues['fileName']."'
        FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
        LINES TERMINATED BY '\n'
        FROM (".$generateFileValues['queryToExecute'].") as output_table;";
} else {
    $query = "SELECT * INTO OUTFILE '".$generateFileValues['downloadLocation'].
            $generateFileValues['fileName']."'
        FIELDS TERMINATED BY '' OPTIONALLY ENCLOSED BY ''
        LINES TERMINATED BY '\n'
        FROM (".$generateFileValues['queryToExecute'].") as output_table;";
}
//echo $query;
// executing query
if ($dbConnection->query($query)) {
    $insertQuery = "INSERT INTO downloads (`date_added`, `merchant_id`, `download_path`)"
            . " VALUES ('".date('Y-m-d')."', '".$generateFileValues['user_id']."', '"
            . $generateFileValues['url'] . $generateFileValues['fileName'] . "');";

    if ($dbConnection->query($insertQuery)){
        echo "\n ****** File downloaded to storage******\n";
    } else {
        echo "\n ****** Failed ******\n";
    }
}


echo "****************script running ended for id ========$id******************\n";


