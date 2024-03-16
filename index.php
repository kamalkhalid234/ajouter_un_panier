<?php

include ("connexion.php");

session_start();
$affciche = 0;
if (isset ($_POST['add_to_cart'])) {
    if (isset ($_SESSION['cart'])) {
        $session_array_id = array_column($_SESSION['cart'], "id");

        if (!in_array($_GET['id'], $session_array_id)) {
            $session_array = array(
                'id' => $_GET['id'],
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'quantite' => $_POST['quantite']
            );
            $_SESSION['cart'][] = $session_array;
        }

    } else {
        $session_array = array(
            'id' => $_GET['id'],
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'quantite' => $_POST['quantite']
        );
        $_SESSION['cart'][] = $session_array;
        $affciche = 1;

    }
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div>
        <?php

        if ($affciche == 1) {
            echo "<div class='alert alert-danger' role='alert'>
  A simple danger alert—check it out!
</div>";
        }
        ?>
    </div>
    <div class="container fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="text-center">Shopping cart Date</h2>
                    <div class="col-md-12">
                        <div class="row">



                            <?php

                            $query = "SELECT * FROM  products";
                            $result = mysqli_query($con, $query);

                            while ($row = mysqli_fetch_assoc($result)) { ?>
                            <div class="col-md-4">
                                <form action="index.php?id=<?= $row['id'] ?>" method="post"
                                    enctype="multipart/form-data">
                                    <img src="admin/<?= $row['img'] ?>" style='height:150px;'>
                                    <h5 class="text-center">
                                        <?= $row['name'] ?>
                                    </h5>
                                    <h5 class="text-center">
                                        $
                                        <?= number_format($row['price'], 2) ?>
                                    </h5>

                                    <input type="hidden" name="name" value=" <?= $row['name'] ?>">
                                    <input type="hidden" name="price" value=" <?= number_format($row['price'], 2) ?>">
                                    <input type="number" name="quantite" value='1' class="form-control">
                                    <input type="submit" name="add_to_cart" class="btn btn-warning btn-block  my-2"
                                        value="Add Tocart">
                                </form>
                            </div>


                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="text-center">Item Selected</h2>
                    <?php


                    $total = 0;
                    $output = "";

                    $output .= "
                    <table class='table table-bordered table-striped'>
                         <tr>
                             <td>ID</td>
                             <td>Item Name</td>
                             <td>Item Price</td>
                             <td>Item qauntite</td>
                             <td>Total Price</td>
                             <td>Action</td>
                         </tr>
                    ";
                    if (!empty ($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $key => $value) {
                            $output .= "
                              <tr>
                                 <td>" . $value['id'] . "</td>
                                 <td>" . $value['name'] . "</td>
                                 <td>$" . $value['price'] . "</td>
                                 <td>" . $value['quantite'] . "</td>
                                 <td>$" . number_format($value['price'] * $value['quantite']) . "</td>
                                 <td>
                                   <a href='index.php?action=remove&id=" . $value['id'] . "'>
                                   <button class='btn btn-danger btn-block'>Remove</button>
                                   </a>
                                 </td>
                             </tr>
                                    ";


                            $total = $total + $value['quantite'] * $value['price'];
                        }
                        $output .= "
                             <tr>
                             <td colspan='3'></td>
                             <td><b>Total Price </b></td>              
                             <td> $" . number_format($total, 2) . "</td>
                             <td> 
                                 <a href='index.php?action=clerall'>
                                     <button class='btn btn-warning btn-block'>Clear</button>
                                 </a>
                             </td>
                         </tr>
                        ";
                    }
                    echo $output;

                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset ($_GET['action'])) {


        //supprime tout le panie
        if ($_GET['action'] == "clerall") {
            unset($_SESSION['cart']);
        }



        //supprime 1 par 1
        // unset =>romove
        if ($_GET['action'] == "remove") {

            foreach ($_SESSION['cart'] as $key => $value) {
                if ($value['id'] == $_GET['id']) {

                    unset($_SESSION['cart'][$key]);
                }

            }
        }
    }


    ?>
</body>

</html>