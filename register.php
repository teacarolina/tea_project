<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register new user</title>
</head>
<body>
    <!--POST is used here for security when handling sensitive data-->
    <h3>Register here</h3>
    <form action="v1/users/addUser.php" method="POST">
    <input type="text" placeholder="username" name="username"><br/><br/>
    <input type="password" placeholder="password" name="password"><br/><br/>
    <input type="text" placeholder="email" name="email"><br/><br/>
    <input type="submit" value="Register" name="submit">
    <p>Already have an account? Sign in <a href="index.php">here</a></p>
    </form>
</body>
</html>