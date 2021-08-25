<?PHP
    error_reporting(E_ALL);
    include_once("../connection/connection.php");

    if(isset($_GET['state_id'])){
      $state_id = strip_tags($_GET['state_id']);
      $sql = "SELECT * FROM lga WHERE state_id = '{$state_id}'";
      $stmt = $dbh->query($sql);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $counter = 1;
      $str = "[";
      foreach($result as $lga){
        if($str != "[")
          $str .= ",";

        $str .='{"id":"'.$lga['id'].'", "name":"'.$lga['name'].'"}';
      }
      $str .= "]";

      echo $str;
      //return json_encode($str);
    }

    if(isset($_GET['lga_id'])){
      $lga_id = strip_tags($_GET['lga_id']);
      $sql = "SELECT * FROM ward  WHERE lga_id = '{$lga_id}'";
      $stmt = $dbh->query($sql);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $str = "[";
      foreach($result as $ward){
        if($str != "[")
          $str .= ",";

        $str .='{"id":"'.$ward['id'].'", "name":"'.$ward['name'].'"}';
      }
      $str .= "]";

      echo $str;
      //return json_encode($str);
    }


    if(isset($_GET['delete_user'])) {
      $user_id = strip_tags($_GET['delete_user']);
      $user_id = (int)$user_id;

      $sql = "DELETE FROM user WHERE id= :user_id";
      $stmt = $dbh->prepare($sql);
      $result = $stmt->execute(array(
        ':user_id' => $user_id
      ));

      if($result){
        header("Location: ../view_current_user.php");
      }
    }
?>
