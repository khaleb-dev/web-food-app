<?php
    include_once(dirname(__FILE__)."/../connection/connection.php");

    function getStateById($state_id){
      global $dbh;
      $sql = "SELECT name FROM state WHERE id = '{$state_id}'";
      $stmt = $dbh->query($sql);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result;
    }

    function removeCandidate($candidate_id){
      global $dbh;
      $sql = "DELETE FROM party_candidate WHERE id = :candidate_id";
      $stmt = $dbh->prepare($sql);
      $result = $stmt->execute(array(
        ':candidate_id' => $candidate_id
      ));
      return $result;
    }
    function removeVoter($voter_id){
        global $dbh;
        $sql = "DELETE FROM voter WHERE id = :voter_id";
        $stmt = $dbh->prepare($sql);
        $result = $stmt->execute(array(
          ':voter_id' => $voter_id
        ));
        return $result;
    }

    function removeUser($user_id){
        global $dbh;
        $sql = "DELETE * FROM user WHERE id = :user_id";
        $stmt = $dbh->prepare($sql);
        $result = $stmt->execute(array(
          ':user_id' => $user_id
        ));
        return $result;
    }

    function getWardsByLGAId($lga_id){
      global $dbh;
      $sql = "SELECT * FROM ward WHERE lga_id = '{$lga_id}'";
      $stmt = $dbh->query($sql);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $result;
    }

    function getLGANameById($lga_id){
      global $dbh;
      $sql ="SELECT name FROM lga WHERE id= '{$lga_id}'";
      $stmt = $dbh->query($sql);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['name'];
    }

    /** Give me a state_id and i will give u all the local govts in that state **/
    function getLGAByStateId($state_id){
        global $dbh;
        $sql = "SELECT id, name FROM lga WHERE state_id = '{$state_id}'";
        $stmt = $dbh->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function getWardNameById($ward_id){
      global $dbh;
      $sql ="SELECT name FROM ward WHERE id= '{$ward_id}'";
      $stmt = $dbh->query($sql);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['name'];

    }
    function insertElection($election_type, $description){
      global $dbh;
      $sql = "INSERT INTO election(name, description) VALUES(:name, :description)";
      $sth = $dbh->prepare($sql);
      $sth->bindParam(':name', $election_type, PDO::PARAM_STR, 150);
      $sth->bindParam(':description', $description, PDO::PARAM_STR, 500);
      if($sth->execute()){
        echo "data has been inserted";
      }
    }

    function getElectionNameByType($election_type){
      global $dbh;
      $sql = "SELECT name FROM election WHERE name = :name";
      $stmt = $dbh->prepare($sql);
      $result = $stmt->execute(array(
        ':name' => $election_type
      ));

    }

    function removePartyById($party_id){
      global $dbh;
      $sql = "DELETE FROM party WHERE id = :party_id";
      $stmt = $dbh->prepare($sql);
      $result = $stmt->execute(array(
        ':party_id' => $party_id
      ));
      if(!$result){
        return false;
      }

      return true;
    }
	
	function getVoteCount($election_id, $party_id, $state_id = 0, $lga_id = 0, $ward_id = 0)
	{
		global $dbh;
      	$sql = "SELECT COUNT(v.id) AS total FROM vote v WHERE v.election_id = $election_id AND v.party_id = $party_id";
		
		if($ward_id)
			$sql .= " AND v.registered_ward_id = $ward_id";
		else if($lga_id)
		{
			$sql = "SELECT COUNT(v.id) AS total FROM vote v WHERE v.election_id = $election_id AND v.party_id = $party_id AND v.registered_ward_id IN (SELECT w.id FROM ward w WHERE w.lga_id = $lga_id)";
		}
		else if($state_id)
		{
			$sql = "SELECT COUNT(v.id) AS total FROM vote v WHERE v.election_id = $election_id AND v.party_id = $party_id AND v.registered_ward_id IN (SELECT w.id FROM ward w WHERE w.lga_id IN (SELECT l.id FROM lga l WHERE l.state_id = $state_id))";
		}
		
      	$stmt = $dbh->query($sql);
      	$result = $stmt->fetch(PDO::FETCH_ASSOC);
      	return $result;
	}

    function getTotalBrandProducts($id) {
      global $dbh;
      $sql = "SELECT COUNT(id) AS total FROM product WHERE brand_id = {$id}";
      $stmt = $dbh->query($sql);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'];
    }
	
	function getTotalCategoryProducts($id) {
      global $dbh;
      $sql = "SELECT COUNT(id) AS total FROM product WHERE category_id = {$id}";
      $stmt = $dbh->query($sql);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'];
    }
	
    function getAllElections() {
      global $dbh;
      $sql = "SELECT id, name, description FROM election";
      $stmt = $dbh->query($sql);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $result;
    }

	function login($user_name, $password) {
      global $dbh;
      $sql = "SELECT * FROM user WHERE user_name = '".$user_name."' AND password = '".$password."'";
      $stmt = $dbh->query($sql);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result;
    }
?>
