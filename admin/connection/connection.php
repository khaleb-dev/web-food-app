<?PHP
    $hostname = "localhost";
    $username = "root";
    $password = "";
    try {
        $dbh = new PDO("mysql:host=$hostname;dbname=ecp", $username, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $pdo){
        echo $pdo->getMessage();
    }
?>  