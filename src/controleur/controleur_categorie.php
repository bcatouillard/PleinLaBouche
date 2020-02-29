<?php
function actionListeCategorie($twig, $db){
    $form = array();
    $categorie = new Categorie($db);
    $liste = $categorie->select();
    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id){
          $exec=$categorie->delete($id); 
          if (!$exec){
             $form['valide'] = false;  
             $form['message'] = 'Problème de suppression dans la table catégorie';   
          }
        }
        header("Location:index.php?page=admin-categorie-liste");
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
    $r = $categorie->selectCount();
    $nb = $r['nb'];
    $liste = $categorie->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
    $form['nbcategorie'] = $nb;
    echo $twig->render('admin-categorie-liste.html.twig', array('form'=>$form, 'liste'=>$liste));
}

function actionAjoutCategorie($twig, $db){
    $form = array();
    $categorie = new Categorie($db);
    if(isset($_POST['btAjouter'])){
        $libelle = $_POST['libelle'];
        $r = $categorie->selectCount();
        $r['nb']+=1;
        $id = $r['nb'];
        $exec = $categorie->insert($id, $libelle);
        if (!$exec){
               $form['valide'] = false;  
               $form['message'] = 'Problème d\'insertion dans la table catégorie';   
        }
        else{
            $form['valide'] = true;  
            $form['message'] = 'Ajout réussie !'; 
        }
    }
    echo $twig->render('admin-categorie-ajout.html.twig', array('form'=>$form));
}


function actionModifCategorie($twig, $db){
    $form = array();
    $categorie = new Categorie($db);
    if(isset($_GET['id'])){
        $uneCategorie = $categorie->selectByID($_GET['id']);
        if($uneCategorie != null){
            $form['categorie'] = $uneCategorie;
        }
        else{
            $form['valide'] = false;
            $form['message'] = "Catégorie invalide";
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            $libelle = $_POST['libelle'];
            $id = $_POST['id'];
            $exec = $categorie->update($id, $libelle);
            if (!$exec){
               $form['valide'] = false;  
               $form['message'] = 'Problème de modification dans la table catégorie';   
            }
            else{
               $form['valide'] = true;  
               $form['message'] = 'Modification réussie !'; 
            }
        }
    }
    echo $twig->render('admin-categorie-modif.html.twig', array('form'=>$form));
}
?>