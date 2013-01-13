<?php

$target_url = "http://www.marchofthedroids.co.uk/";
$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
$date_array= array();
$title_array = array();
$article_link_array = array();
$snippet_array = array();
$author_array = array();


$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
curl_setopt($ch, CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$html= curl_exec($ch);
$html = @mb_convert_encoding($html, 'HTML-ENTITIES', 'utf-8');

if (!$html) {
    echo "<br />cURL error number:" .curl_errno($ch);
    echo "<br />cURL error:" . curl_error($ch);
    exit;
}

$dom = new DOMDocument();
@$dom->loadHTML($html);

$nodes = $dom->getElementsByTagName('*');

foreach($nodes as $node) {

    if ($node->nodeName == 'h1') {
        $title_array[] = ($node->nodeValue);        
    }

    if($node->nodeName == 'article') {
        $inodes = $node->childNodes;
        foreach($inodes as $inode) {
            if($inode->nodeName == 'div' && $inode->getAttribute('class') == 'entry-content') {
                $snippet_array[] = ($inode->nodeValue);
            }
        }        
    }
}

?>
