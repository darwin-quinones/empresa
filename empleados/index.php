<?php
require './employees.php';
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD with PHP and MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>

    <div class="container">
        <br>
        <center>
            <h1>Company System - CRUD with PHP and MySQL</h1>
        </center>
        <form action="" method="post" enctype="multipart/form-data">
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
                                    <input type="text" class="form-control <?php echo (isset($error['name'])) ? "is-invalid" : "" ?> " name="name" value="<?php echo $name; ?>" placeholder="Name" id="name">
                                    <div class="invalid-feedback">
                                        <?php echo (isset($error['name'])) ? $error['name'] : "" ?>
                                    </div>
                                </div><br>
                                <div class="form-group col-4">
                                    <label for="">Lastname:</label>
                                    <input type="text" class="form-control <?php echo (isset($error['lastname_p'])) ? "is-invalid" : "" ?>" name="lastname_p" value="<?php echo $lastname_p; ?>" placeholder="Lastname" id="lastname_p">
                                    <div class="invalid-feedback">
                                        <?php echo (isset($error['lastname_p'])) ? $error['lastname_p'] : "" ?>
                                    </div>
                                </div><br>
                                <div class="form-group col-md-4">
                                    <label for="">M. lastname:</label>
                                    <input type="text" class="form-control <?php echo (isset($error['lastname_m'])) ? "is-invalid" : "" ?>" name="lastname_m" value="<?php echo $lastname_m; ?>" placeholder="M. lastname " id="lastname_m">
                                    <div class="invalid-feedback">
                                        <?php echo (isset($error['lastname_m'])) ? $error['lastname_m'] : "" ?>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="">Email:</label>
                                    <input type="text" class="form-control <?php echo (isset($error['email'])) ? "is-invalid" : "" ?>" name="email" value="<?php echo $email; ?>" placeholder="Email" id="email">
                                    <div class="invalid-feedback">
                                        <?php echo (isset($error['email'])) ? $error['email'] : "" ?>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">

                                    <?php if ($photo != '') { ?>
                                        <label for="">Photo:</label>
                                        <br>
                                        <center><img class="img-thumbnail rounded mx-outo d-block" src="../imagenes/<?php echo $photo ?>" width="200px" alt="user profile img"></center>
                                    <?php } ?>
                                    <br>
                                    <br>
                                    <input type="file" class="form-control" accept="image/*" name="photo" placeholder="Photo" id="photo"><br>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" value="btn_add" <?php echo $add_action ?> name="action" id="btn_add" class="btn btn-success">Add</button>
                            <button type="submit" value="btn_modify" <?php echo $modify_action ?> name="action" id="btn_modify" class="btn btn-warning">Modify</button>
                            <button type="submit" value="btn_delete" onclick="return Confirm('Do you really want to delete this item?')" <?php echo $delete_action ?> name="action" id="btn_delete" class="btn btn-danger">Delete</button>
                            <button type="submit" value="btn_cancel" <?php echo $cancel_action ?> name="action" id="btn_cancel" class="btn btn-primary">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <br>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add register
        </button><br><br>
        <div class="row">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
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
                                <input type="submit" value="Select" id="btn_select" class="btn btn-info" name="action">
                                <button onclick="return Confirm('Do you really want to delete this item?')" type="submit" value="btn_delete" name="action" id="btn_delete" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <?php if ($show_modal) { ?>
            <script>
                var myModalEl = document.querySelector('#exampleModal')
                var modal = bootstrap.Modal.getOrCreateInstance(myModalEl)
                modal.show()
            </script>
        <?php } ?>

    </div>

    <script>
        function Confirm(message) {
            return (confirm(message)) ? true : false
        }
    </script>

</body>

</html>