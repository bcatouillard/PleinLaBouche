<?php
function  getPage($db){
    
    //Panel Visiteur
    $lesPages['accueil']     = "actionAccueil;0";
    $lesPages['maintenance'] = "actionMaintenance;0";
    $lesPages['actualite']   = "actionActualites;0";
    $lesPages['plat']        = "actionProduits;0";
    $lesPages['contact']     = "actionContact;0";
    
    //Panel Admin Connexion
    $lesPages['admin-panel-inscription'] = "actionInscription;0";
    $lesPages['admin-panel-connexion']   = "actionConnexion;0";
    $lesPages['admin-panel-deconnexion'] = "actionDeconnexion;0";
   
    //Panel Admin Catégorie
    $lesPages['admin-categorie-liste'] = "actionListeCategorie;1";
    $lesPages['admin-categorie-ajout'] = "actionAjoutCategorie;1";
    $lesPages['admin-categorie-modif'] = "actionModifCategorie;1";
    
    //Panel Admin Plat
    $lesPages['admin-plat-liste']     = "actionListeProduit;1";
    $lesPages['admin-plat-ajout']     = "actionAjoutProduit;1";
    $lesPages['admin-plat-modif']     = "actionModifProduit;1";
    
    //Panel Admin Menu
    $lesPages['admin-menu-liste'] = "actionListeMenu;1";
    $lesPages['admin-menu-ajout'] = "actionAjoutMenu;1";
    $lesPages['admin-menu-modif'] = "actionModifMenu;1";
    
    //Panel Admin Semaine
    $lesPages['admin-semaine-liste'] = "actionListeSemaine;1";
    $lesPages['admin-semaine-ajout'] = "actionAjoutSemaine;1";
    $lesPages['admin-semaine-modif'] = "actionModifSemaine;1";
    
    //Panel Admin Photo
    $lesPages['admin-photo-modif'] = "actionModifPhoto;1";
    
    
    $contenu = $lesPages['accueil']; 
    
    if ($db!=null)
    {
        if(isset($_GET['page']))
        {
            $page = $_GET['page'];
            $contenu = $lesPages[$page];
        }else{
            
            $page = 'accueil';
        }
        if (!isset($lesPages[$page]))
        {
            $page = 'accueil'; 
        }
    }
    $explose = explode(";",$lesPages[$page]);
    $role = $explose[1];

    if ($role != 0){
        if(isset($_SESSION['role'])){    
            if($role!=$_SESSION['role']){
                $contenu = 'actionAccueil';  
            }
            else{
            $contenu = $explose[0]; 
            }
        }
        else{
            
            $contenu = 'actionAccueil';   
        }
    }else{
        $contenu = $explose[0];
    }
    return $contenu; 
}
?>