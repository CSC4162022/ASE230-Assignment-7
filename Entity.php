<?php
require_once './CSVHelper.php';
require_once './JSONHelper.php';

//A class to represent and manipulate Artists
class Entity
{

    //retrieve a single entity
    public static function get_entity($file, $type, $index) {
        if(!file_exists($file)) return null;
        if ($index < 0 ) return '';
        if(!isset(PATHINFO($file)['extension'])) return null;
        if(strtolower(PATHINFO($file)['extension'])=='php' && $type == 'json') {
            return JSONHelper::read($file ,$index,$index + 1);
        }
        else {
            return CSVHelper::read($file, $index, $index + 1);
        }
    }

    //retrieve all entities
    public static function get_entities($file, $type) {
        if(!file_exists($file)) return null;

        if(!isset(PATHINFO($file)['extension'])) return null;
        if(strtolower(PATHINFO($file)['extension'])=='php' && $type == 'json') {
            return JSONHelper::read($file);
        }
        else {
            return CSVHelper::read($file);
        }
    }
    //create a new entity
    public static function new_entity($file, $type, $entity, $assoc=false, $overwrite=false) {
        if(!file_exists($file)) return null;

        if(!isset(PATHINFO($file)['extension'])) return null;
        if(strtolower(PATHINFO($file)['extension'])=='php' && $type == 'json') {
            JSONHelper::write($file, $entity, $assoc, $overwrite);
        }
        else {
            CSVHelper::write($file, $entity, $assoc, $overwrite);
        }
    }
    //modify existing entity at index
    public static function modify_entity($file, $type, $index, $data, $overwrite=true) {
        if(!file_exists($file)) return null;

        if(!isset(PATHINFO($file)['extension'])) return null;
        if(strtolower(PATHINFO($file)['extension'])=='php' && $type == 'json') {
            JSONHelper::modify($file, $index, $data, $overwrite);
        }
        else {
            CSVHelper::modify($file, $index, $data, $overwrite);
        }
    }
    //delete entity
    public static function delete_entity($file, $type, $index, $assoc=false) {
        if(!file_exists($file)) return null;
        if(!isset($index)) return unlink($file);
        if(!isset(PATHINFO($file)['extension'])) return null;
        if(strtolower(PATHINFO($file)['extension'])=='php' && $type == 'json') {
            JSONHelper::delete($file, $index, $assoc);
        }
        else {
            CSVHelper::delete($file, $index, $assoc);
        }
    }

}