<?php
// start the session
session_start();
// check if user is already login 
if (isset($_SESSION['adminAPI'])) {
    header("Location:index.php");
} else {

    // connect to database
    include "connect.php";
    include "templates/header.php";

    // validate the form 
    if (isset($_POST['login'])) {
        // convert the inputs to string 
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        // hash the password with md5 algorithm
        $enc = md5($password);
        // check if user exists and he is admin 
        $sql = "SELECT * FROM users WHERE email = ? AND password = ? AND groupid = 1 LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $enc);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $fetch = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) == 1) {
            $_SESSION['adminAPI'] = $fetch['name'];
            // echo $_SESSION['adminAPI'];
            header("Location:index.php");
        } else {
            echo "<div class='alert alert-danger text-center'>email or password incorrect</div>";
        }
        mysqli_stmt_close($stmt);
    }

?>



    <div class="container mt-5">
        <h3 class="text-center">Admin Login</h3>
        <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>" autocomplete="off">
            <div class="row mb-3 mt-5 w-50 m-auto">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control" id="inputEmail3">
                </div>
            </div>
            <div class="row mb-3 w-50 m-auto">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="password" class="form-control" id="inputPassword3">
                </div>
            </div>
            <div class="row mb-3 w-25 m-auto">
                <button type="submit" name="login" class="btn btn-primary ms-auto">Sign in</button>
            </div>
        </form>
    </div>


<?php

}

include "templates/footer.php";
