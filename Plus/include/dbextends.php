<?
//�X�R�ƾڮw��ơA�ت��G�PVBB�ݮe�C by ���ɹD�� �ѯ�
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