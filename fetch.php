<?php
// take the link from api file path 
$url = 'http://localhost/api/api.php';
$ch  = curl_init($url);
// format the result 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// excute the result in data 
$data = curl_exec($ch);
// check for error 
if ($data === false) {
    echo "there is an error " . curl_error($ch);
}
// close the curl 
curl_close($ch);
// decode the result in json 
$rows = json_decode($data, true);

// foreach ($rows as $row) {
//     echo $row['name'] . " " . $row['email'];
// }
include "templates/header.php";
?>
<div class="container">
    <div class="row">
        <?php foreach ($rows as $row) { ?>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="assets/images/<?php echo $row['image'] ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title text-center"><?php echo $row['name'] ?></h5>
                        <p class="card-text"><?php echo $row['name'] ?></p>

                    </div>
                </div>
            </div>
        <?php  } ?>
    </div>

</div>


<?php
include "templates/footer.php";
