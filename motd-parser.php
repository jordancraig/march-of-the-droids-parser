<?php

//set up variables
global $title_array;
global $link_array;
global $description_array;
global $date_array;
global $author_array;


scrape_article_info();


function scrape_article_info() {


    $rss_feed = simplexml_load_file('http://www.marchofthedroids.co.uk/feed/');
     
    foreach($rss_feed->channel->item as $article) {
      $title_array[] = $article->title;         //article title
      $link_array[] = $article->link;          //article link
      $description_array[] = $article->description;   //article desc
      $date_array[] = $article->pubDate;       //article date
           
      //get namespaced items
      $namespaces = $article->getNameSpaces(true);
      $dc = $article->children($namespaces['dc']);
      $author_array[] = $dc->creator;            //article author
  
    }

    create_json_document($title_array, $link_array, $description_array, $date_array, $author_array);

}



function create_json_document($title_array, $link_array, $description_array, $date_array, $author_array) {
    
    $i = 0;
    $l = count($title_array);
    $jsontext = '{ "stories": {';

    while($i < $l) {
        
        $jsontext .= '"article":' . "{" . '"title":'  . '"' .
                        $title_array[$i] . '"' . ',' .  '"author":' . '"' . $author_array[$i] . '"' . "},";

        $i++;

    }
    
    $jsontext = substr_replace($jsontext, '', -1); // to get rid of extra comma
    $jsontext .= '} }';
    echo $jsontext;

}

?>