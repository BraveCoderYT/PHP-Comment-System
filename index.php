<?php
include 'config.php';

// Function to update a comment
function updateComment($conn, $id, $name, $message) {
    $name = $conn->real_escape_string($name);
    $message = $conn->real_escape_string($message);

    $sql = "UPDATE comment SET name='$name', message='$message' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to delete a comment
function deleteComment($conn, $id) {
    $sql = "DELETE FROM comment WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST['post_comment'])) {
    $name = $_POST['name'];
    $message = $_POST['message'];

    $sql = "INSERT INTO comment (name, message) VALUES ('$name', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle comment updates
if (isset($_POST['update_comment'])) {
    $comment_id = $_POST['comment_id'];
    $update_name = $_POST['update_name'];
    $update_message = $_POST['update_message'];

    if (updateComment($conn, $comment_id, $update_name, $update_message)) {
        echo "Comment updated successfully!";
    } else {
        echo "Error updating comment.";
    }
}

// Handle comment deletions
if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];

    if (deleteComment($conn, $comment_id)) {
        echo "Comment deleted successfully!";
    } else {
        echo "Error deleting comment.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".edit-form").hide();
            $(".edit-button").click(function(){
                $(this).closest(".comment").find(".edit-form").toggle();
                return false;
            });
        });
    </script>
</head>
<style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            width: 60%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form {
            display: flex;
            flex-direction: column;
        }

        .form input,
        .form textarea,
        .form button {
            margin-bottom: 10px;
            padding: 8px;
            font-size: 14px;
        }

        .btn {
            background-color: #3498db;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .content {
            margin-top: 20px;
        }

        .comment {
            background-color: #fff;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .comment h3 {
            color: #333;
        }

        .comment p {
            color: #555;
        }

        /* Comment Options Styling */
        .comment-options {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .comment-options button {
            background: none;
            color: #3498db;
            cursor: pointer;
            text-decoration: underline;
            font-size: 14px;
            border: none;
            padding: 0;
        }

        .comment-options .edit-button {
            color: #3498db; /* Set the color for the edit button */
        }

        .comment-options button:hover {
            color: #2980b9;
        }

        /* Edit Form Styling */
        .edit-form {
            display: none;
            flex-direction: column;
            margin-top: 10px;
        }

        .edit-form input,
        .edit-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .edit-form input {
            background-color: #f8f8f8;
            color: #333;
        }

        .edit-form textarea {
            background-color: #f8f8f8;
            color: #333;
            resize: vertical;
        }

        .edit-form button {
            background-color: #2ecc71;
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .edit-form button:hover {
            background-color: #27ae60;
        }

        /* Delete Form Styling */
        .delete-form {
            flex-direction: column;
            margin-top: 10px;
        }

        .delete-form button {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .delete-form button:hover {
            background-color: #c0392b;
        }
    </style> 
<body>
    <div class="wrapper">
        <form action="" method="post" class="form">
            <input type="text" class="name" name="name" placeholder="Name">
            <br>
            <textarea name="message" cols="30" rows="5" class="message" placeholder="Message"></textarea>
            <br>
            <button type="submit" class="btn" name="post_comment">Post Comment</button>
        </form>
    </div>

    <div class="content">
        <?php
        $sql = "SELECT * FROM comment";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="comment">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['message']; ?></p>

                    <!-- Display options for updating and deleting a comment -->
                    <div class="comment-options">
                        <button class="edit-button">Edit</button>
                        <form action="" method="post" class="edit-form">
                            <input type="hidden" name="comment_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="update_name" value="<?php echo $row['name']; ?>" placeholder="Name">
                            <textarea name="update_message" cols="30" rows="5" placeholder="Message"><?php echo $row['message']; ?></textarea>
                            <button type="submit" name="update_comment">Update</button>
                        </form>

                        <form action="" method="post" class="delete-form">
                            <input type="hidden" name="comment_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_comment">Delete</button>
                        </form>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p>No comments yet!</p>';
        }
        ?>
    </div>
</body>
</html>