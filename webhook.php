<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(0);
date_default_timezone_set("Asia/Taipei");

//GET JSON FROM GITLAB PUSH EVENT
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$valid_ip = array('118.163.33.121','118.163.33.126','172.68.47.50');
$client_ip = $_SERVER['REMOTE_ADDR'];
// $client_token = $_SERVER['HTTP_X_GITLAB_TOKEN'];//release on gitlab v8.5 above
$commit_author = $data['commits'][0]['author'];
$repo_name = $data['repository']['name'];
$repo_url = $data['repository']['git_ssh_url'];
$ref = explode('/', $data['ref']);
$fs = './log/'.$repo_name.'.log';


// 認證 ip
if ( ! in_array($client_ip, $valid_ip))
{
    echo "error 503";
    file_put_contents($fs,"Request on [".date("Y-m-d H:i:s")."] from Invalid ip [{$client_ip}]".PHP_EOL, FILE_APPEND | LOCK_EX);
    exit(0);
}




//HOOKFILE
$hookfile = 'hook.sh';

$argsment = $repo_name.' '.$repo_url.' '.$ref[2];
$w_bash =  "C:\\Program Files\\Git\\bin\\bash.exe";
$command = "\"$w_bash\" ".$hookfile.' '.$argsment;

$start_str = 'Request on ['.date("Y-m-d H:i:s").'] from ['.$client_ip.'] '.PHP_EOL;
$end_str = '===========================End of Request==========================';
$cmd_str = 'Command Line : '.$command;

//WRITE INTO LOG FILE
file_put_contents($fs,$start_str.PHP_EOL, FILE_APPEND | LOCK_EX);
// file_put_contents($fs, 'request : '.print_r($_SERVER, true).PHP_EOL, FILE_APPEND | LOCK_EX);
file_put_contents($fs, 'Commit Author: '.print_r($commit_author, true).PHP_EOL, FILE_APPEND | LOCK_EX);
file_put_contents($fs, 'Branch: '.$ref[2].PHP_EOL, FILE_APPEND | LOCK_EX);
exec($command,$out,$status);
file_put_contents($fs,'exec out : '.print_r($out,true).'exec status : '.$status.PHP_EOL, FILE_APPEND | LOCK_EX);
file_put_contents($fs,$end_str.PHP_EOL, FILE_APPEND | LOCK_EX);

?>