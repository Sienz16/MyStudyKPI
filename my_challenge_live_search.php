<?php
session_start();
include("config.php");

$search = $_GET["search"];

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    // Split the search string into individual words
    $keywords = explode(" ", $search);

    // Prepare the SQL query with multiple LIKE conditions
    $sql = "SELECT * FROM challenge WHERE (";

    // Build the conditions dynamically for single keyword
    $conditions = [];
    foreach ($keywords as $index => $keyword) {
        $conditions[] = "challenge LIKE '%$keyword%' OR remark LIKE '%$keyword%'";
    }                
    
    // Combine
    $sql .= implode(" OR ", $conditions);

    // Select only with this userID
    $sql .= " OR challenge LIKE '%$search%') AND userID=" . $_SESSION["UID"]; 
    
    $result = mysqli_query($conn, $sql);
    ?>
    <table border="1" width="100%" id="projectable">
    <tr>
        <th width="5%">No</th>
        <th width="10%">Sem & Year</th>
        <th width="30%">Challenge</th>
        <th width="30%">Plan</th>
        <th width="15%">Remark</th>
        <th width="10%">Photo</th>
        <th width="10%">Action</th>
    </tr>
    <?php
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        $numrow=1;
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $numrow . "</td><td>". $row["sem"] . " " . $row["year"]. "</td><td>" . $row["challenge"] .
                "</td><td>" . $row["plan"] . "</td><td>" . $row["remark"] . "</td><td>" . $row["img_path"] . "</td>";
            echo '<td> <a href="my_challenge_edit.php?id=' . $row["ch_id"] . '">Edit</a>&nbsp;|&nbsp;';
            echo '<a href="my_challenge_delete.php?id=' . $row["ch_id"] . '" onClick="return confirm(\'Delete?\');">Delete</a> </td>';
            echo "</tr>" . "\n\t\t";
            $numrow++;
        }
    } 
    else {
        echo '<tr><td colspan="7">0 results</td></tr>';
    } 
    mysqli_close($conn);
    ?>
</table>
    
<?php
} else {
    echo "Search query is empty<br>";
}
?>