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
    public static $id = 0;

    public $data = [
        'host' => '',
        'port' => '',
        'username' => '',
        'password' => '',
        'databasetype' => '',
        'databasename' => ''

    ];

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __construct(Array $array)
    {

        foreach($array as $key => $value)
        {
            $this->$key = $value;
        }
        self::$id++;
    }

}

class DatabaseConnection
{
    private static $dependentConfig;
    private static $injection_satisied = false;
    public static function init(DatabaseConfig $config)
    {

        self::$dependentConfig = $config;
        self::$injection_satisied = true;

    }

    public static function getConfig(){
        if(self::$injection_satisied == true){
            return self::$dependentConfig;
        }
    }
}

class ConfigurationSaver
{
    public static function MakeDatabaseConfig(DatabaseConfig $config)
    {
        $fileName="db_conf";

        $config_json = json_encode(get_object_vars($config));
        var_dump($config_json);

        FileIO::createDir("config");
        $path = "config/".$fileName.".".$config::$id.".conf";
        FileIO::write($path, $config_json);
        return $path;

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

var_dump($db_conf_object);
die();
DatabaseConnection::init($db_conf_object);
$conf = DatabaseConnection::getConfig();

$file = ConfigurationSaver::MakeDatabaseConfig($conf);
$dump = FileIO::read($file);
echo $dump;

var_dump(function(){
    return "How could I forget? ";
});

