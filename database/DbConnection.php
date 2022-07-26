<?php

class DbConnection {

public static function make($config){
    try{
        return new PDO(
            $config['host'].';dbname='.$config['db_name'],
            $config['username'],
            $config['password'],
            
        );

    }catch(PDOException $e){
        http_response_code(500);
        echo 'Database could not be connected: ' . $e->getMessage();
    }
}

};
