<?php
include('../conexion/conexion.php');

// print_r($_POST);
// echo $_POST['name'];

$id = isset($_POST["id"]) ? $_POST["id"] : "";
$name = isset($_POST["name"]) ? $_POST["name"] : "";
$lastname_p = isset($_POST["lastname_p"]) ? $_POST["lastname_p"] : "";
$lastname_m = isset($_POST['lastname_m']) ? $_POST["lastname_m"] : "" ;
$email = isset($_POST['email']) ? $_POST['email'] : "" ;
$photo = isset($_POST['photo']) ? $_POST['photo'] : "" ;

$action = (isset($_POST["action"])) ? $_POST["action"] : "";

switch($action){
    case "btn_add":
        // inser info in DB
        $statement = $conn->prepare("INSERT INTO empleados(NAME, LASTNAME_P, LASTNAME_M, EMAIL, PHOTO) 
        VALUES (:NAME, :LASTNAME_P, :LASTNAME_M, :EMAIL, :PHOTO)");
        $statement->bindParam(":NAME", $name);
        $statement->bindParam(":LASTNAME_P", $lastname_p);
        $statement->bindParam(":LASTNAME_M", $lastname_m);
        $statement->bindParam(":EMAIL", $email);
        $statement->bindParam(":PHOTO", $photo);
        $statement->execute();

        echo "Clicked add";
        break;
    case "btn_modify":
        echo "Clicked modify";
        break;
    case "btn_delete":
        echo "Clicked delete";
        break;
    case "btn_cancel" :
        echo "Clicked cancel";
        break;

}
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD with PHP and MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <h1>Hello, world!</h1>
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="">ID</label>
            <input type="text" name="id" value="<?php echo $id; ?>" placeholder="ID" id="id" required="required">
            <label for="">Name</label>
            <input type="text" name="name"  value="<?php echo $name; ?>" placeholder="Name" id="name" required="required">
            <label for="">Lastname</label>
            <input type="text" name="lastname_p"  value="<?php echo $lastname_p; ?>" placeholder="Lastname" id="lastname_p" required="required">
            <label for="">Mother's lastname</label>
            <input type="text" name="lastname_m"  value="<?php echo $lastname_m; ?>" placeholder="Mother's lastname " id="lastname_m" required="required">
            <label for="">Email</label>
            <input type="text" name="email"  value="<?php echo $email; ?>" placeholder="Email" id="email" required="required">
            <label for="">Photo</label>
            <input type="text" name="photo"  value="<?php echo $photo; ?>" placeholder="Photo" id="photo" required="required">
            <button type="submit" value="btn_add" name="action" id="btn_add" class="btn btn-primary">Add</button>
            <button type="submit" value="btn_modify" name="action" id="btn_modify" class="btn btn-warning">Modify</button>
            <button type="submit" value="btn_delete" name="action" id="btn_delete" class="btn btn-danger">Delete</button>
            <button type="submit" value="btn_cancel" name="action" id="btn_cancel" class="btn btn-secondary">Cancel</button>

        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>