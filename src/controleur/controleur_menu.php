<?php
function actionListeMenu($twig, $db){
    $form = array();
    $menu = new Menu($db);
    $liste = $menu->select();
    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id){
          $exec=$menu->delete($id); 
          if (!$exec){
             $form['valide'] = false;  
             $form['message'] = 'Problème de suppression dans la table menu';   
          }
        }
        header("Location:index.php?page=admin-menu-liste");
        exit;
    }
    $limite=3;
    if(!isset($_GET['nopage'])){
        $inf=0;
        $nopage=0;
    }
    else{
        $nopage=$_GET['nopage'];
        $inf=$nopage * $limite;
    }
    $r = $menu->selectCount();
    $nb = $r['nb'];
    $liste = $menu->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
    $form['nbmenu'] = $nb;
    echo $twig->render('admin-menu-liste.html.twig', array('liste'=>$liste,'form'=>$form));
}

function actionAjoutMenu($twig, $db){
    $form = array();
    $produit = new Produit($db);
    $form['produit'] = $produit->select();
    $menu = new Menu($db);
    if(isset($_POST['btAjouter'])){
        $date = $_POST['date'];
        $description = $_POST['description'];
        $idProduit = $_POST['plat'];
        $r = $menu->SelectCount();
        $r['nb'] +=1;
        $id = $r['nb'];
        $exec = $menu->insert($id, $date, $description, $idProduit);
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = "Problème d'insertion dans la table menu";
        } else{
            $form['valide'] = true;
            $form['message'] = "Ajout réalisé avec succès";
        }
    }
    echo $twig->render('admin-menu-ajout.html.twig', array('form'=>$form));
}

function actionModifMenu($twig, $db){
    $form = array();
    $produit = new Produit($db);
    $menu = new Menu($db);
    if(isset($_GET['id']) && isset($_GET['date'])){
        $unMenu = $menu->selectByID($_GET['id']);
        if ($unMenu!=null){
            $form['menu'] = $unMenu;
            $form['produit'] = $produit->select();
        }
        else{
            $form['message'] = 'Menu incorrect';  
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            $date = $_POST['date'];
            $description = $_POST['description'];
            $idProduit = $_POST['plat'];
            $id = $_POST['id'];
            $exec = $menu->insert($id, $date, $description, $idProduit);
            if(!$exec){
            $form['valide'] = false;
            $form['message'] = "Problème d'insertion dans la table menu";
            } else{
                $form['valide'] = true;
                $form['message'] = "Modification réalisé avec succès";
            }
        }
    }
    echo $twig->render('admin-menu-modif.html.twig', array('form'=>$form));
}
?>