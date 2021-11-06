<?php
/*
	-- 1 -- Creation d'une BDD : 'dialogue'

		CREATE DATABASE dialogue;
		USE dialogue;

	-- 2 -- Création d'une table : 'commentaire' (id_commentaire, pseudo, message, date_enregistrement)

		CREATE TABLE commentaire(
			id_commentaire INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			pseudo VARCHAR(20) NOT NULL,
			message TEXT NOT NULL,
			date_enregistrement DATETIME NOT NULL
		) ENGINE=InnoDB;
*/

// 3 - Connexion à la bdd: 'dialogue'

$pdo = new PDO("mysql:host=localhost;dbname=dialogue", "root", '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));




// 4 - Création d'un formulaire avec les champs adéquats (pseudo, message):

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Open+Sans+Condensed:ital,wght@0,300;0,700;1,300&family=Roboto+Mono&family=Zen+Antique+Soft&family=Zen+Kaku+Gothic+Antique:wght@400;700&family=Zen+Kurenaido&display=swap');

        *{
            font-family: 'Dancing Script';
            font-size: 20px;
        }

        body{
            background-image: url('https://images.pexels.com/photos/1533720/pexels-photo-1533720.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260');
            object-fit: cover;
            background-attachment: fixed;
            
        }

        textarea{
            border-radius: 15px;

        }

        h1{
            text-align: center;
            font-size: 35px;
            font-family: 'Zen Antique Soft';
        }

        header{
            background-color: #f0fff07d;
            width: 50%;
            margin: 0 auto;
            border-radius: 10px;
            
        }

        .container{
            display: flex;
            justify-content: center;
            font-weight: bold;
        }
        .container3{
            width: 50%;
            margin: 0 auto;
        }

        .post{
            background-color: #f0fff07d; 
            border-radius: 10px;
            padding: 1px 5px 5px 15px;
            margin: 20px;
            
        }

        .post p{
            font-family: 'Zen Kaku Gothic Antique';

        }
        .post h3{
            font-weight: bold;
            font-family: 'Zen Kaku Gothic Antique';
            
        }

        .post i{
            font-family: 'Zen Kaku Gothic Antique';
            
            font-size: 12px;
        }

        .flex{
            display: flex;
            justify-content: space-between;
        }

        .delete{
            text-decoration: none;
            background-color: indianred;
            color: floralwhite;
            padding: 2px;
            border-radius: 10px;
        }

        .update{
            border: none;
            background-color: #f0fff00d ;
            height: fit-content;
        }

    </style>
</head>
<body>
    
    <header>
        <h1>フォローム</h1>
    </header>

    <div class="container">
        <form action="" method="post">

            <label for="">Pseudo</label>
            <input type="text" name="pseudo"><br><br>

            <label for="">Message</label><br>
            <textarea type="text" name="message" cols='30' rows='10'></textarea><br><br>

            <input type="submit" value="Poster">

        </form>
    </div>

    <hr>
    <?php

// print '<pre>';
//     print_r($_POST);
// print '</pre>';

// - 5 Insertion (prepare/execute) des messages postés en BDD : NOW() : fonction SQL qui permet de retourner la date et l'heure courante
// if(isset($_POST['pseudo']) && isset($_POST['message'])){
if($_POST){

// echo "Pseudo posté: $_POST[pseudo] <br>";
// echo "Message posté: $_POST[message] <br>";



$pdostatement = $pdo -> prepare("INSERT INTO commentaire(pseudo, message,date_enregistrement) VALUES(:pseudo, :message,  NOW() )");

    foreach($_POST as $indice => $valeur){

        $_POST[$indice] = addslashes($_POST[$indice]);
        $_POST[$indice] = stripslashes($_POST[$indice]);
        $_POST[$indice] = htmlentities(strip_tags($_POST[$indice]));
    }

$pseudo = $_POST['pseudo'];
$message = $_POST['message'];

$pdostatement -> bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
$pdostatement -> bindValue(":message", $message, PDO::PARAM_STR);

$pdostatement -> execute();

}

// print '<pre>';
// print_r($_GET['postnumber']);
// print '</pre>';

if(isset($_GET['postnumber'])){
// $_GET['postnumber'] = $number_id;

function delete($postNumber){

    global $pdo;

    $pdo -> exec("DELETE FROM commentaire WHERE id_commentaire = '{$postNumber}'");

}

delete($_GET['postnumber']);
};

//6 - affichage des messages

// $pdostatement2 = $pdo -> query("SELECT * FROM commentaire WHERE pseudo = '{$pseudo}'");

$pdostatement2 = $pdo -> query("SELECT * FROM commentaire ORDER BY date_enregistrement DESC");



echo "<section class='container3'>";

echo "<p> Il y a " . $pdostatement2->rowCount() . " messages </p>";

while($post = $pdostatement2 -> fetch(PDO::FETCH_ASSOC)){

    echo "<div class='post'>";
    echo "<div class='flex'>";
    echo "<h3>". $post['pseudo'] . "</h3>";
    echo "<p><i>". $post['date_enregistrement'] . "</i></p>";
    echo "</div>";
    echo "<textarea class='update' disabled>" . $post['message'] . "</textarea>";
    echo "<a class='delete' href='?postnumber=" . $post['id_commentaire']. "'>Regrets</a>";
    echo "</div>";

};

echo "</section>";


// echo $post['pseudo'];


// print '<pre>';
// print_r($post);
// print '</pre>';


//CORRECTION:
//// // -  5 - Insertion des messages postés en BDD : ( NOW() : fonction SLQ qui permet de reoturner la date et l'heure courante  )

// if( $_POST ){ //Si on valide le formulaire

//     // echo "Pseudo posté : $_POST[pseudo] <br>";
//     // echo "Message posté : ". $_POST["message"] ."<br>";

//     //Insertion (pas sécurisé)
//     //$pdo->exec(" INSERT INTO commentaire( pseudo, message, date_enregistrement ) VALUES( '$_POST[pseudo]', '$_POST[message]', NOW() ) ");

//     //$pdo->exec(' INSERT INTO commentaire( pseudo, message, date_enregistrement ) VALUES( "'.$_POST['pseudo'].'", "'. $_POST['message'].'", NOW() ) ');

//     // //-------------------------------------------
//     //addslashes() : permet d'accepter les apostrophes
//     $_POST['message'] = addslashes( $_POST['message'] );
//         //echo $_POST['message'] . '<br>';

//     //htmlentities() : converti les caractères spéciaux en entités HTML
//     $_POST['message'] = htmlentities( $_POST['message'] );
//        // echo $_POST['message'] . '<br>';

//     //htmlspecialchars() : converti les caractères spéciaux en entités HTML
//     //$_POST['message'] = htmlspecialchars( $_POST['message'] );
//         //echo $_POST['message'] . '<br>';

//     //strip_tags() : permet de supprimer les balises HTML et PHP
//     //$_POST['message'] = strip_tags( $_POST['message'] );
//        // echo $_POST['message'] . '<br>';

//     //Insertion préparation de la requête :
//     $pdostatement = $pdo->prepare(" INSERT INTO commentaire (pseudo, message, date_enregistrement) 
//                                     VALUES ( :pseudo, :message, NOW() )
//                  ");
//         //NOW() : fonction SQL qui retourne la date et l'heure courante
//     //var_dump( $pdostatement );

//         //Justification des marqueurs :
//         $pdostatement->bindParam( ':pseudo', $_POST['pseudo'], PDO::PARAM_STR );
//         $pdostatement->bindParam( ':message', $_POST['message'], PDO::PARAM_STR );

//     //execution de la requête préparée :
//     $pdostatement->execute();

//     //----------------------------------------------------------------
//     //Exemple de failles :

//     // $pdo->exec(" INSERT INTO commentaire( pseudo, date_enregistrement, message ) 
//     //                             VALUES( '$_POST[pseudo]', NOW(), '$_POST[message]' )
//     //         ");

//     //     //faille css :
//     //     //<style> body{display:none;} </style>

//     //     //faille SLQ :
//     //     //ok'); DELETE FROM commentaire;(
// }

// // 6 -  affichage des messages
// //6.1 : récupération des données en BDD :
// // $pdostatement = $pdo->query(" SELECT * FROM commentaire ORDER BY id_commentaire DESC ");
// // //Ici, je récupère toutes les informations de la table commentaire ordonnées par id_commentaire dans l'ordre décroissant donc le dernier id sera affiché en premier.

// // //Affichage du nombre de message : 
// // echo "Il y a " . $pdostatement->rowCount() . ' messages <br>';

// // //6.2 - affichage des commentaires :
// // while( $commentaire = $pdostatement->fetch( PDO::FETCH_ASSOC ) ){

// //     // print '<pre>';
// //     //     print_r( $commentaire );
// //     // print '<pre>';

// //     $commentaire['message'] = stripslashes( $commentaire['message'] );
// //     //stripslashes() : permet d'enlever les antislashs d'une chaine

// //     echo "<div style='border:1px solid'>";

// //         echo "<p> $commentaire[pseudo] - le $commentaire[date_enregistrement] </p>";
// //         echo "<p> $commentaire[message]</p>";

// //     echo "</div>";
// //}

//     //version fetchAll() :
//     $pdostatement = $pdo->query(" SELECT * FROM commentaire ORDER BY id_commentaire DESC ");

//     $donnees = $pdostatement->fetchAll( PDO::FETCH_ASSOC );

//     // print "<pre>";
//     //     print_r( $donnees );
//     // print "</pre>";

//     foreach( $donnees as $sous_tableau ){
//         // print "<pre>";
//         //     print_r( $sous_tableau );
//         // print "</pre>";

//         foreach( $sous_tableau as $indice => $valeur ){
        
//             if( $indice == 'message' ){

//                 $sous_tableau[$indice] = stripslashes( $valeur );

//                 $test = $sous_tableau[$indice];
//                 echo $test;
//             }
//             else{
//                 echo "$valeur / ";
//             }
//         }
//     }

    ?>
<hr>







</body>
</html>