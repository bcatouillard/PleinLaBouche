<?php
function actionConnexion($twig,$db){
    $form = array(); 
    if (isset($_POST['btConnexion'])){
        $email = $_POST['email'];
        $passwd = $_POST['passwd'];  
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->connect($email);
        if ($unUtilisateur!=null){
            if(!password_verify($passwd,$unUtilisateur['passwd'])){
              $form['valide'] = false;
              $form['message'] = 'Login ou mot de passe incorrect';
            }  
            else{
                $_SESSION['login'] = $email;     
                $_SESSION['role'] = $unUtilisateur['idRole'];
                header("Location:index.php");
                exit;
            } 
        }
        else{
           $form['valide'] = false;
           $form['message'] = 'Login ou mot de passe incorrect';
        }
    }
    echo $twig->render('admin-panel-connexion.html.twig', array('form'=>$form));
}

function actionDeconnexion($twig){
    session_unset();
    session_destroy();
    header("Location:index.php");
}

function actionInscription($twig, $db){
    $form = array(); 
    $role = new Role($db);
    $liste = $role->select();
    if(isset($_POST['btInscription'])){
        $utilisateur = new Utilisateur($db);
        $email = $_POST['email'];
        $passwd = $_POST['passwd'];
        $passwd2 = $_POST['passwd2'];
        $role = $_POST['role'];
        if($passwd != $passwd2){
            $form['valide'] = false;  
            $form['message'] = 'Les mots de passe sont différents';
        }
        else{
            $exec = $utilisateur->insert($email, password_hash($passwd,PASSWORD_DEFAULT), $role);
            if (!$exec){
                $form['valide'] = false;  
                $form['message'] = 'Problème d\'insertion dans la table utilisateur';  
            }
        }
    }
    echo $twig->render('admin-panel-inscription.html.twig', array('form'=>$form, 'liste'=>$liste));
}
?>