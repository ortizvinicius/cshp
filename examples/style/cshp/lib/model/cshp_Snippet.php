<?php 

class Cshp_Snippet {

  public $snippets;
  private $cshpFolder;

  public function __construct($cshpFolder){
    $this->cshpFolder = $cshpFolder;
    $this->snippets = json_decode(
      file_get_contents($this->cshpFolder . "/lib/model/data/cshp_snippets.json"), true);
  }

}

?>