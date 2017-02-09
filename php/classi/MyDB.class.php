<?php
/*
Di default allow remote include è settato a false su php ini, quindi solo file in questo server web possono utilizzare questa classe, conseguentemente non ci sono privilage escalation

USO: per scrivere doQueryRead per leggere, mentre usare doQueryRWrite per cancella, scrivere e modificare
    per fare l'escape delle stringhe, usare escapeString per la sistemazione dell'utf8 

*/
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class MyDB{
  private static $DATABASE_NAME='tecweb';
  private static $DATABASE_HOST='localhost';
  private static $DATABASE_USER='root';
  private static $DATABASE_PW='';

  public static function doQueryRead($query) {
      $conn = new mysqli(self::$DATABASE_HOST, self::$DATABASE_USER, self::$DATABASE_PW, self::$DATABASE_NAME);
      
      mysqli_set_charset($conn, "utf8");
      $result = $conn->query($query);
      $conn->close(); 
      return $result;
  }

  public static function doQueryWrite($query) {//ritorna false se fallisce, true se l'operazione riesce
      $conn = new mysqli(self::$DATABASE_HOST, self::$DATABASE_USER, self::$DATABASE_PW, self::$DATABASE_NAME);
      mysqli_set_charset($conn, "utf8");
      $result = $conn->query($query);
      $conn->close();
      return true;

  }
  public static function doQueryWriteID($query) {//ritorna false se fallisce, true se l'operazione riesce
      $conn = new mysqli(self::$DATABASE_HOST, self::$DATABASE_USER, self::$DATABASE_PW, self::$DATABASE_NAME);
      mysqli_set_charset($conn, "utf8");
      $result = $conn->query($query);
      $res = $conn->insert_id;
      $conn->close();
      return $res;
  }

}
?>
