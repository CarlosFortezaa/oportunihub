<?php
function add_tags($text){

    // Convierte windows y macOS line breaks (enters) en \n
    $text = str_replace("\r\n", "\n", $text);
    $text = str_replace("\r", "\n", $text);

    // Get un arreglo de parrafos
    $paragraphs = explode("\n\n", $text);

    $texts_with_tags = '';
    foreach($paragraphs as $p){
        $p = ltrim($p);

        $first_char = substr($p, 0, 1);
        if($first_char == '*'){
            // Se anaden los tags <ul> y <li>
            $p = "<ul>$p</li></ul>";
            $p = str_replace("*", '<li>', $p);
            $p = str_replace("\n", '</li>', $p);
        } else {
            // Se anaden los tags <p>
            $p = "<p>$p</p>";
        }
        $texts_with_tags .= $p;
    }
    return $texts_with_tags;
}