<?php
 // here we define utility functions for reading and writing JSON files i use it in many files so better to have it in one place
 // this will help to avoid code duplication and make the code cleaner and more maintainable
function readJson($filName){
    if(!file_exists($filName)){
        return [];
}
        $data = file_get_contents($filName);
        return $data ? json_decode($data, true) : [];
}
    function writeJson($filName, $data){
    file_put_contents($filName, json_encode($data, JSON_PRETTY_PRINT));
    }
?>