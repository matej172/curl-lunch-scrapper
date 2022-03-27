<?php
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "http://eatandmeet.sk/tyzdenne-menu");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch);

$dom = new DOMDocument();

@$dom->loadHTML($output);
$dom->preserveWhiteSpace = false;

$parseNodes = ["day-1", "day-2", "day-3", "day-4", "day-5", "day-6", "day-7"];

$jedla = [
    ["date"  => date( 'd.m.Y', strtotime( 'monday this week' ) ), "day" => "Pondelok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'tuesday this week' ) ), "day" => "Utorok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'wednesday this week' ) ), "day" => "Streda", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'thursday this week' ) ), "day" => "Štvrtok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'friday this week' ) ), "day" => "Piatok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'saturday this week' ) ), "day" => "Sobota", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'sunday this week' ) ), "day" => "Nedeľa", "menu" => []],
];

foreach ($parseNodes as $index => $nodeId) {

    $node = $dom->getElementById($nodeId);

    foreach ($node->childNodes as $menuItem)
    {
        if($menuItem && $menuItem->childNodes->item(1) && $menuItem->childNodes->item(1)->childNodes->item(3)){
            $nazov = trim($menuItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(1)->childNodes->item(1)->nodeValue);
            $cena = trim($menuItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(1)->childNodes->item(3)->nodeValue);
            $popis = trim($menuItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(3)->nodeValue);
            array_push($jedla[$index]["menu"], "$nazov ($popis): $cena");
        }
    }
}

echo "<pre>";
var_dump($jedla);
echo "</pre>";