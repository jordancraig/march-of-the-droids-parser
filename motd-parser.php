<?php

//set up variables
global $title_array;
global $link_array;
global $description_array;
global $date_array;
global $author_array;



$rss_feed = simplexml_load_file('http://www.marchofthedroids.co.uk/feed/');
     
foreach($rss_feed->channel->item as $article) {

    $title_array[] = $article->title;        //article title
    $description = $article->description;
    $description = substr($description, 0, -148);
    $link_array[] = $article->link;          //article link
    $description_array[] = $description . "...";   //article desc
    $date_array[] = $article->pubDate;       //article date
           
    //get namespaced items
    $namespaces = $article->getNameSpaces(true);
    $dc = $article->children($namespaces['dc']);
    $author_array[] = $dc->creator;            //article author
  
}

$json = create_json_document($title_array, $link_array, $description_array, $date_array, $author_array);

function create_json_document($title_array, $link_array, $description_array, $date_array, $author_array) {
    
    $i = 0;
    $l = count($title_array);
    $jsontext = '{ "stories": {';

    while($i < $l) {
        
        $jsontext .= '"article ' . $i . '":' . " [ { " . '"title":'  . '"' .
                        $title_array[$i] . '"' . ',' .  '"desc":' . '"' . $description_array[$i] . '"}' . "],";

        $i++;

    }
    
    $jsontext = substr_replace($jsontext, '', -1); // to get rid of extra comma
    $jsontext .= '} }';
    return $jsontext;

}

print $json;

?>