<?php
class userClass
{
    /*Login */
    public function userLogin($usernameEmail,$password)
    {
        try{
            $modelo = new Conexion();
            $db = $modelo -> get_conexion();
        $hash_password= hash('sha256', $password); //Password encryption 
        $stmt = $db->prepare("SELECT uid FROM users WHERE (username=:usernameEmail or email=:usernameEmail) AND password=:hash_password"); 
        $stmt->bindParam("usernameEmail", $usernameEmail,PDO::PARAM_STR) ;
        $stmt->bindParam("hash_password", $hash_password,PDO::PARAM_STR) ;
        $stmt->execute();
        $count=$stmt->rowCount();
        $data=$stmt->fetch();
        $db = null;
        if($count)
        {
            session_start();
            $_SESSION['uid']=$data['uid']; // Storing user session value
            return true;
        }
        else
        {
            return false;
        } 
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
 
}
/* Registro */
public function userSignup($username,$password,$email,$name, $userphone, $useraddress)
{
    try{
        $modelo = new Conexion();
        $db = $modelo -> get_conexion();
        $st = $db->prepare("SELECT uid FROM users WHERE username=:username OR email=:email"); 
        $st->bindParam("username", $username,PDO::PARAM_STR);
        $st->bindParam("email", $email,PDO::PARAM_STR);
        $st->execute();
        $count=$st->rowCount();
        if($count<1)
        {
            $stmt = $db->prepare("INSERT INTO users(username,password,email,name, userphone, useraddress) VALUES (:username,:hash_password,:email,:name, :userphone, :useraddress)");
            $stmt->bindParam("username", $username,PDO::PARAM_STR) ;
        $hash_password= hash('sha256', $password); //Password encryption
        $stmt->bindParam("hash_password", $hash_password,PDO::PARAM_STR) ;
        $stmt->bindParam("email", $email,PDO::PARAM_STR) ;
        $stmt->bindParam("name", $name,PDO::PARAM_STR) ;
        $stmt->bindParam("userphone", $userphone,PDO::PARAM_STR) ;
        $stmt->bindParam("useraddress", $useraddress,PDO::PARAM_STR) ;
        $stmt->execute();
        $uid=$db->lastInsertId(); // Last inserted row id
        $db = null;
        session_start();
        $_SESSION['uid']=$uid;
        return true;
    }
    else
    {
        $db = null;
        return false;
    }
 
} 
catch(PDOException $e) {
    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
}
}
 
/* User Details */
public function userDetails($uid)
{
    try{
        $modelo = new Conexion();
        $db = $modelo -> get_conexion();
        $stmt = $db->prepare("SELECT uid, email,username,name, userphone, useraddress FROM users WHERE uid=:uid"); 
        $stmt->bindParam("uid", $uid,PDO::PARAM_INT);
        $stmt->execute(); 
    $data = $stmt->fetch(PDO::FETCH_OBJ); //User data
    return $data;
}
catch(PDOException $e) {
    echo '{"error":{"text":'. $e->getMessage() .'}}';
}
}
public function modificarPerfilUser($arg_campo, $arg_valor, $arg_id_user){
    $modelo = new conexion();
    $conexion = $modelo -> get_conexion();
    $sql = "update users set $arg_campo = :valor where uid = :uid";
    $statement = $conexion -> prepare($sql);
    $statement -> bindParam(":valor", $arg_valor);
    $statement -> bindParam(":uid", $arg_id_user);
    if(!$statement){
        return "Error al modificar su perfil";
    }else{
        $statement -> execute();
        echo "<img  src='../../media/confirm.png' >";
        return "<h1>SU PERFIL HA SIDO MODIFICADO <span>¡EXITOSAMENTE!</span></h1>";
    }
}
}
?>