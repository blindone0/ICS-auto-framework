<?php

class FileIO
{
    public static function createDir($directory)
    {
        return mkdir($directory);
    }

    public static function write($fileName ,$txt)
    {
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

    public function __get($key)
    {
        return $this->data[$key];
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
    private static $connection;
    public static function init(DatabaseConfig $config)
    {
        self::$dependentConfig = $config;
        self::$injection_satisied = true;
    }

    public static function getConfig()
    {
        if(self::$injection_satisied == true){
            return self::$dependentConfig;
        }
    }

    public static function conntect2db()
    {
        if(self::$injection_satisied){
            $user = self::$dependentConfig->get('username');
            $pass = self::$dependentConfig->get('password'); 
            $host = self::$dependentConfig->get('host');
            $db_type = self::$dependentConfig->get('databasetype');
            $dbname  = self::$dependentConfig->get('databasename');
            $pdo_str = $db_type.":host=".$host.";dbname=".$dbname.";charset=utf8";
            $pdo = new PDO($pdo_str, $user, $pass);
            self::$connection = $pdo;
            return $pdo;
        } else {
            return 1;
        }
    }
}

class ConfigurationSaver
{
    public static function MakeDatabaseConfig(DatabaseConfig $config)
    {
        $fileName="db_conf";
        $config_json = json_encode(get_object_vars($config));
        FileIO::createDir("config");
        $path = "config/".$fileName.".".$config::$id.".conf";
        FileIO::write($path, $config_json);
        return $path;

    }
}

$db_conf = array("databasetype" => "MySql", "databasename"=> "ICS","host"=>"billing.ics.global", "port"=>"3306", "username"=>"root", "password"=>"toor");
$db_conf_object = new DatabaseConfig($db_conf);
DatabaseConnection::init($db_conf_object);
$conf = DatabaseConnection::getConfig();
var_dump($conf);
$file = ConfigurationSaver::MakeDatabaseConfig($conf);
$dump = FileIO::read($file);
echo $dump;
