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

// modal actions 
$add_action = "";
$modify_action = $delete_action = $cancel_action = "disabled";
$show_modal = false;

switch ($action) {
    case "btn_add":

        // server side validations

        if ($name == "" || (!preg_match("/^[a-zA-Zñáéíóú ]*$/",$name))) {
            $error['name'] = 'Please write name';
        }
        if ($lastname_p == "" ) {
            $error['lastname_p'] = 'Please write lastname';
        }
        if ($lastname_m == "") {
            $error['lastname_m'] = 'Please write M. lastname';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error['email'] = 'Please write email';
        }
        if(isset($error)){
            if (count($error) > 0) {
                $show_modal = true;
                break;
            }
        }

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

            if (isset($employee['PHOTO']) && $employee['PHOTO'] != IMG_DEFAULT) {
                // if the user has the default img then we don't delete it
                if (file_exists("../imagenes/" . $employee['PHOTO'])) {
                    unlink("../imagenes/" . $employee['PHOTO']);
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

        if (isset($employee['PHOTO']) && $employee['PHOTO'] != IMG_DEFAULT) {
            // if the user has the default img then we don't delete it
            if (file_exists("../imagenes/" . $employee['PHOTO'])) {
                unlink("../imagenes/" . $employee['PHOTO']);
            }
        }

        $statement = $conn->prepare("DELETE FROM empleados WHERE ID=:ID");
        $statement->bindParam(":ID", $id);
        $statement->execute();
        header("Location: index.php");
        break;
    case "btn_cancel":
        header("Location: index.php");
        break;
    case "Select":
        $add_action = "disabled";
        $modify_action = $delete_action = $cancel_action = "";
        $show_modal = true;

        $statement = $conn->prepare("SELECT * FROM empleados WHERE ID=:ID");
        $statement->bindParam(":ID", $id);
        $statement->execute();
        $employee = $statement->fetch(PDO::FETCH_LAZY);

        $name = $employee['NAME'];
        $lastname_p = $employee['LASTNAME_P'];
        $lastname_m = $employee['LASTNAME_M'];
        $email = $employee['EMAIL'];
        $photo = $employee['PHOTO'];
        break;
}

// retrieve data from database
$statement = $conn->prepare("SELECT * FROM empleados");
$statement->execute();
$employees_list = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($employees_list);
?>
