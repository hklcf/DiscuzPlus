<?
//擴充數據庫函數，目的：與VBB兼容。 by 阿怡道場 老草
class mydb extends dbstuff {

  function query_first($query_string) {
    $query_id = $this->query($query_string);//." LIMIT 1");
    $returnarray = $this->fetch_array($query_id);
    $this->free_result($query_id);
    return $returnarray;
  }
}
$db = new mydb;
?>