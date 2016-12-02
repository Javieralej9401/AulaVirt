<?php
echo __DIR__;

if(isset($_POST["id_grupo"]) && isset($_POST["id_usuario"]) ){

	$grupo= $_POST["id_grupo"];
	$user= $_POST["id_usuario"];
	$carpeta= "dir_rec".$grupo;



	if(isset($_POST["check_exists"] ) || isset($_POST["rm_files"] ) || isset($_POST["success_upload_post"] )  ){
		if(isset($_POST["check_exists"] )){
			$filename= $_POST["check_exists"];
			echo file_exists($filename);
			
		}
		if(isset($_POST["rm_files"] )){ // se borran los archivos de la carpeta cuando se modifica o elimina un post.

				foreach ($_POST["files"] as $key => $value) {
						
						unlink($value);
				}

		}
		if(isset($_POST["success_upload_post"] ) ){ //se mueven todos los archivos de la carp temporal a la carpeta de archivos..

					$tmp_folder= $_POST["success_upload_post"]."/";
					$ruta= __DIR__.'/../../../../AulaVirt/web/bundles/av/'. $carpeta."/";
					if(!file_exists($ruta)){

						mkdir($ruta);
					} 

					//leyendo archivos...
					if (is_dir($tmp_folder)) {
						if ($gd = opendir($tmp_folder)) {
							while ($archivo = readdir($gd)) {
								if($archivo!="." || $archivo!=".." ){
										rename($tmp_folder.$archivo,$ruta.$archivo);
									
								}
								
							}
							closedir($gd);
						}
						rmdir($tmp_folder);
					}
					

		}

	}else{		
				if ($_FILES['file']["error"] <= 0) { //los archivos se suben a una carpeta temporal del usuario
					$tmp_folder=  __DIR__.'/../../../../AulaVirt/web/bundles/av/'."tmp_upload/ct_".$user."/";
			

						mkdir($tmp_folder,0777, true); 

							
						$r= array("tmp_folder"=> $tmp_folder);

						echo json_encode($r);
					
					 $file_name= $_FILES['file']['name'];
					 $tmp_folder.= $file_name;

					if(move_uploaded_file($_FILES['file']['tmp_name'], $tmp_folder)){

						print_r($file_name) ;	
						

					}else{
						
					}

				}
	}
	
}else{
	echo "ERRORRRaAsd";
}	

?>
