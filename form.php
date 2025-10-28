<?php
// 🧹 Function to sanitize user input by trimming spaces and preventing HTML code injection
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

// ✅ Function to validate form data fields
function validate_form($data) {
    $errors = [];

    // Check if First Name is empty
    if (empty($data['firstname'])) {
        $errors[] = "First Name is required.";
    }

    // Check if Birthday is valid and not empty
    if (empty($data['birthday'])) {
        $errors[] = "Birthday is required.";
    } elseif (!validate_date($data['birthday'])) {
        $errors[] = "Invalid date format for Birthday.";
    }

    // Check if Gender is selected
    if (empty($data['gender'])) {
        $errors[] = "Gender is required.";
    }

    // Check if Motto field is filled
    if (empty($data['motto'])) {
        $errors[] = "Quote in Life is required.";
    }

    return $errors;
}

// 📅 Helper function to check if date follows the correct format (YYYY-MM-DD)
function validate_date($date) {
    $d = DateTime::createFromFormat("Y-m-d", $date);
    return $d && $d->format("Y-m-d") === $date;
}

// 🎂 Function to calculate age based on given birthday
function calculate_age($birthday) {
    $birthDate = new DateTime($birthday);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
}

// ⚙️ Main function that handles the entire form submission
function handle_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize user inputs
        $firstname = sanitize_input($_POST['firstname']);
        $birthday = sanitize_input($_POST['birthday']);
        $gender = sanitize_input($_POST['gender']);
        $motto = sanitize_input($_POST['motto']);

        // Store data into an array for validation
        $form_data = [
            'firstname' => $firstname,
            'birthday' => $birthday,
            'gender' => $gender,
            'motto' => $motto
        ];

        // Validate input fields
        $errors = validate_form($form_data);

        // If no errors, show result; otherwise, display all errors
        if (empty($errors)) {
            $age = calculate_age($birthday);
            echo "<div class='result'>You are <strong>{$firstname}</strong>, a <strong>{$age}-year-old {$gender}</strong>. Your motto in life is: <em>{$motto}</em></div>";
            return true;
        } else {
            echo "<ul class='errors'>";
            foreach ($errors as $error) {
                echo "<li>{$error}</li>";
            }
            echo "</ul>";
        }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Functional PHP Registration Form</title>
<style>
    /* 🌿 Modern + Classic Styling */

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #e8ebe9, #d7dbda);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }

    .container {
        background: #ffffff;
        padding: 35px;
        border-radius: 15px;
        max-width: 480px;
        width: 100%;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .container:hover {
        transform: translateY(-3px);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #2c3e50;
        font-weight: 600;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 6px;
        font-weight: 500;
        color: #333;
    }

    input[type="text"],
    input[type="date"],
    select,
    textarea {
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 15px;
        font-family: inherit;
        transition: border 0.3s ease;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: #6c9a8b;
        outline: none;
    }

    textarea {
        resize: vertical;
    }

    button {
        background: #6c9a8b;
        color: #fff;
        border: none;
        padding: 12px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s ease;
    }

    button:hover {
        background: #557f73;
    }

    /* 🚨 Error styling */
    .errors {
        background-color: #fdeaea;
        border: 1px solid #f5c2c2;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
        color: #a33a3a;
        list-style-type: none;
        font-size: 14px;
    }

    /* ✅ Success result box */
    .result {
        background-color: #eaf7f0;
        border: 1px solid #b9e4c9;
        color: #2e6243;
        padding: 15px;
        border-radius: 8px;
        font-size: 16px;
        margin-top: 20px;
        line-height: 1.6;
    }
</style>
</head>
<body>
<div class="container">
<?php
// 🧩 Executes form handling and shows either the form or the result
$success = handle_form_submission();
if (!$success):
?>
    <h2>Register</h2>
    <form method="POST" action="">
        <!-- 👤 First Name Input -->
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" required>

        <!-- 🎂 Birthday Input -->
        <label for="birthday">Birthday</label>
        <input type="date" id="birthday" name="birthday" required>

        <!-- ⚧ Gender Dropdown -->
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <!-- 💬 Motto / Life Quote -->
        <label for="motto">Quote in Life</label>
        <textarea id="motto" name="motto" rows="4" required></textarea>

        <!-- 🚀 Submit Button -->
        <button type="submit">Submit</button>
    </form>
<?php endif; ?>
</div>
</body>
</html>
