<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Schedule Form</title>
</head>
<body>
    <div class="container">
        <h2>Schedule Form</h2>

        <?php
        // Include the database connection file
        include 'include/db_connection.php';

        // Display the edit form if "Edit" button is clicked
        if (isset($_GET['edit'])) {
            // Reopen the database connection for edit form
            $db = new mysqli($servername, $username, $password, $database);

            if ($db->connect_error) {
                die("Connection failed: " . $db->connect_error);
            }

            $editId = $_GET['edit'];
            $editResult = $db->query("SELECT * FROM schedule WHERE schedule_id=$editId");

            if ($editResult !== false && $editResult->num_rows == 1) {
                $editRow = $editResult->fetch_assoc();
                ?>
                <!-- Edit form -->
                <h2>Edit Schedule</h2>
                <form action="include/schedule_process.php" method="post">
                    <input type="hidden" name="schedule_id" value="<?php echo $editRow['schedule_id']; ?>">
                    <label for="schedule_time">Schedule Time:</label>
                    <input type="time" name="schedule_time" value="<?php echo $editRow['schedule_time']; ?>" required>
                    <label for="schedule_date">Schedule Date:</label>
                    <input type="date" name="schedule_date" value="<?php echo $editRow['schedule_date']; ?>" required>
                    <label for="status">Status:</label>
                    <select name="status" required>
                        <option value="vacant" <?php echo ($editRow['status'] == 'vacant') ? 'selected' : ''; ?>>Vacant</option>
                        <option value="occupied" <?php echo ($editRow['status'] == 'occupied') ? 'selected' : ''; ?>>Occupied</option>
                    </select>
                    <button type="submit" name="edit">Update</button>
                    <a href="patient_form.php" class="button" style=" background-color: #8d0a0a; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;"> Back </a>
                </form>
                <?php

                // Close the edit form database connection
                $db->close();
                exit(); // Exit to prevent further execution
            }

            // Close the edit form database connection
            $db->close();
        } else {
            // Display the add form if "Edit" button is not clicked
            ?>
            <!-- Add form -->
            <form action="include/schedule_process.php" method="post">
                <label for="schedule_time">Schedule Time:</label>
                <input type="time" name="schedule_time" required>
                <label for="schedule_date">Schedule Date:</label>
                <input type="date" name="schedule_date" required>
                <button type="submit" name="add">Add</button>
                <a href="dashboard.php" class="button" style="   font-size: 13px; background-color: #8d0a0a; color: white; padding: 8px 14px; border: none; border-radius: 4px; cursor: pointer;"> Back </a> 
            </form>
            <?php
        }
        ?>

       

        <!-- Display added schedules -->
        <h2>Schedules</h2>

        <?php
        // Reopen the database connection for the schedule list
        $db = new mysqli($servername, $username, $password, $database);

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        // Fetch and display schedule data from the database
        $result = $db->query("SELECT * FROM schedule");

        if ($result !== false && $result->num_rows > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Schedule Time</th>';
            echo '<th>Schedule Date</th>';
            echo '<th>Status</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo "<td>{$row['schedule_time']}</td>";
                echo "<td>{$row['schedule_date']}</td>";
                echo "<td>{$row['status']}</td>";
                echo '<td>';
                echo "<a href='schedule_form.php?edit={$row['schedule_id']}' class='button edit'>Edit</a>";
                echo " | ";
                echo "<a href='include/schedule_process.php?delete={$row['schedule_id']}' class='button delete'>Delete</a>";
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No schedules found.</p>';
        }

        if ($result !== false) {
            $result->free(); // Free the result set
        }

        // Close the schedule list database connection
        $db->close();
        ?>
    </div>
</body>
</html>
