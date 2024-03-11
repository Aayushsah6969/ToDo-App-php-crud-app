<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome <span>User</span></h1>
        </div>
        <div class="task-box">
            <h2>Add/Edit Task</h2>
            <div class="task-form">
                <form action="inputTask.php" method="POST" id="taskForm">
                    <input type="hidden" name="task_id" id="taskId">
                    <input type="text" name="title" id="title" placeholder="Title">
                    <textarea name="description" id="description" cols="50" rows="6"
                        placeholder="Description"></textarea>
                    <input type="submit" id="submitButton" value="Add">
                </form>
            </div>
        </div>
        <div class="task-box">
        <h2>Task List</h2>
        <ul class="task-list">
            <?php
            // Establish connection to the database
            $servername = "localhost";
            $username = "root"; // Change this to your database username
            $password = ""; // Change this to your database password
            $dbname = "crudphp";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if form is submitted for deletion
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
                $delete_id = $_POST['delete_id'];
                // Delete task from database
                $sql = "DELETE FROM data WHERE id=$delete_id";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Task deleted successfully');</script>";
                } else {
                    echo "<script>alert('Error deleting task');</script>";
                }
            }

            // Retrieve tasks from database
            $sql = "SELECT id, title, description FROM data";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<li class='task-item'>";
                    echo "<div>";
                    echo "<strong>Title: </strong>" . $row["title"] . "<br>";
                    echo "<strong>Description: </strong>" . $row["description"];
                    echo "</div>";
                    echo "<div>";
                    // Edit button with onclick function to populate the form fields
                    echo "<button style='background-color: #007bff;' onclick=\"editTask('" . $row["id"] . "', '" . $row["title"] . "', '" . $row["description"] . "')\">Edit</button>";
                    // Delete button with onclick function to confirm deletion
                    echo "<button onclick=\"confirmDelete(" . $row["id"] . ")\" style='background-color: red;'>Delete</button>";
                    echo "</div>";
                    echo "</li>";
                }
            } else {
                echo "0 results";
            }

            // Close connection
            $conn->close();
            ?>
    </div>
    <div class="footer">
        <p>&copy;aayush 2024 Task Manager App</p>
    </div>
    <script>
        // Function to populate form fields for editing
        function editTask(taskId, title, description) {
            document.getElementById("taskId").value = taskId;
            document.getElementById("title").value = title;
            document.getElementById("description").value = description;
            // Change submit button value to 'Save' for editing
            document.getElementById("submitButton").value = "Save";
        }

        // Function to confirm deletion before submitting form
        function confirmDelete(taskId) {
            if (confirm("Are you sure you want to delete this task?")) {
                // Create a form dynamically
                var form = document.createElement("form");
                form.setAttribute("method", "post");
                form.setAttribute("action", "index.php");

                // Create a hidden input field for delete_id
                var input = document.createElement("input");
                input.setAttribute("type", "hidden");
                input.setAttribute("name", "delete_id");
                input.setAttribute("value", taskId);

                // Append the input field to the form
                form.appendChild(input);

                // Append the form to the document body
                document.body.appendChild(form);

                // Submit the form
                form.submit();
            }
        }
    </script>
</body>

</html>
