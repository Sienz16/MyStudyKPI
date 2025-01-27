<?PHP
session_start();
include('config.php');

//variables
$action="";
$id="";
$sem = "";
$year = "";
$challenge =" ";
$remark = "";

//for upload
$target_dir = "uploads/";
$target_file = "";
$uploadOk = 0;
$imageFileType = "";
$uploadfileName = "";

//this block is called when button Submit is clicked
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //values for add or edit
    $sem = $_POST["sem"];
    $year = $_POST["year"];
    $challenge = trim($_POST["challenge"]);
    $plan = trim($_POST["plan"]);
    $remark = trim($_POST["remark"]);
    
    $filetmp = $_FILES["fileToUpload"];
    //file of the image/photo file
    $uploadfileName = $filetmp["name"];

    //Check if there is an image to be uploaded
    //IF no image
    if(isset($_FILES["fileToUpload"]) &&  $_FILES["fileToUpload"]["name"] == ""){
        $sql = "INSERT INTO challenge (userID, sem, year, challenge, plan, remark, img_path)
            VALUES (" . $_SESSION["UID"] . ", " . $sem . ", '". $year . "', '" . $challenge . "','" . $plan . "', '" . $remark . "', '" . $uploadfileName . "')";
       
        $status = insertTo_DBTable($conn, $sql);

        if($status){
            echo "Your data had been saved successfully! :)<br>";
            echo '<a href="my_challenge.php">Go Back</a>';             
        } 
        else{
            echo '<a href="my_challenges.php">Go Back</a>';
        }   
    }
    //IF there is image
    else if(isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK){
        //Variable to determine for image upload is OK
        $uploadOk = 1;        
        $filetmp = $_FILES["fileToUpload"];

        //file of the image/photo file
        $uploadfileName = $filetmp["name"];
                 
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);        
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if file already exists
        if(file_exists($target_file)){
            echo "[ERROR]: Sorry, the image file $uploadfileName already exists. :(<br>";
            $uploadOk = 0;
        }
        
        // Check file size <= 488.28KB or 500000 bytes
        if($_FILES["fileToUpload"]["size"] > 500000){
            echo "[ERROR]: Sorry, your file is too large. :( Tips: Try resizing your image!<br>";
            $uploadOk = 0;
        }
        
        // Allow only these file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ){
            echo "[ERROR]: Sorry, only JPG, JPEG, PNG & GIF files are allowed. :(<br>";
            $uploadOk = 0;
        } 

        //If uploadOk, then try add to database first 
        //uploadOK=1 if there is image to be uploaded, filename not exists, file size is ok and format ok     
        if($uploadOk){
            $sql = "INSERT INTO challenge (userID, sem, year, challenge, plan, remark, img_path)
            VALUES (" . $_SESSION["UID"] . ", " . $sem . ", '". $year . "', '" . $challenge . "','" . $plan . "', '" . $remark . "', '" . $uploadfileName . "')";
            
            $status = insertTo_DBTable($conn, $sql);

            if($status){
                if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
                    //Image file successfully uploaded 
                    //Prompt successfull record message
                    echo "Your data had been saved successfully! :)<br>";
                    echo '<a href="my_challenge.php">Back</a>'; 
                } 
                else{
                    //There is an error while uploading image 
                    echo "Sorry, there was an error when trying to upload your file. :(<br>";  
                    echo '<a href="javascript:history.back()">Go Back</a>';              
                }
            } 
            else{
                echo '<a href="javascript:history.back()">Go Back</a>';
            }
        }
        else{            
            echo '<a href="javascript:history.back()">Go Back</a>';
        }
    }    
}

//close db connection
mysqli_close($conn);

//Function to insert data to database table
function insertTo_DBTable($conn, $sql){
    if(mysqli_query($conn, $sql)){
        return true;
    } 
    else{
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}
?>