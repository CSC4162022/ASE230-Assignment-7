<?php

class CSVHelper
{
    private static $obfuscator='<?php die() ?>';

    //
    static function write($file,$data,$assoc=false,$overwrite=false){
        if(!isset($data)) return false;
        //populate rows, don't overwrite
        if(!$overwrite && file_exists($file)) {
            $input = fopen($file, 'r');
            $output = fopen('../temporary.csv', 'w');
            while( false !== ( $line= fgetcsv($input) ) ){  //read line as array
                if (in_array(self::$obfuscator, $line)) continue;
                //write modified data to new file
                fputcsv( $output, $line);
            }
            fclose( $input );
            fclose( $output );
            //clean up
            unlink($file);// Delete
            rename('../temporary.csv', $file); //Rename
            self::produceCSV($file, $data, $assoc, $overwrite, array_keys($data));
        }
        //overwrite or $write
        else {
            if(!$assoc){
                self::produceCSV($file, $data, $assoc, $overwrite, array_keys($data));
            }
            else {
                self::produceCSV($file, $data, $assoc, $overwrite, array_keys($data));
            }
        }
        return true;
    }
    //read and return the items between the offsets
    static function read($file,$offset=null,$limit=null,$skipblanks=false){
        if(!file_exists($file)) return [];
        if(!isset(PATHINFO($file)['extension'])) return [];
        $rows=[]; $i = 0;
        $input = fopen($file, 'r');  //open for reading
        while( false !== ( $line= fgetcsv($input) ) ){
            //print_r($line);
            array_push($rows, $line );
            $i++;
        }
        if(!isset($offset)) return $rows;
        $count=1;
        $started=false;
        $out=[];
        $is_assoc=self::is_assoc($rows);
        //read associative array as numeric offset
        foreach($rows as $k=>$v){
            if($count==$offset) $started=true;
            if($started){
                $out[$k]=$v;
                $count++;
                if($count==$limit || $count > $limit) break;
            }
            else { $count++; }
        }
        return $out;
    }
    //modify at index with overwrite as default behavior
    static function modify($file,$index,$data,$overwrite=true){
        if(!file_exists($file) || !isset($data) || !isset($index)) return false;

        $rows=[]; $i = 0;
        $input = fopen($file, 'r');  //open for reading
        while( false !== ( $line= fgetcsv($input) ) ){
            //print_r($line);
            array_push($rows, $line );
            $i++;
        }
        if(!isset($rows[$index])) return false;
        $rows[$index]=$overwrite ? $data : array_merge($rows[$index],$data);

        $assoc = self::is_assoc($rows);
        self::produceCSV($file, $data, $assoc, $overwrite, array_keys($data));
        //self::produceCSV($file, $rows, $assoc, $overwrite, array_keys($data));
        return true;
    }

    //populate array from csv and overwrite. If wipe, remove element. Otherwise set null.
    static function delete($file,$index=null,$assoc=false,$wipe=false){
        if(!file_exists($file)) return false;
        if(!isset($index)) return unlink($file);
        $rows=[]; $i = 0;
        $input = fopen($file, 'r');  //open for reading
        while( false !== ( $line= fgetcsv($input) ) ){
            //print_r($line);
            array_push($rows, $line );
            $i++;
        }
        if(is_array($index)){
            foreach($index as $i){
                if($wipe) unset($rows[$i]);
                else $rows[$i]=[null];
            }
        }else{
            if(count($rows)<$index || $index < 0) return false;
            if($wipe) unset($rows[$index]);
            else $rows[$index]=[null];
        }
        //if(!$assoc) $rows=array_values($rows);
        print_r($rows);
        $assoc = self::is_assoc($rows);
        self::produceCSV($file, $rows, $assoc, true, array_keys($rows));
        return true;
    }

    static function find($file,$filter,$limit=null){
        if(!file_exists($file)) return [];
        $records=self::read($file);
        $count=0;
        $out=[];
        foreach($records as $record){
            if(is_array($filter)){
                $found=true;
                foreach($filter as $k=>$v) if(!isset($record[$k]) || $record[$k]!=$v) $found=false;
                if($found) $out[$count]=$record;
            }else foreach($record as $k=>$v) if($v==$filter) $out[$count]=$record;
            $count++;
        }
        return $out;
    }

    private static function is_assoc($array){
        $keys=array_keys($array);
        return $keys!==array_keys($keys);
    }

    //write user data in w or a mode depending on $overwrite flag
    private static function produceCSV($file_name, $arr, $assoc, $overwrite, $keys) {

        $has_header = false;
        $i = 0;
        if ($assoc) {
            if ($overwrite==true) {
                $fp = fopen($file_name, 'w');
                foreach ($arr as $c) {
                    array_unshift($c, $keys[$i]);
                    fputcsv($fp, $c);
                    $i++;
                }
                fclose($fp);
            }
            else {
                foreach ($arr as $c) {
                    $fp = fopen($file_name, 'a');
                    if (!$has_header) {
                        //fputcsv($fp, array_keys($c));
                        $has_header = true;
                    }
                    //$c = array_merge(array_keys($c), array_values($c));
                    array_unshift($c, $keys[$i]);
                    fputcsv($fp, $c);
                    fclose($fp);
                    $i++;
                }
            }
        }
        else {
            $str='';
            if ($overwrite) {
                $fp = fopen($file_name, 'w');
                foreach ($arr as $k=>$v) {
                    $str .= $k;
                    if (is_string($v)) {
                        $str .= ',' . $v;
                    }
                    if (is_array($v)) {
                        //$str .= implode(',', $v);
                        $arr_keys=array_keys($v);
                        fputcsv($fp, array_merge(explode(',', $str), $v));
                    }
                    else fputcsv($fp, explode(',', $str));
                    $str = '';
                }
                fclose($fp);
            }
            else {
                foreach ($arr as $k=>$v) {
                    $fp = fopen($file_name, 'a');
                    $str .= $k;
                    if (is_string($v)) {
                        $str .= ',' . $v;
                    }
                    if (is_array($v)) {
                        //$str .= implode(',', $v);
                        fputcsv($fp, array_merge(explode(',', $str), $v));
                    }
                    else fputcsv($fp, explode(',', $str));
                    fclose($fp);
                    $str = '';
                }
            }
        }
    }

    private static function reset($file){
        if(file_exists($file)) rename($file,str_replace('.csv','_backup_'.date('Y-m-d_h_i_s').'.csv',$file));
    }
}