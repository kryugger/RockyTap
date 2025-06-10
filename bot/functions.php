<?php
function LampStack($method,$datas=[]){
global $apiKey;
$url = 'https://api.telegram.org/bot'.$apiKey.'/'.$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
return json_decode(curl_error($ch));
}else{
return json_decode($res);
}
}

function remove_json_comma($json_data){
    $json = '{';
    foreach ($json_data as $key => $value) {
        $json .= '"' . $key . '": ';
        if (is_array($value)) {
            $json .= json_encode($value) . ',';
        } else {
            $json .= $value . ',';
        }
    }
    $json = rtrim($json, ','); // Remove the last comma
    $json .= '}';
    return $json;
}

function dbBackup($host, $user, $pass, $dbname, $path) {
$link = mysqli_connect($host,$user,$pass, $dbname);
if (mysqli_connect_errno()){
echo "Failed to connect to MySQL: " . mysqli_connect_error();
exit;
}
mysqli_query($link, "SET NAMES 'utf8'");
$tables = array();
$result = mysqli_query($link, 'SHOW TABLES');
while($row = mysqli_fetch_row($result)) {
$tables[] = $row[0];
}
$return = '';
foreach($tables as $table) {
$result = mysqli_query($link, 'SELECT * FROM '.$table);
$num_fields = mysqli_num_fields($result);
$num_rows = mysqli_num_rows($result);
$return.= 'DROP TABLE IF EXISTS '.$table.';';
$row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
$return.= "\n\n".$row2[1].";\n\n";
$counter = 1;
for ($i = 0; $i < $num_fields; $i++) {
while($row = mysqli_fetch_row($result)) {   
if($counter == 1){
$return.= 'INSERT INTO '.$table.' VALUES(';
}else{
$return.= '(';
}
for($j=0; $j<$num_fields; $j++){
$row[$j] = addslashes($row[$j]);
$row[$j] = str_replace("\n","\\n",$row[$j]);
if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; 
}else{
$return.= '""';
}
if ($j<($num_fields-1)) { 
$return.= ',';
}
}
if($num_rows == $counter){
$return.= ");\n";
}else{
$return.= "),\n";
}
++$counter;
}
}
$return.="\n\n\n";
}
$fileName = $path . '.sql';
$handle = fopen($fileName,'w+');
fwrite($handle,$return);
if(fclose($handle)){
return true;
exit; 
}
}