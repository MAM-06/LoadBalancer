<style>
<?php include 'CSS/main.css'; ?>
</style>
<div class="d-flex flex-column justify-content-center w-100 h-100">
  <div class="d-flex flex-column justify-content-center align-items-center">
  </div>
</div>
<?php echo " <h1><b>load balancer </b></h1>";
    $pilihan = array (
    "http://MAM-3/",
    "http://MAM-2/",
    "http://MAM-2/",
    "http://MAM-1/",
    "http://MAM-1/",
    "http://MAM-1/",
    // "http://MAM-cd/"
    );
    $acak = rand(0,5);
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "loadbalancer";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    $sql ="SELECT c.alamat, COUNT(alamat) akses, kapasitas FROM
(SELECT b.server,sesi, a.alamat,k.kapasitas FROM `beban` b
LEFT JOIN SERVER a ON a.nomor=b.server
LEFT JOIN kapasitas_server k ON k.alamat=a.alamat
) c
GROUP BY c.alamat";

$result = $conn->query($sql);
$jumlah_server = 3;
$pilihan_sementara = 0;
$server = 1;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "alamat: " . $row["alamat"]. " akses: " . $row["akses"]. " kapasitas: " . $row["kapasitas"]. "<br>";
    $yang_sudah_akses = $row["akses"];
    $kapasitas_server = $row["kapasitas"];

    if ($yang_sudah_akses < $kapasitas_server)
    $pilihan_sementara = $server;
    $server++;
  }
} else {
  echo "0 results";
}

    $urlnya = "http://MAM-".$pilihan_sementara."/";;
?>
<br>
<iframe src="<?php echo $urlnya; ?>" width="100%" height="35" style="border:3px solid black" title="lokasi server"></iframe>
<?php

$sesi = rand(1,10000);
$sql = "INSERT INTO beban (server, sesi)
VALUES ('$acak', '$sesi')";
if ($conn->query($sql) === TRUE) {
  echo "<br>Insert Data successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>