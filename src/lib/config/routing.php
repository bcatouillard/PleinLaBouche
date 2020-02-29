<?php
function  getPage($db){
    $lesPages['accueil'] = "actionAccueil;0";

    
    
 
    
    $contenu = $lesPages['accueil']; 
    
    if ($db!=null)
    {
        if(isset($_GET['page']))
        {
            $page = $_GET['page']; 
        }else{
            
            $page = 'accueil';
        }
        if (!isset($lesPages[$page]))
        {
            $page = 'accueil'; 
        }
        
        $explose = explode(";",$lesPages[$page]);
        $role = $explose[1];
        if ($role != 0)
        {
            if(isset($_SESSION['login']))
            {  
                if(isset($_SESSION['role']))
                {    
                    if($role!=$_SESSION['role'])
                    {
                        $contenu = 'actionAccueil';  
                    }else{
                        $contenu = $explose[0]; 
                    }
                }else{
                    $contenu = 'actionAccueil';   
                }
            }else{
                $contenu = 'actionAccueil';  
            }
        }else{
            $contenu = $explose[0];
        }
    }else{
        
        $contenu = $lesPages['maintenance'];
    }
    return $contenu; 
}
?>