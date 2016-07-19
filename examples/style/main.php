<?php 
require_once("cshp/cshp.php");
$cshp = new Cshp(["snippets", "compress"]);

$cshp->import("normalize.css");
$cshp->comment("info", "CSHP Introduction styles");

$mainColor = "teal";
$secondColor = "lightseagreen";
$bgColor = "#EEE";

//Mixins
$cshp->mixin("bdRadius", ["radius"], [
  "border-radius" => "{{radius}}",
  "-moz-border-radius" => "{{radius}}",
  "-webkit-border-radius" => "{{radius}}",
]);
$cshp->mixin("boxSizingBorder", [
  "box-sizing" => "border-box",
  "-moz-box-sizing" => "border-box",
  "-webkit-box-sizing" => "border-box",
]);
$cshp->mixin("fLeft", ["fl:l"]);
$cshp->mixin("fRight", ["fl:r"]);
$cshp->mixin("code", [
  "d:tb",
  "padding" => "15px",
  "color" => "#FFF",
  "width" => "100%"
]);

//Global
$cshp->rule("body", [
  "color" => "#454545",
  "margin" => "3%",
  "%font" => [
    "family" => "Helvetica, Arial, sans-serif",
    "size" => "18px"
  ]
]);

$cshp->rule("a", [
  "td:n",
  "color" => $mainColor,
  "&hover" => [
    "border-bottom" => "1px dotted " . $secondColor,
    "color" => $secondColor
  ]
]);

$cshp->rule("pre", ["whs:pw"]);

$cshp->rule(".clear", ["cl:b"]);

$cshp->rule("hr", [
  "border" => "none",
  "border-bottom" => "2px solid " . $mainColor
]);

//Header
$cshp->rule(".banner", [
  "background" => $bgColor,
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

//Main
$cshp->rule(".article", [
  "margin" => "3%",
  " > h2" => [
    "ta:c",
    "padding-bottom" => "10px",
    "margin-bottom" => "10px",
    "border-bottom" => "1px solid " . $secondColor
  ]
]);

$cshp->rule(".box", [
  "background" => "#EEE",
  "padding" => "0 20px 20px 20px",
  "width" => "49%",
  "margin-bottom" => "15px",
  "@bdRadius" => ["10px"],
  "@boxSizingBorder",
  "&nth-child(even)" => [
    "cl:b",
    "@fLeft"
  ],
  "&nth-child(odd)" => ["@fRight"]
]);

$cshp->rule(".phpCode", [
  "background" => $mainColor,
  "box-sizing" => "border-box",
  "-moz-box-sizing" => "border-box",
  "-webkit-box-sizing" => "border-box",
  "@code",
  //"@boxSizingBorder", //Bug here
  "@bdRadius" => ["10px"]
]);
$cshp->rule(".cssCode", [
  "background" => $secondColor,
  "box-sizing" => "border-box",
  "-moz-box-sizing" => "border-box",
  "-webkit-box-sizing" => "border-box",
  "@code",
  //"@boxSizingBorder", //Bug here
  "@bdRadius" => ["10px"]
]);

//Footer
$cshp->rule(".mainFooter", [
  "border-top" => "2px solid " . $mainColor,
  "padding-top" => "15px",
  ".versionInfo" => [
    "font-size" => ".8em"
  ]
]);

$cshp->compile();
?>