<?php

// Kusonime Scrapper
// MADE BY ME : RINNN
// MY CODE SO DIRTY LIKE SHIT :(
// My Facebook : fb.com/azum.co

header('Content-Type: application/json');
include_once("lib/simple_html_dom.php");

function getTitle($content){
   foreach($content as $title){
      return $title->innertext;
   }     
}

function getKusonime($kusolink){
        
$html = @file_get_html($kusolink) or die("Cannot Open Link");
   foreach($html->find('div.smokeddl') as $element){
      foreach($element->find("div.smokeurl") as $links){
      
         $res = $links->find("strong",0)->innertext;
         $ttl = getTitle($element->find("div.smokettl"));
         $kusolink = preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $links->innertext, $nemu);
        
        $url = $nemu[0];
        $item["url"] = $url;
        $item["res"] = $res;
        $item["ttl"] = $ttl;
        $kusonime[] = $item;
      }
        
   }
   
// After this,is not MY CODE,I just copy from STACKOVERFLOW

$json_array = json_encode($kusonime);
// Group Json Object => https://stackoverflow.com/questions/23123878/php-how-to-group-json-object

$objects = json_decode($json_array);
$grouped = array();

// Loop JSON objects
foreach($objects as $object) {
    if(!array_key_exists($object->ttl, $grouped)) { // a new ID...
         $newObject = new stdClass();

         // Copy the ID/ID_NAME, and create an ITEMS placeholder
         $newObject->title = $object->ttl;

         // Save this new object
         $grouped[$object->ttl] = $newObject;
    }

    $taskObject = new stdClass();

    // Copy the TASK/TASK_NAME
    $taskObject->url = $object->url;
    $taskObject->resolution = $object->res;

    // Append this new task to the ITEMS array
    $grouped[$object->ttl]->data[] = $taskObject;
}

// We use array_values() to remove the keys used to identify similar objects
// And then re-encode this data :)
$grouped = array_values($grouped);
$json = json_encode($grouped, JSON_PRETTY_PRINT);
//print_r(json_decode($json, true));
return $json;
 
 }

//echo getKusonime("https://kusonime.com/nandei-sensei-batch-sub-indo/");

if(!empty($_GET["url"])){
   if(preg_match('/kusonime.com/', $_GET['url'])){
     print_r(getKusonime($_GET['url']));
   }else{
   $item["url"] = "Error, Url is not Kusonime Link!";
   $msg[] = $item;
   die(json_encode($msg));
   }
}else{
     $item["url"] = "Url is not Set!";
     $msg[] = $item;
     die(json_encode($msg));
}