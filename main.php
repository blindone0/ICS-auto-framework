<?php

class FileIO
{
    public static function createDir($directory){
        return mkdir($directory);
    }

    public static function write($fileName ,$txt){
    try {
        $myfile = fopen($fileName, "w");
        fwrite($myfile, $txt);
    } catch (Exception $ex){
        echo $ex->getMessage();
    } finally {
        fclose($myfile);
    }
    }

    public static function read($file){
        try {
            $contents = file_get_contents($file);
            return $contents;
        } catch (Exception $ex){
            echo $ex->getMessage();
        }
    }
}

class DatabaseConfig
{
    public $host;
    public $port;
    public $username;
    public $password;
    public $databasetype;
    public $databasename;

    public static $id = 0;

    public function __construct($array)
    {
        $this->databasetype = $array['databasetype'];
        $this->databasename = $array['databasename'];
        $this->host = $array['host'];
        $this->port = $array['port'];
        $this->username = $array['username'];
        $this->password = $array['password'];
        self::$id++;
    }

    public function getSomething($something){
        return $this->$something;
     }

}

class DatabaseConnection
{
    private static $dependentConfig;
    private static $injection_satisied = false;
    public static function init($config)
    {
        if($config instanceof DatabaseConfig){
            self::$dependentConfig = $config;
            self::$injection_satisied = true;
        }
    }

    public static function getConfig(){
        if(self::$injection_satisied == true){
            return self::$dependentConfig;
        }
    }
}

class ConfigurationSaver
{
    public static function MakeDatabaseConfig($config)
    {
        $fileName="db_conf";
        if($config instanceof DatabaseConfig){
            $config_json = json_encode(get_object_vars($config));
            var_dump($config_json);

            FileIO::createDir("config");
            $path = "config/".$fileName.".".$config::$id.".conf";
            FileIO::write($path, $config_json);
            return $path;
        } else {
            return 0;
        }
    }
}

class Dialog
{
    private static $step;
    private static $databases= array("0" => "Mysql", "1" => "Postgress");
    
    public static function create(){
        echo "Welcome to ICS builder for your WEB project!";
        echo "Select your database: ";
        echo "0 - MySql";
        echo "1 - Postgress";

    }
}

$db_conf = array("databasetype" => "MySql", "databasename"=> "ICS","host"=>"billing.ics.global", "port"=>"3306", "username"=>"root", "password"=>"toor");
$db_conf_object = new DatabaseConfig($db_conf);
$ls = $db_conf_object->getSomething("host");
var_dump($ls);
die();
DatabaseConnection::init($db_conf_object);
$conf = DatabaseConnection::getConfig();

$file = ConfigurationSaver::MakeDatabaseConfig($conf);
$dump = FileIO::read($file);
echo $dump;

var_dump(function(){
    return "How could I forget? ";
});

