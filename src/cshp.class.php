<?php 

require_once("classes/cshp_OutputObject.class.php");
require_once("classes/cshp_Ruleset.class.php");

class Cshp {

  //Options
  private $compress, $cache;
  private $cshpFolder;

  //Data
  private $snippets;

  //The final CSS
  private $outputObject;

  function __construct($options = [], $cshpFolder = "src"){
    
    $this->outputObject = new Cshp_OutputObject();
    $this->cshpFolder = is_array($options) ? $cshpFolder : $options; //If an array was not given in options, so it is the folder name

    try { 
      $this->compress = in_array("compress", $options) || in_array("compressed", $options); //True = compress, False = normal  
      $this->snippets = in_array("snippets", $options) ? json_decode(file_get_contents($this->cshpFolder."/data/cshp_snippets.json"), true) : array();
    } catch (Exception $error){
      $this->throwError($error->getMessage());
    }
  }

  public function text($text){ //Other contents
    $this->outputObject->addString($text);
  }

  private function throwError($errorMessage){ //Clear all the css and shows an error in a comment
    $this->outputObject->erase();
    $this->comment("error", $errorMessage);
  }

  public function comment($type = "info", $message = "Ops! We forget the message"){ //CSS comment
    $commentString = "";
    
    if($type == "info"){
      $commentString = "/* " . $message . " */ ";
    } else if ($type == "error"){
      $commentString  = "########## ERROR ##########\n";
      $commentString .= "/* " . $message . " \n";
      $commentString .= "--- Compilation stopped */ ";
    }

    $this->outputObject->addString($commentString);
  }

  public function import($file, $options = []){ //Add a import at the begining of the CSS file
    try {
      
      $importString = "@import '" . $file . "' ";
      $optionsSize = sizeof($options);

      if($optionsSize > 0){
        foreach($options as $opI => $option){
          $importString .= $option;
          if($opI < $optionsSize-1){
            $importString .= ", ";
          }
        }
      }

      $this->outputObject->addStringAtBeginning($importString."; ");

    } catch (Exception $error){
      $this->throwError($error->getMessage());
    }
  }

  public function rule($selector, $declarations = [], $functions = []){ //CSS declaration
    try {
      
      $pseudoRules = [];
      $nestedElements = [];

      foreach ($declarations as $propertie => $value) {
        //if the value is an array, then it is an nested propertie or a pseudo-class/element
        if(gettype($value) == "array"){
          $mod = substr($propertie, 0, 1);
          $propName = substr($propertie, 1);

          if($mod == "&"){ //Pseudo-classes
            array_push($pseudoRules, [
              "selector" => $selector.":".$propName, 
              "declarations" => $value
            ]);
          } else if($mod == "%"){ //Nested properties
            foreach ($value as $valueKey => $valueVal) {
              $declarations[$propName."-".$valueKey] = $valueVal;
            }
          } else { //Nested element
            array_push($nestedElements, [
              "selector" => $selector." ".$propertie, 
              "declarations" => $value
            ]);
          }

          unset($declarations[$propertie]);
        }
      }

      $ruleset = new Cshp_Ruleset();
      $ruleset->selector = $selector;
      $ruleset->declarations = $declarations;

      //Search for snippets in the current ruleset
      $rulesetSnippets = array_intersect($declarations, array_flip($this->snippets));
      
      foreach ($rulesetSnippets as $snippetKey => $snippetValue) {
        $newDeclaration = explode(":", $this->snippets[$snippetValue]);
        $ruleset->declarations[$snippetKey] = $newDeclaration[1];
        $ruleset->declarations[$newDeclaration[0]] = $ruleset->declarations[$snippetKey];
        unset($ruleset->declarations[$snippetKey]);
      }

      //Add the main ruleset
      $this->outputObject->addRuleset($ruleset);

      //Add the pseudo rulesets after the main
      foreach ($pseudoRules as $pseudoRule) {
        $this->rule($pseudoRule["selector"], $pseudoRule["declarations"]);
      }

      //Add the nested elements after the main
      foreach ($nestedElements as $nested) {
        $this->rule($nested["selector"], $nested["declarations"]);
      }

    } catch (Exception $error) {
      $this->throwError($error->getMessage());
    }
  
  }

  public function compile($outputFolder = "", $outputFile = ""){ //if folder an file are given, then will save relative to where compile was called

    $outputCSS = $this->outputObject->compile($this->compress);

    if($outputFolder == ""){
      header("Content-type: text/css");
      echo $outputCSS;
    } else {
      //If name was not given generates a random filename
      try {
        $fileName = $outputFile != "" ? $outputFile : number_format(uniqid(rand(), true), 0, "", "") . '.css';

        $file = fopen($outputFolder . "/" . $fileName, "w");
        fwrite($file, $outputCSS);
        fclose($file);

        if($outputFile == ""){
          return $fileName;
        } else {
          return true;
        }

      } catch (Exception $e){
        return $e;
      }
    }
  }

}

?>