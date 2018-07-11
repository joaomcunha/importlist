<form method="POST" accept-charset="utf-8" enctype="multipart/form-data">
		<div class="form-group">
	    <label for="importClientes">Escolha um arquivo</label>
	    <input type="file" class="form-control-file" name="importClientes">
	    <div class="form-group">
	    	<button type="submit" class="btn btn-primary" style="margin-top: 10px">Enviar</button>
	    	
	    </div>
	</form>

<?php 

	if($_SERVER["REQUEST_METHOD"] === "POST"){

	$files = $_FILES["importClientes"];

	if($files["error"]){
		throw new 	Exception("Error: " . $files["error"]);
		
	}

	$dirUploads = "docs";

	if(!is_dir($dirUploads)){
		mkdir($dirUploads);
	}

	if(move_uploaded_file($files["tmp_name"], $dirUploads . DIRECTORY_SEPARATOR	. $files["name"])){

		echo "Upload realizado com sucesso </br>";

	}else{
		throw new 	Exception("Não foi possivel realizar o upload");
		
	}

}

$filename = "docs" . DIRECTORY_SEPARATOR . $files["name"];

if (file_exists($filename)) {
		$file = fopen($filename, "r");

		$headers = explode(";" , fgets($file));

		$data = array();

		while ($row = fgets($file)) {

			$rowData = explode(";" , $row);
			$linha = array();
			for ($i=0; $i < count($headers); $i++) { 
				$linha[$headers[$i]] = $rowData[$i];

			}
		array_push($data, $linha);

		$conn = new PDO("mysql:dbname=estudos_mysql;host=127.0.0.1", "root", "" );

		foreach ($data as $key) {
			
				$stmt = $conn->prepare("INSERT INTO tb_clientes(nome, tel, email) VALUES ( :nome, :tel, :email)");

				
				$stmt->bindParam(":nome", $key["nome"]);
				$stmt->bindParam(":tel", $key["tel"]);
				$stmt->bindParam(":email", $key["email"]);
				
				$stmt->execute();


			}
			
		}

}


 ?>
