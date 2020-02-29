<?php
function actionListeProduit($twig, $db){
    $form = array();
    $produit = new Produit($db);
    $phot = new Photo($db);
    $appartenir = new Appartenir($db);
    $liste = $produit->select();
    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id){
            $exec = $appartenir->delete($id);
            $exec = $phot->delete($id);
            $exec=$produit->delete($id); 
            if (!$exec){
               $form['valide'] = false;  
               $form['message'] = 'Problème de suppression dans la table plat';   
            }
        }
        header("Location:index.php?page=admin-plat-liste");
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
    $r = $produit->selectCount();
    $nb = $r['nb'];
    $liste = $produit->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
    $form['nbproduit'] = $nb;
    echo $twig->render('admin-plat-liste.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionAjoutProduit($twig, $db){
    $form = array();
    $produit = new Produit($db);
    $categorie = new Categorie($db);
    $form['categorie'] = $categorie->select();
    $appartenir = new Appartenir($db);
    if(isset($_POST['btAjouter'])){
        $designation = $_POST['designation'];
        $description = $_POST['description'];
        $prix = $_POST['prix'];
        $r = $produit->SelectCount();
        $r['nb'] +=1;
        $idProduit = $r['nb'];
        $nbPersonne = $_POST['nbPersonne'];
        $cat = $_POST['cat'];
        $exec = $produit->insert($idProduit,$designation,$description,$prix,$nbPersonne);
        $phot = new Photo($db);
        $util = new Util();
        $total = count($cat)-1;
        for($i=0;$i<=$total;$i++){
            if($cat[$i] != 0){
                $e=$appartenir->insert($idProduit,$cat[$i]);
            }
        }
        if(!empty($_FILES['photo'])){
            $photoArray = $util->normalizeFiles( $_FILES[ 'photo' ] );
            foreach ( $photoArray as $file ){
                $extensions_ok = array('png', 'jpg', 'jpeg');
                $taille_max = 500000;
                $dest_dossier = '/var/www/html/PleinLaBouche/web/images/produit/';
                if( !in_array( substr(strrchr($file['name'], '.'), 1), $extensions_ok ) ){
                    echo 'Veuillez sélectionner un fichier de type png ou jpg !';
                }
                else{
                    if( file_exists($file['tmp_name']) && (filesize($file['tmp_name'])) >  $taille_max){
                        echo 'Votre fichier doit faire moins de 500Ko !';
                    }
                    else{
                        $r = $phot->selectCount();
                        $r['nb']+=1;
                        $id = $r['nb'];
                        $photo = basename($file['name']);                           
                        $photo=strtr($photo,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                        $photo = preg_replace('/([^.a-z0-9]+)/i', '_', $photo);
                        $exec = $phot->insert($id, $photo, $idProduit);
                        move_uploaded_file($file['tmp_name'], $dest_dossier.$photo);      
                    }
                } 
            }
        }
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = "Problème d'insertion dans la table plat";
        } else{
            $form['valide'] = true;
            $form['message'] = "Ajout réalisé avec succès";
        }
    }
    echo $twig->render('admin-plat-ajout.html.twig', array('form'=>$form));
}

function actionModifProduit($twig, $db){
    $form = array();
    $produit = new Produit($db);
    $phot = new Photo($db);
    $categorie = new Categorie($db);
    $appartenir = new Appartenir($db);
    $util = new Util();
    $liste = $categorie->select();
    if(isset($_GET['id'])){
        $unProduit = $produit->selectByID($_GET['id']);
        $lesPhotos = $phot->selectByProduit($_GET['id']);
        $lesCategories = $appartenir->selectByProduit($_GET['id']);
        if ($unProduit!=null){
            $form['produit'] = $unProduit;
            if($lesCategories != null){
                $form['categorie'] = $lesCategories;                
            }
            if($lesPhotos != null){
                    $form['lesPhotos'] = $lesPhotos;                               
                    $form['directory'] = "images/produit/";
            }
        }
        else{
            $form['message'] = 'Produit incorrect';  
        }
    }
    elseif(isset($_POST['btSupprimerPhoto'])){
        $idPhoto = $_POST['idPhoto'];
        $laPhoto = $phot->selectByID($idPhoto);
        $idProduit = $_POST['idProduit'];
        $nom = $laPhoto['nom'];
        unlink("/var/www/html/PleinLaBouche/web/images/produit/".$nom);
        $exec = $phot->delete($idProduit);
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = "Problème de suppression dans la table photo";
        } else{
            $form['valide'] = true;
            $form['message'] = "Suppression réalisée avec succès";
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            $idProduit = $_POST['idProduit'];
            $nbPersonne = $_POST['nbPersonne'];
            $designation = $_POST['designation'];
            $description = $_POST['description'];
            $cat = $_POST['cat'];
            $total = count($cat)-1;
            $r = $appartenir->selectCount($idProduit);
            $count = $r['nb'];
            for($i=0;$i<=$count;$i++){
                $exec = $appartenir->delete($idProduit);
            }
            for($i=0;$i<=$total;$i++){
                if($cat[$i] != 0){
                    $e=$appartenir->insert($idProduit, $cat[$i]);
                }
            }
            $prix = $_POST['prix'];
            $exec = $produit->update($idProduit, $designation, $description, $prix, $nbPersonne);
            $util = new Util();
            $photoArray = $util->normalizeFiles( $_FILES[ 'photo' ] );
            if(!empty($photoArray)){
                foreach ( $photoArray as $file ){
                    $extensions_ok = array('png', 'jpg', 'jpeg');
                    $taille_max = 500000;
                    $dest_dossier = '/var/www/html/PleinLaBouche/web/images/produit/';
                    if( !in_array( substr(strrchr($file['name'], '.'), 1), $extensions_ok ) ){
                        echo 'Veuillez sélectionner un fichier de type png ou jpg !';
                    }
                    else{
                        if( file_exists($file['tmp_name']) && (filesize($file['tmp_name'])) >  $taille_max){
                            echo 'Votre fichier doit faire moins de 500Ko !';
                        }
                        else{
                            $photo = basename($file['name']);                           
                            $photo=strtr($photo,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                            $photo = preg_replace('/([^.a-z0-9]+)/i', '_', $photo);
                            move_uploaded_file($file['tmp_name'], $dest_dossier.$photo);        
                            $r = $phot->selectByProduit($idProduit);
                            $nom = $r['nom'];
                            unlink($dest_dossier.$nom);
                            $exec = $phot->update($photo,$idProduit);
                        }
                    }
                }            
            }
            if (!$exec){
               $form['valide'] = false;  
               $form['message'] = 'Problème de modification dans la table plat';   
            }
            else{
               $form['valide'] = true;  
               $form['message'] = 'Modification réussie !'; 
            }
        }
        else{
            $form['valide'] = false;
            $form['message'] = 'Plat non précisé';  
        } 
    }
    echo $twig->render('admin-plat-modif.html.twig', array('form'=>$form, 'liste'=>$liste));
}
?>