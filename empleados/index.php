<?php
include('../conexion/conexion.php');
include('../consts.php');

// print_r($_POST);
// echo $_POST['name'];

$id = isset($_POST["id"]) ? $_POST["id"] : "";
$name = isset($_POST["name"]) ? $_POST["name"] : "";
$lastname_p = isset($_POST["lastname_p"]) ? $_POST["lastname_p"] : "";
$lastname_m = isset($_POST['lastname_m']) ? $_POST["lastname_m"] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
$photo = isset($_FILES['photo']['name']) ? $_FILES['photo']['name'] : "";

$action = (isset($_POST["action"])) ? $_POST["action"] : "";

switch ($action) {
    case "btn_add":
        $date = new DateTime();
        // if no img, then we save an img name by default
        $photo_name = ($photo != '') ? $date->getTimestamp() . "_" . $_FILES['photo']['name'] : IMG_DEFAULT;
        $temporary_photo = $_FILES['photo']['tmp_name'];

        if ($temporary_photo != '') {
            move_uploaded_file($temporary_photo, "../imagenes/" . $photo_name);
        }
        // inser info in DB
        $statement = $conn->prepare("INSERT INTO empleados(NAME, LASTNAME_P, LASTNAME_M, EMAIL, PHOTO) 
        VALUES (:NAME, :LASTNAME_P, :LASTNAME_M, :EMAIL, :PHOTO)");
        $statement->bindParam(":NAME", $name);
        $statement->bindParam(":LASTNAME_P", $lastname_p);
        $statement->bindParam(":LASTNAME_M", $lastname_m);
        $statement->bindParam(":EMAIL", $email);
        $statement->bindParam(":PHOTO", $photo_name);
        $statement->execute();
        header("Location: index.php");
        break;
    case "btn_modify":
        $statement = $conn->prepare("UPDATE empleados SET NAME=:NAME, LASTNAME_P=:LASTNAME_P, LASTNAME_M=:LASTNAME_M, EMAIL=:EMAIL WHERE ID=:ID");
        $statement->bindParam(":NAME", $name);
        $statement->bindParam(":LASTNAME_P", $lastname_p);
        $statement->bindParam(":LASTNAME_M", $lastname_m);
        $statement->bindParam(":EMAIL", $email);
        //$statement->bindParam(":PHOTO", $photo_name);
        $statement->bindParam(":ID", $id);
        $statement->execute();

        // Update photo
        $date = new DateTime();
        // if no img, then we save an img name by default
        $photo_name = ($photo != '') ? $date->getTimestamp() . "_" . $_FILES['photo']['name'] : IMG_DEFAULT;
        $temporary_photo = $_FILES['photo']['tmp_name'];

        if ($temporary_photo != '') {
            // move new photo
            move_uploaded_file($temporary_photo, "../imagenes/" . $photo_name);

            // retrieve current photo to delete
            $statement = $conn->prepare("SELECT PHOTO FROM empleados WHERE ID=:ID");
            $statement->bindParam(":ID", $id);
            $statement->execute();
            $employee = $statement->fetch(PDO::FETCH_LAZY);

            if (isset($employee['PHOTO'])) {
                // if the user has the default img then we don't delete it
                if ($employee['PHOTO'] != IMG_DEFAULT) {
                    if (file_exists("../imagenes/" . $employee['PHOTO'])) {
                        unlink("../imagenes/" . $employee['PHOTO']);
                    }
                }
            }

            $statement = $conn->prepare("UPDATE empleados SET PHOTO = :PHOTO WHERE ID = :ID");
            $statement->bindParam("PHOTO", $photo_name);
            $statement->bindParam("ID", $id);
            $statement->execute();
        }
        header("Location: index.php");
        break;
    case "btn_delete":

        $statement = $conn->prepare("SELECT PHOTO FROM empleados WHERE ID=:ID");
        $statement->bindParam(":ID", $id);
        $statement->execute();
        $employee = $statement->fetch(PDO::FETCH_LAZY);

        if (isset($employee['PHOTO'])) {
            // if the user has the default img then we don't delete it
            if ($employee['PHOTO'] != IMG_DEFAULT) {
                if (file_exists("../imagenes/" . $employee['PHOTO'])) {
                    unlink("../imagenes/" . $employee['PHOTO']);
                }
            }
        }

        $statement = $conn->prepare("DELETE FROM empleados WHERE ID=:ID");
        $statement->bindParam(":ID", $id);
        $statement->execute();
        header("Location: index.php");
        break;
    case "btn_cancel":
        echo "Clicked cancel";
        break;
}

// retrieve data from database
$statement = $conn->prepare("SELECT * FROM empleados");
$statement->execute();
$employees_list = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($employees_list);
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD with PHP and MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <h1>Company system</h1>
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Add register
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Employee</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" class="" name="id" value="<?php echo $id; ?>" placeholder="ID" id="id" required="required">
                                <div class="form-group col-4">
                                    <label for="">Name(s):</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" placeholder="Name" id="name" required="required"><br>
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Lastname:</label>
                                    <input type="text" class="form-control" name="lastname_p" value="<?php echo $lastname_p; ?>" placeholder="Lastname" id="lastname_p" required="required"><br>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">M. lastname:</label>
                                    <input type="text" class="form-control" name="lastname_m" value="<?php echo $lastname_m; ?>" placeholder="M. lastname " id="lastname_m" required="required"><br>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="">Email:</label>
                                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>" placeholder="Email" id="email" required="required"><br>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="">Photo:</label>
                                    <input type="file" class="form-control" accept="image/*" name="photo" value="<?php echo $photo; ?>" placeholder="Photo" id="photo"><br>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" value="btn_add" name="action" id="btn_add" class="btn btn-success">Add</button>
                            <button type="submit" value="btn_modify" name="action" id="btn_modify" class="btn btn-warning">Modify</button>
                            <button type="submit" value="btn_delete" name="action" id="btn_delete" class="btn btn-danger">Delete</button>
                            <button type="submit" value="btn_cancel" name="action" id="btn_cancel" class="btn btn-primary">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Full name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <?php foreach ($employees_list as $employee) { ?>
                    <tr>
                        <td><img class="img-thumbnail" width="100px" height="100px" src="../imagenes/<?php echo $employee["PHOTO"] ?>" alt="user profile image"></td>
                        <td><?php echo $employee["NAME"] . " " .  $employee["LASTNAME_P"] . " " . $employee["LASTNAME_M"] ?> </td>
                        <td><?php echo $employee["EMAIL"] ?></td>
                        <td>
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $employee["ID"] ?>">
                                <input type="hidden" name="name" value="<?php echo $employee["NAME"] ?>">
                                <input type="hidden" name="lastname_p" value="<?php echo $employee["LASTNAME_P"] ?>">
                                <input type="hidden" name="lastname_m" value="<?php echo $employee["LASTNAME_M"] ?>">
                                <input type="hidden" name="email" value="<?php echo $employee["EMAIL"] ?>">
                                <input type="hidden" name="photo" value="<?php echo $employee["PHOTO"] ?>">

                                <input type="submit" value="Select" name="action">
                                <button type="submit" value="btn_delete" name="action" id="btn_delete" class="btn btn-danger">Delete</button>
                            </form>

                        </td>
                    </tr>
                <?php } ?>
            </table>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>