<?php
Class Util{
    function normalizeFiles( &$files ){
	$_files       = [ ];
	$_files_count = count( $files[ 'name' ] );
	$_files_keys  = array_keys( $files );

	for ( $i = 0; $i < $_files_count; $i++ )
		foreach ( $_files_keys as $key )
			$_files[ $i ][ $key ] = $files[ $key ][ $i ];

	return $_files;
    }
    
    function normalizePhotos(&$photos){
        $_photos = [];
        $_photos_count = count($photos['nom']);
        $_photos_keys = array_keys($photos); 
        $values = array_values($photos);
        $values_count = count($values);
        
        echo "Compte : " + $values_count + "<hr>";
        
        echo "Values : ";        var_dump($values); echo '<hr>';
        echo "Test :    " ; var_dump($photos); echo "<hr>"; 
        for($i=0; $i<$_photos_count;$i++){
            foreach($_photos_keys as $key){
                    $_photos[$i][$key] = $values[$i];
            }
        }
        return $_photos;
    }
}
?>