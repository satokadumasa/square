<?php
/*
//$db = new SQLite3('/usr/local/var/db/turndb');
$db = new NewDB();
// $sql = "SELECT * FROM turnusers_lt WHERE name='susanoo'";
$sql = "SELECT * FROM turnusers_lt ";
$result = $db->query($sql);
echo "turnusers_lt:".print_r($result, true)."\n";
echo "result: ".print_r($result->fetchArray(SQLITE3_ASSOC), true)."\n";
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "result: ".print_r($row, true)."\n";
}
exit();

class NewDB extends SQLite3
{
  function __construct() {
    $this->open('/usr/local/var/db/turndb');
  }
}
*/
$dbh = new PDO(
    "sqlite:/usr/local/var/db/turndb",
    null,
    null,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => false, 
      PDO::ATTR_FETCH_TABLE_NAMES => 1
    ]
);
$sql = "SELECT * FROM turnusers_lt ";
$data = $dbh->query($sql);

if( !empty($data) ) {
    foreach( $data as $datum ) {
        echo "turnusers_lt".print_r($datum, true)."\n";
    }
}
