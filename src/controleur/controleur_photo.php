<?php
function actionModifPhoto($twig, $db){
    $form = array();
    $phot = new Photo($db);
    if(isset($_GET['idPhoto'])){
        $unePhoto = $phot->selectByID($_GET['idPhoto']);
        if($unePhoto!=null){
            $form['photo'] = $unePhoto;
            $form['directory'] = "images/produit/";
        }
        else{
            $form['valide'] = false;
            $form['message'] = "Photo invalide";
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            if(isset($_FILES['photo'])){
                if(!empty($_FILES['photo']['name'])){  
                    $id = $_POST['id'];
                    $extensions_ok = array('png', 'jpg', 'jpeg');
                    $taille_max = 500000;
                    $dest_dossier = '/var/www/html/PleinLaBouche/web/images/produit/';
                    if( !in_array( substr(strrchr($_FILES['photo']['name'], '.'), 1), $extensions_ok ) ){
                        echo 'Veuillez sélectionner un fichier de type png ou jpg !';
                    }
                    else{
                        if( file_exists($_FILES['photo']['tmp_name']) && (filesize($_FILES['photo'] ['tmp_name'])) >  $taille_max){
                            echo 'Votre fichier doit faire moins de 500Ko !';
                        }
                        else{
                            $photo = basename($_FILES['photo']['name']);                           
                            $photo=strtr($photo,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                            $photo = preg_replace('/([^.a-z0-9]+)/i', '_', $photo);
                            move_uploaded_file($_FILES['photo']['tmp_name'], $dest_dossier.$photo);        
                            $exec = $phot->update($id, $photo);
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
        }
    }
     echo $twig->render('admin-photo-modif.html.twig', array('form'=>$form));
}
?>