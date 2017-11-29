<?php
	try {
		$host = "db.ist.utl.pt";
		$user ="ist426058";
		$password = "vjwj7059";
		$dbname = $user;
		$db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$db->query("start transaction;");
		
		$case = $_POST["case"];
		echo("<p>$case</p>");
		switch($case) {
			case 2:
				$categoria = $_POST["categoria"];
				//Checks if the category has a parent.
				$sql = "SELECT super_categoria FROM constituida WHERE categoria = '$categoria';";
				$result = $db->query($sql);

				$count = 0;
				$parent = 'Indefinido';
				$hasparent = false;
				foreach ($result as $cat) {
					$parent = $cat['super_categoria'];
					$hasparent = true;
				}
								
				
				//Checks if category has children.
				$sql = "SELECT categoria FROM constituida WHERE super_categoria = '$categoria';";
				$result = $db->query($sql);
				//If the category has children, all of the relations in "constituida" will
				//be changed.
				//Updates all the relations in "constituida" so that all the children
				//that were parented by the to-be-deleted category are now parented
				//by its parent (if it existed) or by a category called "Indefinida"
				//(undefined).
				foreach ($result as $cat) {
					$subcat = $cat['categoria'];
					echo("<p>$subcategory</p>");
					//CHANGE - IF NO NAME THAN PARENT WILL BE INDEFINID.
					$sql = "UPDATE constituida SET super_categoria = '$parent' WHERE categoria = '$subcat';";
					$db->query($sql);
				}

				
				//Deleted the relation in "constituida" between the to-be-deleted
				//category and its parent.

				
				//Deletes the relation in "categoria_simples" (if it existed).
				$sql = "DELETE FROM categoria_simples WHERE nome = '$categoria';";
				$db->query($sql);

				//Deletes the relation in "super_categoria" (if it existed).
				$sql = "DELETE FROM super_categoria WHERE nome = '$categoria';";
				$db->query($sql);

				//Deletes the relation in "categoria".
				$sql = "DELETE FROM categoria WHERE nome = '$categoria';";
				$db->query($sql);

				break;
		}
		

		$db->query("commit;");
		$db = null;
	}
	catch (PDOException $e) {
		$db->query("rollback;");
		echo("<p>ERROR: {$e->getMessage()}</p>");
	}
?>