<?php
// database connection details
 $db = new PDO("sqlsrv:Server=;", "", "");
// if could not connect to database
if (!$db) {

    // stop execution and display error message
    die('Error connecting to the database!<br>Make sure you have specified correct values for host, username, password and database.');
}

// how many records should be displayed on a page?
$records_per_page = 10;

// include the pagination class
require 'Zebra_Pagination.php';

// instantiate the pagination object
$pagination = new Zebra_Pagination();

// if we want to show records in reversed order
if (isset($_GET['reversed'])) {

    // show records in reversed order
    $pagination->reverse(true);

	$stmt =$db->prepare("SELECT count(*) FROM katrak_islem JOIN isemri ON isemri.isemri_id = katrak_islem.isemri_id JOIN blok ON blok.blok_no=isemri.blok_no WHERE isemri.islem_id = 2 and isemri.durum = 0");
	$stmt->execute();
	$row_count = $stmt->fetchColumn();
    $pagination->records($row_count);


    // records per page
    $pagination->records_per_page($records_per_page);
}

// set position of the next/previous page links
$pagination->navigation_position(isset($_GET['navigation_position']) && in_array($_GET['navigation_position'], array('left', 'right')) ? $_GET['navigation_position'] : 'outside');



$MySQL = "SELECT * FROM katrak_islem JOIN isemri ON isemri.isemri_id = katrak_islem.isemri_id JOIN blok ON blok.blok_no=isemri.blok_no
		 WHERE isemri.islem_id = 2 and isemri.durum = 0 ORDER BY katrak_islem_id OFFSET ".(($pagination->get_page() - 1) * $records_per_page)." ROWS FETCH NEXT ".$records_per_page." ROWS ONLY";



echo $MySQL;



    $sorgu2=$db->prepare($MySQL);
	$sorgu2->setFetchMode(PDO::FETCH_ASSOC);
	$sorgu2->execute();
	$result = $sorgu2->fetchAll();
        
	$sorgu3=$db->prepare("SELECT count(*) FROM katrak_islem JOIN isemri ON isemri.isemri_id = katrak_islem.isemri_id JOIN blok ON blok.blok_no=isemri.blok_no WHERE isemri.islem_id = 2 and isemri.durum = 0");
	$sorgu3->execute();
	$rows2 = $sorgu3->fetchColumn();

if (!isset($_GET['reversed'])) {

    // pass the total number of records to the pagination class
    $pagination->records($rows2);
    // records per page

    $pagination->records_per_page($records_per_page);
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <div class="container well">
        <h1 class="text-center">Bootstrap Pagination in PHP and MSSQL</h1>
        <div class="row">
            <div class="col-md-10">
                <div style="height: 600px; overflow-y: auto;">
                    <table id="" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 0;
							foreach($result as $key => $row): ?>
                            <tr <?php echo $index++ % 2 ? ' class="even"' : '' ; ?>>
                                <td><?php echo $row['blok_no']; ?></td>
                                <td><?php echo $row['baslangic_tarihi']; ?></td>
                                <td><?php echo $row['bitis_tarihi']; ?></td>
                                <td><?php echo $row['yukseklik']; ?></td>
                              
                            </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                    <div class="text-center">

                        <?php

// render the pagination links
$pagination->render();

?>

                    </div>
                </div>
            </div>
        </div>
    </div>



















    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>