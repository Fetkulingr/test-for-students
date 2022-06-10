<?php
require "db.php";
$data = $_POST;
 echo "<link rel='stylesheet' href='style.css'>";
function captcha_show(){
    $questions = array(
        1 => 'Столица России',
        2 => 'Столица США',
        3 => '2 + 3',
        4 => '15 + 14',
        5 => '45 - 10',
        6 => '33 - 3'
    );
    $num = mt_rand( 1, count($questions) );
    $_SESSION['captcha'] = $num;
    echo $questions[$num];
}
 
//если кликнули на button
if ( isset($data['do_signup']) )
{
// проверка формы на пустоту полей
    $errors = array();
    if ( trim($data['login']) == '' )
    {
        $errors[] = 'Введите ваш логин';
    }
 
    if ( trim($data['email']) == '' )
    {
        $errors[] = 'Введите ваш Email';
    }
 
    if ( $data['password'] == '' )
    {
        $errors[] = 'Введите ваш пароль';
    }
 
    if ( $data['password_2'] != $data['password'] )
    {
        $errors[] = 'Пароли не совпадают!';
    }
 
    //проверка на существование одинакового логина
    if ( R::count('users', "login = ?", array($data['login'])) > 0)
    {
        $errors[] = 'A user with this login already exists!';
    }
 
//проверка на существование одинакового email
    if ( R::count('users', "email = ?", array($data['email'])) > 0)
    {
        $errors[] = 'A user with this Email Already exists!';
    }
 
    //проверка капчи
    $answers = array(
        1 => 'москва',
        2 => 'вашингтон',
        3 => '5',
        4 => '29',
        5 => '35',
        6 => '30'
    );
    if ( $_SESSION['captcha'] != array_search( mb_strtolower($_POST['captcha']), $answers ) )
    {
        $errors[] = 'The answer to the question is incorrect!';
    }
 
 
    if ( empty($errors) )
    {
        //ошибок нет, теперь регистрируем
        $user = R::dispense('users');
        $user->login = $data['login'];
        $user->email = $data['email'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT); 
        //пароль нельзя хранить в открытом виде, 
        //мы его шифруем при помощи функции password_hash для php > 5.6
         
        R::store($user);
        echo '<div style="color:dreen;">You are successfully registered!</div><hr>';
    }else
    {
        echo '<div id="errors" style="color:red;">' .array_shift($errors). '</div><hr>';
    }
 
}
?>
<form action="/signup.php" method="POST">
<meta charset="utf-8">
    <strong>Введите ваш логин</strong>
    <input type="text" name="login" value="<?php echo @$data['login']; ?>"><br>
 
    <strong>Введите ваш Email</strong>
    <input type="email" name="email" value="<?php echo @$data['email']; ?>"><br>
 
    <strong>Введите ваш пароль</strong>
    <input type="password" name="password" value="<?php echo @$data['password']; ?>"><br>
 
    <strong>Повторите пароль</strong>
    <input type="password" name="password_2" value="<?php echo @$data['password_2']; ?>"><br>
 
    <strong><!--?php captcha_show(); ?--></strong>
    <button type="submit" name="do_signup">Зарегистрироваться</button>
</form>