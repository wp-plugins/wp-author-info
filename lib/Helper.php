<?php

Class WpAuthorInfoHelper{
    static public function pr($obj, $val=null) {
        echo "<pre>";
        echo str_repeat("-",10)."Start".str_repeat("-",10)."<br>";
            print_r($obj);
        echo "<br>".str_repeat("-",10)."End".str_repeat("-",10);
        echo "</pre>";
        if($val)
            exit;
    }
}