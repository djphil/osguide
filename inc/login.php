<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php if (isset($_SESSION['valid'])): ?>
    <?php $_SESSION['flash']['danger'] = "You are already log-in <strong>".$_SESSION['username']."</strong> ..."; ?>
    <?php header("Location: ?page=home"); exit(); ?>
<?php endif; ?>

<h1>Login <i class="glyphicon glyphicon-lock pull-right"></i></h1>
<div class="clearfix"></div>

<?php
if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password']))
{
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $buffer = explode(" ", $username);
    if (isset($buffer[0])) $firstname = $buffer[0];
    else $firstname = "Unknow Firstname";
    if (isset($buffer[1])) $lastname = $buffer[1];
    else $lastname = "Unknow Lastname";

    $sql = $db->prepare("
        SELECT *
        FROM ".TB_USERACCOUNTS."
        WHERE FirstName = ?
        AND LastName = ?
    ");

    $sql->bindValue(1, $firstname, PDO::PARAM_STR);
    $sql->bindValue(2, $lastname, PDO::PARAM_STR);

    $sql->execute();
    $rows = $sql->rowCount();

    if ($rows <> 0)
    {
        while ($row = $sql->fetch(PDO::FETCH_ASSOC))
        {
            $PrincipalID = $row['PrincipalID'];

            if ($PrincipalID <> "")
            {
                $sql = $db->prepare("
                    SELECT *
                    FROM ".TB_AUTH."
                    WHERE UUID = ?
                ");

                $sql->bindValue(1, $PrincipalID, PDO::PARAM_STR);

                $sql->execute();
                $rows = $sql->rowCount();

                if ($rows <> 0)
                {
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        $passwordHash = $row['passwordHash'];
                        $passwordSalt = $row['passwordSalt'];
                    }

                    if ($passwordHash <> "")
                    {
                        $md5Password   = md5(md5($password).":".$passwordSalt);

                        if ($passwordHash == $md5Password)
                        {
                            $_SESSION['valid'] = TRUE;
                            $_SESSION['username'] = $username;
                            $_SESSION['useruuid'] = $PrincipalID;
                            $_SESSION['flash']['success'] = "You are connected succefully <strong>".$username."</strong>";

                            if ($_POST['remember'])
                            {
                                setcookie($cookie_name, $cookie_name, time() + $cookie_time);
                            }

                            header("Location: ?page=home");
                            exit();
                        }
                        else $_SESSION['flash']['danger'] = "Wrong password ...";
                    }
                    else $_SESSION['flash']['danger'] = "Invalid password ...";
                }
                else $_SESSION['flash']['danger'] = "ID/Password no match ...";
            }
            else $_SESSION['flash']['danger'] = "Invalid ID ...";
        }
    }
    else $_SESSION['flash']['danger'] = "Invalid username ...";
}
?>

<!-- Login Form -->
<?php if (!isset($_SESSION['valid'])): ?>
<form class="form-signin" role="form" action="./?page=login" method="post" >
<h2 class="form-signin-heading">Please login</h2>
    <label for="username" class="sr-only">User name</label>
    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
    <label for="password" class="sr-only">Password</label>
    <input type="password" name="password" class="form-control" placeholder="Password" required>
    <div class="checkbox">
        <label>
            <input type="checkbox" value="remember-me"> Remember me
        </label>
    </div>        
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">
        <i class="glyphicon glyphicon-log-in"></i> Log-in
    </button>
</form>
<?php endif; ?>
