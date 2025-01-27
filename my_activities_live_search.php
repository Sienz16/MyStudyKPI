<?php
session_start();
include("config.php");

$search = $_GET["search"];

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    // Split the search string into individual words
    $keywords = explode(" ", $search);

    // Prepare the SQL query with multiple LIKE conditions
    $sql = "SELECT * FROM activities WHERE (";

    // Build the conditions dynamically for single keyword
    $conditions = [];
    foreach ($keywords as $index => $keyword) {
        $conditions[] = "activity_name LIKE '%$keyword%' OR remarks LIKE '%$keyword%'";
    }

    // Combine
    $sql .= implode(" OR ", $conditions);

    // Select only with this userID
    $sql .= ") AND userID=" . $_SESSION["UID"];

    // Add UNION to combine results from other tables
    $sql .= " UNION ";

    // Add similar conditions for the competition table
    $sql .= "SELECT * FROM competition WHERE (";

    // Build the conditions dynamically for single keyword
    $conditions = [];
    foreach ($keywords as $index => $keyword) {
        $conditions[] = "comp_name LIKE '%$keyword%' OR remark LIKE '%$keyword%'";
    }

    $sql .= implode(" OR ", $conditions);
    $sql .= ") AND userID=" . $_SESSION["UID"];

    // Add UNION to combine results from certification table
    $sql .= " UNION ";

    // Add similar conditions for the certification table
    $sql .= "SELECT * FROM certification WHERE (";

    // Build the conditions dynamically for single keyword
    $conditions = [];
    foreach ($keywords as $index => $keyword) {
        $conditions[] = "cert_name LIKE '%$keyword%' OR remark LIKE '%$keyword%'";
    }

    $sql .= implode(" OR ", $conditions);
    $sql .= ") AND userID=" . $_SESSION["UID"];

    $result = mysqli_query($conn, $sql);
    // Check for errors in the query execution
    if (!$result) {
        die("Error in SQL query: " . mysqli_error($conn));
    }
    ?>

    <table border="1" width="100%" id="projectable">
        <tr>
            <th width="5%">No</th>
            <th width="10%">Sem & Year</th>
            <th width="45%">List of Activities / Competition / Certification</th>
            <th width="15%">Level</th>
            <th width="15%">Remarks</th>
            <th width="10%">Photo</th>
        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            $numrow = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $numrow . "</td><td>" . $row["sem"] . " " . $row["year"] . "</td><td>" . $row["activity_name"] .
                    "</td><td>" . $row["remarks"] . "</td><td>" . $row["img_path"] . "</td>";
                echo '<td> <a href="my_activities_edit.php?id=' . $row["activity_id"] . '">Edit</a>&nbsp;|&nbsp;';
                echo '<a href="my_activities_delete.php?id=' . $row["activity_id"] . '" onClick="return confirm(\'Delete?\');">Delete</a> </td>';
                echo "</tr>" . "\n\t\t";
                $numrow++;
            }
        } else {
            echo '<tr><td colspan="6">0 results</td></tr>';
        }
        mysqli_close($conn);
        ?>
    </table>

<?php
} else {
    echo "Search query is empty<br>";
}
?>