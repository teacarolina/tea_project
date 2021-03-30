<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
</head>
<body>
    <!--POST is used here for security when handling sensitive data-->
    <h3>Sign in</h3>
    <form action="v1/users/loginUser.php" method="POST">
    <input type="text" placeholder="username" name="username"><br/><br/>
    <input type="password" placeholder="password" name="password"><br/><br/>
    <input type="submit" value="Sign in" name="submit">
    <p>Don't have an account? Register <a href="register.php">here</a></p>
    </form>
</body>
</html>