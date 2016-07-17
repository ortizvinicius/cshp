<?php 
require_once("cshp/cshp.class.php");
$cshp = new Cshp(["snippets"]);

$cshp->import("normalize.css");
$cshp->comment("info", "CSHP Introduction styles");

$mainColor = "teal";
$secondColor = "lightseagreen";

//Mixins
$cshp->mixin("bdRadius", ["radius"], [
  "border-radius" => "{{radius}}",
  "-moz-border-radius" => "{{radius}}",
  "-webkit-border-radius" => "{{radius}}",
]);
$cshp->mixin("fLeft", ["fl:l"]);
$cshp->mixin("fRight", ["fl:r"]);

//Global
$cshp->rule("body", [
  "%font" => [
    "family" => "Helvetica, Arial, sans-serif",
    "size" => "18px"
  ],
  "margin" => "3%"
]);
$cshp->rule("a", [
  "td:n",
  "color" => $mainColor,
  "&hover" => [
    "border-bottom" => "1px dotted " . $secondColor,
    "color" => $secondColor
  ]
]);

//Header
$cshp->rule(".banner", [
  "background" => "#EEE",
  "padding" => "30px",
  "margin" => "30px 0",
  "@bdRadius" => ["30px"]
]);

$cshp->rule(".logo", [
  "margin-top" => "0",
  "color" => $mainColor,
  "@fLeft",
  "span" => [
    "d:b",
    "font-size" => ".75em",
    "color" => $secondColor
  ]
]);

$cshp->rule(".bannerNav", [
  "@fRight",
  "ul" => [
    "lis:n",
    "margin" => "7px 0 0 0",
    "li" => [
      "d:ib",
      "margin-left" => "10px"
    ]
  ]
]);

$cshp->rule(".tooltip", [
  "d:tb",
  "background" => $secondColor,
  "margin" => "-20px 0 0 150px",
  "padding" => "10px",
  "color" => "#FFF",
  "@bdRadius" => ["7px"],
  "border-top-left-radius" => "0 !important", //Sorry for the !important :( I will fix it
  "-moz-border-radius-topleft" => "0 !important",
  "-webkit-border-top-left-radius" => "0 !important",
  "&before" => [
    "pos:a",
    "content" => "' '",
    "border" => "10px solid transparent",
    "border-right-color" => $secondColor,
    "margin" => "-10px 0 0 -30px"
  ],
  "a" => [
    "color" => "rgba(255, 255, 255, .8)",
    "&hover" => [
      "color" => "rgba(255, 255, 255, .9)",
      "border-bottom" => "1px dotted rgba(255, 255, 255, .9)" 
    ]
  ]
]);

$cshp->compile();
?>