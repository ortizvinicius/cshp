<?php 

require_once("model/cshp_Ruleset.php");

class Cshp_OutputObject {

  //This class will contain the "css" code itself, "CRUD" and other methods

  private $outputArray = [];
  private $indexArray = [];

  public function erase(){
    $this->outputArray = [];
    $this->indexArray = [];
  }

  public function addString($string = ""){
    array_push($this->outputArray, $string);
    array_push($this->indexArray, "?");
  }

  public function addStringAtBeginning($string = ""){
    array_unshift($this->outputArray, $string);
    array_unshift($this->indexArray, "?");
  }

  public function addRuleset(Cshp_Ruleset $ruleset){

    //Test if the selector was already set
    $existentIndex = array_search($ruleset->selector, $this->indexArray);

    if($existentIndex === false){
      array_push($this->outputArray, $ruleset);
      array_push($this->indexArray, $ruleset->selector);
    } else {
      //Add the new declarations
      $this->outputArray[$existentIndex]->declarations = array_merge($this->outputArray[$existentIndex]->declarations, $ruleset->declarations);
    }

  }

  public function compile($compress = false){
    $outputCSS = "";

    $lineBreak = !$compress ? "\n" : "";
    $space = !$compress ? " " : "";
    $ident = !$compress ? "  " : "";

    foreach ($this->outputArray as $ruleset) {
      if(gettype($ruleset) == "object"){
        $outputCSS .= $ruleset->selector . $space . "{" . $lineBreak;
        foreach ($ruleset->declarations as $propertie => $value) {
          $outputCSS .= $ident.$propertie . ':' . $space . $value . ";" . $lineBreak;
        }
        $outputCSS .= "}".$lineBreak;
      } else {
        $outputCSS .= $ruleset.$lineBreak;
      }
    }

    return $outputCSS;
  }

}

?>