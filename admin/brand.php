<?php
require_once "../includes/config.php";
require_once "../controller/BrandController.php";
require_once "../includes/layout/backHeader.php";

$titleErr = '';

if (isset($_POST['submit'])) {
    $title = $_POST['title'];

    //Title   
    $title = trim($_POST['title']);
    if (empty($title)) {
        $titleErr = "Please enter product title";
    } else if (!preg_match('/^[a-zA-Z0-9\s]+$/', $title)) {
        $titleErr = "Title can only contain letters, numbers and white spaces";
    } else {
        //Image // NEEDS EXTRA WORK
        if ($_FILES["file"]["error"] > 0) {
            $imageErr = "Please upload an image";
        } else {
            $file = $_FILES["file"]['tmp_name'];
            list($width, $height) = getimagesize($file);
            if (
                (($_FILES['file']['type'] == "image/gif") ||
                    ($_FILES['file']['type'] == "image/jpeg") ||
                    ($_FILES['file']['type'] == "image/png") ||
                    ($_FILES['file']['type'] == "image/pjpeg")) &&
                ($_FILES['file']['size'] < 10000000)
            ) {
                if ($_FILES['file']['error'] > 0) {
                    echo "error code: " . $_FILES['file']['error'];
                } else {
                    if (file_exists("../assets/img/" . $_FILES['file']['name'])) {
                        echo "no dude, you already have tha file!";
                    } else if ($width > "2000" || $height > "1200") {
                        $imageErr = "Error : image size must smaller than 2000 x 1200 pixels.";
                    } else {
                        move_uploaded_file($_FILES['file']['tmp_name'], "../assets/img/" . $_FILES['file']['name']);
                        $myFile = $_FILES['file']['name'];
                        $dbCon = dbCon($user, $pass);
                        $query = $dbCon->prepare("INSERT INTO brand(`title`, `image`) VALUES ('$title', '$myFile')");
                        $query->execute();
                        $upSucces = 'status added';
                    }
                }
            } else {
                echo "invalid file!";
            }
        }
    }
}


if (isset($_GET['delete'])) {
    $brandID = $_GET['delete'];
    $dbCon = dbCon($user, $pass);
    $query = $dbCon->prepare("DELETE FROM brand WHERE brandID = $brandID");
    $query->execute();

    //header("Location: ../../admin/brand.php?status=deleted&id=$brandID");
}else{
    //header("Location: ../../admin/brand.php?status=0");
}
?>

<div class="container">
    <div class="column">
        <h3>Add New Brand</h3>
        <form name="brand" method="post" enctype="multipart/form-data">
            <div class="column">
                <div class="input-field">
                    <label for="title">
                        <?php echo $titleErr ?>
                    </label>
                    <label class="w-100 p-1" for="title">Title</label>
                    <input id="title" placeholder="Title" name="title" type="text" class="validate w-75 p-2" required=""
                        aria-required="true">
                </div>
            </div>
            <div class="input-field">
                <label class="w-100 p-1" for="title">Image</label>
                <input type='file' name='file' />
            </div>
            <br />
            <button class="btn btn-dark" type="submit" name="submit">Add Brand
            </button>
        </form>
    </div>
    <hr>
    <div class="column">
        <h2>All Brands</h2>
        <div class="column">
            <table class="highlight">
                <thead>
                    <tr>
                        <th>title</th>
                        <th>image</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $brands = new BrandController;
                        $result = $brands->all();
                        if ($result) {
                            foreach ($result as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['title'] . "</td>";
                                echo "<td>" . "<img src='../assets/img/" . $row['image'] . "' width='100px' alt='images'>" . "</td>";
                                echo "<td>";
                                echo "</td>";
                                echo '<td><a href="editBrand.php?id=' . $row['brandID'] . '" class="waves-effect waves-light btn" ">Edit</a></td>';
                                echo '<td><a href="brand.php?delete=' . $row['brandID'] . '" class="waves-effect waves-light btn red" onclick="return confirm(\'Delete! are you sure?\')">Delete</a></td>';
                                echo "</tr>";
                            }
                        }
                        ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require "../includes/layout/backFooter.php"; ?>