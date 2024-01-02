<?php
session_start();

include "connect.php";
// echo md5("admin123");
include "templates/header.php";
// check for session 
if (isset($_SESSION['adminAPI'])) {
    // import navbar
    include "templates/navbar.php";
    $do = "";
    if (isset($_GET['do'])) {
        $do = $_GET['do'];
    } else {
        $do = "Manage";
    }
    // Manage ==> default page 
    if ($do == "Manage") {
        // bring all the users with groupid = 0 
        $sql = "SELECT * FROM users WHERE groupid = 0";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // echo "<pre>";
        // print_r($rows);
        // echo "</pre>";


?>

        <h3 class="text-center text-danger">hello from manage</h3>
        <div class="container">

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#id</th>
                        <th scope="col">image</th>
                        <th scope="col">name</th>
                        <th scope="col">email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rows as $row) {
                    ?>
                        <tr>
                            <th><?php echo $row['id'] ?></th>
                            <td><img src="assets/images/<?php echo $row['image'] ?>" alt="Profile" class="rounded-circle" width="50" height="50"></td>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['email'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <a class="btn btn-primary" href="index.php?do=Add">add user</a>




        <!-- form to add users  -->
    <?php } elseif ($do == "Add") {
        echo "hello from add"; ?>
        <div class="container mt-5">
            <h3 class="text-center">add user</h3>
            <form method="POST" action="index.php?do=Insert" autocomplete="off" enctype="multipart/form-data">
                <div class="row mb-3 mt-5 w-50 m-auto">
                    <label for="name" class="col-sm-2 col-form-label">name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" id="name">
                    </div>
                </div>
                <div class="row mb-3 mt-5 w-50 m-auto">
                    <label for="password" class="col-sm-2 col-form-label">password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" id="password">
                    </div>
                </div>
                <div class="row mb-3 mt-5 w-50 m-auto">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" id="inputEmail3">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">choose file</label>
                        <input class="form-control" type="file" name="image" id="formFile" accept=".jpg, .jpeg, .png, .gif">
                    </div>
                </div>
                <div class="row mb-3 w-25 m-auto">
                    <button type="submit" name="adduser" class="btn btn-primary ms-auto">add user</button>
                </div>
            </form>
        </div>

        <!-- insert the user in database  -->
<?php } elseif ($do == "Insert") {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        // hash the password 
        $enc = md5($password);
        // add image
        // array for errors 
        $formErrors = array();
        if (!empty($_FILES['image']['name'])) {
            // image name 
            $imageName = $_FILES['image']['name'];
            // image size 
            $imageSize = $_FILES['image']['size'];
            // image temp name 
            $imageTmp  = $_FILES['image']['tmp_name'];
            // image type 
            $imageType = $_FILES['image']['type'];
            // image allow extension  array accept jpeg,jpg,png,gif
            $allowedExtension = array("jpeg", "jpg", "png", "gif");

            $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            if (!in_array($imageExtension, $allowedExtension)) {
                $formErrors[] = 'This extension is not allowed';
            }

            if ($imageSize > 4194304) {
                $formErrors[] = 'Your profile picture cannot be more than 4 MB';
            }

            if (empty($formErrors)) {
                $image = $imageName . "_" . date("y.m.d") . "." . $imageExtension;
                move_uploaded_file($imageTmp, "assets/images/" . $image);
            }
        }
        // insert the data into database 
        $sql = "INSERT INTO users (name,email,password,image) VALUES (?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $enc, $image);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            echo "<div class='alert alert-success text-center'>record inserted</div>";
            // return to default page ==> Manage 
            header("Refresh:5,url=index.php?do=Manage");
        } else {
            echo "<div class='alert alert-danger text-center'>" . mysqli_stmt_error($stmt) . "</div>";
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo "<div class='alert alert-danger text-center'>you cant access this page directly</div>";
    header("Refresh:5;url=login.php");
}

include "templates/footer.php";
