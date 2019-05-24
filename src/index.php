<?php
#Notwendige Includes:
require_once "includes/class_mysql.php";

#Sicherheit SQL Injection

# get ingredient names
$_REQUEST = $DB->real_escape( $_REQUEST );
$sql =
"SELECT group_concat(name) as names, ingredient_id
	FROM alias
	GROUP BY ingredient_id;";
$ingredienNames = $DB->query_json($getIngredientNames);

// get ingredients
$sql = "SELECT
	default_name AS name,
	u.name as Einheit
	FROM ingredient i
	JOIN unit u ON i.unit_id=u.id;";
echo $ingredients = $DB->query_json($sql);

// get recipes
#Datenbankabfrage (JSON)
$sql = "SELECT * FRom, recipes;";
	// default_name AS name,
	// u.name as Einheit
	// FROM ingredient i
	// JOIN alias a ON i.id = a.ingredient_id
	// JOIN unit u ON i.unit_id=u.id
	// WHERE a.name LIKE '%$_REQUEST[suche]%'";
$recipes = $DB->query_json( $sql );

?>
<!DOCTYPE html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <title>smoothie</title>
  </head>
  <body>
    <div id="app"></div>
    <div>
    	<h4>php variablen</h4>
    	<div>{{$recipes}}</div>
    </div>
    <script src="/dist/build.js"></script>
  </body>
</html>

