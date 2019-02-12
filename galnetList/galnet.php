<?php

require "vendor/autoload.php";
use PHPHtmlParser\Dom;

// Get a file into an array.  In this example we'll go through HTTP to get
// the HTML source of a URL.
$lines = file('articles-110219.txt');
$write = 'galnet-new.csv';
file_put_contents($write, "TITLE,DATE,TEXT,UID\r\n", FILE_APPEND | LOCK_EX);

// Loop through our array, show HTML source as HTML source; and line numbers too.
foreach ($lines as $line_num => $line) {
  $line = trim($line);

  $dom = new Dom;
  // $dom->load('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>');
  $dom->loadFromFile($line);
  $dom->loadFromUrl($line);
  $html = $dom->outerHtml;

  $contents = $dom->find('.article');

  $article = '';

  foreach ($contents as $content)
  {
    $body = $content->find('p')->innerHtml;
    $breaks = array("<br />","<br>","<br/>");
    $body = str_ireplace($breaks, "\n", $body);
    $body = str_ireplace("\"","'",$body);


    $date = trim($content->find('div p.small')->innerHtml);

    $title_container = $content->find('.galnetNewsArticleTitle a');
    $title = trim($title_container->text);
    $uid = $title_container->getAttribute('href');
    $uid = 'https://community.elitedangerous.com/en' . $uid;


    $article = '"' . $title;
    $article .= '",' . $date;
    $article .= ',"' . $body;
    $article .= '",' . $uid;
    $article .= "\r\n";
  }

  // using the FILE_APPEND flag to append the content to the end of the file
  // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
  file_put_contents($write, $article, FILE_APPEND | LOCK_EX);
}
