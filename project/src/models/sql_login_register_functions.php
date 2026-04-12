<?php
function registerAccount($conn, $name, $email, $password, $password_retype){
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $check_email = $stmt->get_result();

    if($check_email->num_rows > 0){
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
    }
    // Make sure the password verification is good
    elseif($password != $password_retype){
        $_SESSION['retype_error'] = 'Passwords do not match';
        $_SESSION['active_form'] = 'register';
    }
    // If everything is good then add the user to the database
    else{
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $password);
        $stmt->execute();
        $_SESSION['account_confirmation'] = 'Account successfully created';
    }
}

function loginAccount($conn, $email, $password){
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: " . BASE_URL . "/src/views/main_menu.php");
            exit();
        }
    }
}
?>