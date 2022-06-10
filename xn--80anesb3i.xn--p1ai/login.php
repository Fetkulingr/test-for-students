<?php 
require 'db.php';
$data = $_POST;
echo "<link rel='stylesheet' href='style.css'>";
if ( isset($data['do_login']) )
{
    $user = R::findOne('users', 'login = ?', array($data['login']));
    if ( $user )
    {
        //логин существует
        if ( password_verify($data['password'], $user->password) )
        {
            //если пароль совпадает, то нужно авторизовать пользователя
            $_SESSION['logged_user'] = $user;
            echo '<div style="color:dreen;">Вы авторизованны!<br> 
            Вы можите перейти на <a href="test.html">страницу</a> с тестами.</div><hr>';
        }else
        {
            $errors[] = 'Логин или пароль не совпадают';
        }

    }else
    {
        $errors[] = 'Логин или пароль не совпадают';
    }

    if ( ! empty($errors) )
    {
        //выводим ошибки авторизации
        echo '<div id="errors" style="color:red; text-align:center;">' .array_shift($errors). '</div>';
    }

}
?>
<form action="login.php" method="POST">
<meta charset="utf-8">
    <strong>Логин</strong>
    <input type="text" name="login" value="<?php echo @$data['login']; ?>"><br>
 
    <strong>Пароль</strong>
    <input type="password" name="password" value="<?php echo @$data['password']; ?>"><br>
 
    <button type="submit" name="do_login">Войти</button>
</form>