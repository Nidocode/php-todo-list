<?php
$conn = new mysqli("localhost", "root", "", "todolist"); 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['addtask'])) { // button name "addtask"
    $task = mysqli_real_escape_string($conn, $_POST['todo']);   // input name "todo"
    $conn->query("INSERT INTO tasks (task) VALUES ('$task')");
    header("Location: index.php"); // After the insert query runs, the user is redirected back to index.php
}



if (isset($_GET['complete'])) {
    $id = $_GET['complete'];

    $result= $conn->query("SELECT completed FROM tasks WHERE id = $id");

    if($row = $result->fetch_assoc()){
        $status = $row['completed'];

        if($status == 1){
        $conn->query("UPDATE tasks SET completed = 0 WHERE id = $id");  
        }
        else{$conn->query("UPDATE tasks SET completed = 1 WHERE id = $id");}
    }
    header("Location: index.php");
}


if (isset($_GET['delete'])) {  # Checks if the URL has a delete parameter.
    $id = $_GET['delete'];  # Retrieves the ID from the URL and stores it in $id
    $conn->query("DELETE FROM tasks WHERE id = $id");
    header("Location: index.php");
}


if (isset($_POST['update_task'])) {
    $id = $_POST['edit_id'];
    $updatedTask = mysqli_real_escape_string($conn, $_POST['edit_task']); 

    $conn->query("UPDATE tasks SET task = '$updatedTask' WHERE id = $id");
    header("Location: index.php");
    
}






# $_POST is a superglobal array that holds form data sent via the POST method.
# isset(...) checks if a value exists (i.e., the form was submitted).
# $_POST['addtask'] refers to a form button with the name "addtask"
# $task = $_POST['task'];  This retrieves the value entered in the input field named "todo" and stores it in the variable $task
# ->query()	Method of $conn to run SQL commands
# tasks is the name of the table
# task is the name of the column and $task is the value to insert


/*

GET is for asking the server for data.
Example: search.php?keyword=milk → show search results for "milk".

POST is for sending data to the server to be processed or saved.
Example: submitting a form to add a new task to your database.

*/

$result = $conn->query("SELECT * FROM tasks ORDER BY id DESC");

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class ="container">

        <h1>Todo List</h1>

        <form method="post" action="index.php">
            <input type="text" class="todo-input" name="todo" placeholder="Add a todo" id="" required>
            <button type="submit" class="todo-button" name= "addtask" > Add Task </button>
        </form>
<!-- When you're switching between PHP and HTML, you often open and close php tags-->


        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>

            <li id="task-<?php echo $row['id']; ?>" class="<?php echo $row['completed'] == 1 ? 'completed' : '' ; ?>">

                <div class="task-text" id="task-text-<?php echo $row['id']; ?>">
                    <strong><?php echo $row['task']; ?></strong>
                </div>


                <form method="POST" class="edit-inline-form" id="edit-form-<?php echo $row['id']; ?>" style="display: none;">
                    <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="edit_task" value="<?php echo htmlspecialchars($row['task'], ENT_QUOTES); ?>">
                    <button type="submit" name="update_task">Save</button>
                    <button type="button" onclick="cancelEdit(<?php echo $row['id']; ?>)">Cancel</button>
                </form>

                <!-- Action links -->
                <div class="actions">
                    <a href="index.php?complete=<?php echo $row['id']; ?>"> <img src="images/checked.png" alt="" width="18"> </a>
                    <a href="#" onclick="showEditForm(<?php echo $row['id']; ?>); return false;"> <img src="images/edit.png" alt="" width="18"> </a>
                    <a href="index.php?delete=<?php echo $row['id']; ?>"> <img src="images/delete.png" alt="" width="18"> </a>
                </div>

            </li>

            <?php endwhile; ?>
        </ul>


        


    </div>

    <script>

        function showEditForm(id) {
            document.getElementById('task-text-' + id).style.display = 'none';
            document.getElementById('edit-form-' + id).style.display = 'block';
        }

        function cancelEdit(id) {
            document.getElementById('edit-form-' + id).style.display = 'none';
            document.getElementById('task-text-' + id).style.display = 'block';
        }

    </script>




</body>

</html>


