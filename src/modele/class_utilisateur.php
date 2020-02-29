<?php
class Utilisateur{
    private $db;
    private $insert;
    private $connect;
    private $selectByEmail;
    private $updatemdp;
    
    public function __construct($db){
        $this->db = $db;  
        $this->insert  =  $db->prepare("insert  into  utilisateur(login,  passwd, idRole)  values(:login, :passwd, :role)");  
        $this->connect = $db->prepare("select login, idRole, passwd from utilisateur where login=:login");
        $this->selectByEmail = $db->prepare("select login, idRole from utilisateur where login=:login");
        $this->updatemdp = $db->prepare("update utilisateur set passwd=:passwd where login=:login");
    }
    
    public function insert($email, $mdp, $role){ 
        $r = true;
        $this->insert->execute(array(':login'=>$email, ':passwd'=>$mdp, ':role'=>$role));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
        
    }
    public function connect($email){  
        $unUtilisateur = $this->connect->execute(array(':login'=>$email));
        if ($this->connect->errorCode()!=0){
             print_r($this->connect->errorInfo());  
        }
        return $this->connect->fetch();
    }

    public function selectByEmail($email){  
        $this->selectByEmail->execute(array(':login'=>$email));
        if ($this->selectByEmail->errorCode()!=0){
             print_r($this->selectByEmail->errorInfo());  
        }
        return $this->selectByEmail->fetch();
    }

    
    public function updatemdp($mdp, $email){
        $r = true;
        $this->updatemdp->execute(array(':passwd'=>$mdp,':login'=>$email));
        if ($this->updatemdp->errorCode()!=0){
             print_r($this->updatemdp->errorInfo());  
             $r=false;
        }
        return $r;
    }
}
?>

