<?php

//SQL : 4 requêtes à savoir :

// CRUD : 

	// Create 	=> requête INSERT (insertion en bdd)
	// Read 	=> requête SELECT (lire/récupérer les infos en bdd)
	// Update 	=> requête UPDATE (modification en bdd)
	// Delete 	=> requête DELETE (suppression en bdd)

//----------------------------------------------------------------------------------

/*	PDO : PHP DATA OBJECT : Représente une connexion entre PHP et un serveur de base de données.

=> EXEC() :

	=> INSERT, UPDATE, DELETE :
		exec() est utilisé pour la formulation de requêtes ne retournant pas de résulat !
		exec() renvoi le nombre de lignes affectées par la requêtes

	Valeur de retour : 
		ECHEC : false
		SUCCES : 1 (ce nombre varie selon le nombre d'enregisrement affecté par la requête)

//-------------------------------------------------------------------
=> QUERY() :

	=> SELECT : Au contraire d'exec(), query() est utilisé pour la formulation de requêtes retournant un ou plusieurs résultats.

	Valeur de retour :
		ECHEC : false
		SUCCES : PDOStatement (objet)

//-------------------------------------------------------------------
=> PREPARE() puis EXECUTE() :

	SELECT, INSERT, UPDATE, DELETE :
		
		prepare() : permet de préparer sans exécuter
		execute() : permet d'exécuter la requête préparée

	Valeur de retour : 
		prepare() : renvoie TOUJOURS un PDOStatement (objet)
		execute() : ECHEC : false
					SUCCES : Objet PDOStatement

=> Les requêtes préparées sont à préconiser si vous exécuter plusieurs fois la même requête et ainsi éviter de répéter le cycle (analyse/interprétation/exécution)
=> Les requêtes préparées sont souvent utilisées pour assainir les données (ex : prepare() / bindValue() / execute() )

exemple : pourquoi requêtes préparées :

	select * from employes; => 3cycles (analyse/interprétation/exécution)
	select * from employes; => 3cycles
	select * from employes; => 3cycles
	select * from employes; => 3cycles => 12 cycles 

	prepare : $req = select * from employes; => 2cycles (analysée et interprétée)

		-> execute($req); 1cycle (exécution)
		-> execute($req); 1cycle
		-> execute($req); 1cycle
		-> execute($req); 1cycle => 6 cycles
*/

//----------------------------------------------------------------------------------
echo "<h1>Requêtes PDO</h1>";

echo "<h2> Connexion à la BDD </h2>";

$pdo = new PDO("mysql:host=localhost;dbname=entreprise", "root", "", 

    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8")

);

// Arguments de PDO :

//    • arg1: serveur + bdd
//    • arg2: identifiant
//    • arg3: mdp
//    • arg4: options (ici, la gestions des erreurs et encodage)

var_dump($pdo);

print '<pre>';
    print_r(get_class_methods($pdo)); //permet de récupérer les méthodes de l'objet
print '</pre>';



//________________________________________
echo "<h2>EXEC() / INSERT / UPDATE/ DELETE</h2>";

// $resultat = $pdo->exec("

//         INSERT INTO employes(prenom, nom, salaire, sexe, date_embauche, service)
//             VALUES('jean', 'baptiste', 1234, 'm', '2020-01-01', 'informatique')
        
//         ");
//Ici, on fait une insertion dans la table 'employes' pour les champs (prenom, nom, salaire, etc.) AVEC les valeurs correspondantes DANS LE BON ORDRE!

//On applique la méthode exec() VIA l'objet '$pdo' 'qui représente la connexion à la BDD) pour intéragir avec la BDD, ici en faisant une requête d'insertion.

// echo "Nombre d'enregistrements affectés par la requête: $resultat <br>";

// echo "Dernier id généré: " . $pdo->lastInsertId() . '<br>';

echo "Infinite Jean-Jacques";

//----------------------------------------------

//MODIFICATION:

$pdo -> exec("UPDATE employes SET salaire = 4545 WHERE id_employes = 991");
//Ici, je modifie la table "employes" et plus précisément la colonne salaire à condition que dans la colonne l'id employes soit égale à 991

//----------------------------------------------

//SUPPRESSION:

$pdo -> exec("DELETE FROM employes WHERE id_employes = 996");
//Ici je supprime dans la tables 'employes', A COONDITION que dans la colonne 'id_employes' ce soit égal à 991

    //rappel: les requetes delete sont irreversibles

// print '<pre>';
//     print_r(get_class_methods($pdo));
// print '</pre>';

//----------------------------------------------
echo "<h2>QUERY() / SELECT / FETCH()</h2>";

$pdostatement = $pdo->query("SELECT * FROM employes WHERE prenom = 'Emilie'");
//Ici je sélectionne toutes les informations provenant de la table 'employes' A CONDITION que dans la colonne 'prenom', ce soit égal à 'Emilie'. 

// var_dump( $pdostatement);

// print '<pre>';
//     print_r(get_class_methods($pdostatement));
// print '</pre>';

$emilie = $pdostatement->fetch(PDO::FETCH_ASSOC);
//fetch() : permet de récupérer la ligne suivante d'un jeu de résultats ! Et donc de pourvoir les exploiter. Les résultats sont retournés sous forme de tableaux associatifs

    //Le paramètre 'PDO::FETCH_ASSOC' de la méthode fetch() permet d'indéxer le tableau (retournée par le fetch) avec les champs de la table 'employes'

// print '<pre>';
//     print_r($emilie);
// print '</pre>';


echo "<p>Bonjour, je suis $emilie[prenom] $emilie[nom] du service $emilie[service].</p>";
echo "<p>Bonjour, je suis " . $emilie['prenom'] .' '. $emilie['nom'] . " du service " . $emilie['service'] . ".</p>";


foreach($emilie as $champ => $valeur){

    echo "$champ : $valeur /";
}

//------------------------------------------------------------
echo "<h2> QUERY() / SELECT / FETCH() / WHILE()</h2>";

$pdostatement = $pdo->query("SELECT * FROM employes");
//Ici, on récupère toutes les infos de la table employés

var_dump($pdostatement);

echo "<p>Nombre d'employes: " . $pdostatement->rowCount() . "</p>";
//rowCount(): permet de compter le nb de lignes de résultats retournés par la requête

while($contenu = $pdostatement -> fetch(PDO::FETCH_ASSOC)){
    //TANT QU'il y a une ligne de résultat (retournée par le fetch()), o, l'affiche (rappel: fetch retourne tjrs la ligne suivante d'un jeu de résultats)

// print '<pre>';
//     print_r($contenu);
// print '</pre>';

echo "<p style = 'border-bottom:1px solid; width:50%; margin:0 auto;'> $contenu[prenom] $contenu[nom] </p><br>";

};

//Ici il y a UN SEUL array pour chaque enregistrement (par employé)
//Avec fetch():
    //requête qiu retourne plusieurs résultats => boucle
    //requête qui ne retourne qu'un seul résultat => pas de boucle
    //requête qui ne retourne qu'un seul résultat MAIS potentiellement plusieurs => boucle
//------------------------------------------------------------
echo "<h2> QUERY() / SELECT / FETCHALL() / FOREACH()</h2>";

$pdostatement = $pdo->query("SELECT * FROM employes");

$donnees = $pdostatement -> fetchAll(PDO::FETCH_ASSOC);


// print '<pre>';
//     print_r($donnees);
// print '</pre>';

foreach($donnees as $employe){
    // print '<pre>';
        //print_r($donnees);
    // print '</pre>';

    echo "<p>";

    foreach($employe as $valeur){
        echo "$valeur /";
    }

    echo '</p>';
}

//------------------------------------------------------------
echo "<h2> QUERY() / SELECT / FETCH() / WHILE() / affichage sous forme de tableau </h2>";


$pdostatement = $pdo->query("SELECT * FROM employes");

echo "<table border='2'>";

    echo '<tr>';

        $nombre_colonne = $pdostatement->columnCount();
        //columnCount(): retourne le nombre de colonnes issues du jeu de résultats de la requête
        // echo $nombre_colonne;

        for($i =0; $i < $nombre_colonne; $i++){
            $champ = $pdostatement->getColumnMeta( $i );

            // print '<pre>';
            //     print_r($champ);
            // print '</pre>';

            echo "<th>$champ[name]</th>";
        }

    echo '<tr>'; //fin première ligne

    while($ligne = $pdostatement->fetch(PDO::FETCH_ASSOC)){

        // print '<pre>';
        //     print_r($ligne);
        // print '</pre>';

        echo '<tr>';

        foreach($ligne as $value){
            
            echo "<td> $value </td>";
        }

        echo'</tr>';
    }

echo "</table>";


//------------------------------------------------------------
echo "<h2> prepare() /bindParam() or bindValue()/ execute() </h2>";

//préparation de la requête:

$pdostatement = $pdo->prepare("SELECT * FROM employes WHERE nom = :nom");
    // :nom est un marqueur nominatif qu'il faut justifier (càd lui associer une valeur)

// var_dump($pdostatement);

$nom = 'Winter';

$pdostatement -> bindValue(":nom", $nom, PDO::PARAM_STR);
//bindValue(arg1, arg2, arg3) : reçoit une variable en justification du marqueur
    //arg 1 : marqueur nominatif (ici, nom)
    //arg 2 : justification du marqueur (ici, $nom)
    //arg 3 : verification du paramètre attendu (ici, String)


    $pdostatement -> execute(); //exécution de la requête

    $winter = $pdostatement->fetch(PDO::FETCH_ASSOC);

    print '<pre>';
        print_r($winter);
    print '</pre>';

echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
                    @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Open+Sans+Condensed:ital,wght@0,300;0,700;1,300&family=Roboto+Mono&family=Zen+Antique+Soft&family=Zen+Kaku+Gothic+Antique:wght@400;700&family=Zen+Kurenaido&display=swap');

        *{
            font-family: 'Open Sans Condensed', sans-serif;
            font-size: 20px;
            text-align: center;
        }
        h1{
            background-color: palevioletred;
            color: floralwhite;
            text-align: center;
            height: 50px;
            margin: auto;
            font-size: 35px;
            
        }
        h2{
            font-size: 28px;
        }

        table{
            margin: 0 auto;
        }

        
    </style>
</head>
<body>
    
</body>
</html>