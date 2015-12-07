<?php

require('Color.php');

$color = new Color();

print("\n-------------------------------------- \n");
print($color->getColoredString("Bienvenue au lancement de Quizi \n", "cyan"));
print("-------------------------------------- \n\n");

print("Veuillez entrer les differents parametres \n\n");


// Demande du host
print("Host ".$color->getColoredString("[127.0.0.1]", "green").":" );

$database_host="";
$host = fscanf(STDIN, "%s");
if($host==NULL){	$database_host="127.0.0.1"; }
else{ $database_host=$host[0]; }


// Demande du port
print("Port ".$color->getColoredString("[null]", "green").":" );
$database_port = fscanf(STDIN, "%s");
if($database_port==NULL){$database_port="null";}else{
	$database_port=$database_port[0];
}


// Demande du nom de la BDD
print("Nom de la base de donnee ".$color->getColoredString("[quizi]", "green").":" );
$database_name = fscanf(STDIN, "%s");
if($database_name==NULL){$database_name="quizi";}
else{ $database_name=$database_name[0]; }


// Demande du nom d'utilisateur de la BDD
print("Nom de l'utilisateur de la base de donnee ".$color->getColoredString("[user_quizi]", "green").":" );
$database_user = fscanf(STDIN, "%s");
if($database_user==NULL){
	$database_user="user_quizi";
}else{
	$database_user=$database_user[0];
}


// Demande du mot de passe de la BDD
print("Mot de passe de la base de donnee ".$color->getColoredString("[password]", "green").":" );
$database_password = fscanf(STDIN, "%s");
if($database_password==NULL){
	$database_password="password";
}else{
	$database_password=$database_password[0];
}


generateParams($color, $database_host,$database_port, $database_name, $database_user, $database_password );


function generateParams($color, $database_host, $database_port, $database_name, $database_user, $database_password){
	print($color->getColoredString("[database_host]", "green"). " : " .$database_host."\n");
	print($color->getColoredString("[database_port]", "green"). " : " .$database_port."\n");
	print($color->getColoredString("[database_name]", "green"). " : " .$database_name."\n");
	print($color->getColoredString("[database_user]", "green"). " : " .$database_user."\n");
	print($color->getColoredString("[database_password]", "green"). " : " .$database_password."\n");


	echo exec('rm app/config/parameters.yml');
	
	$fp = fopen('app/config/parameters.yml', 'c');
	fwrite($fp, 'parameters :'."\n");
	fwrite($fp, '    database_host: '.$database_host."\n");
	fwrite($fp, '    database_port: '.$database_port."\n");
	fwrite($fp, '    database_name: '.$database_name."\n");
	fwrite($fp, '    database_user: '.$database_user."\n");
	fwrite($fp, '    database_password: '.$database_password."\n");
	fwrite($fp, '    mailer_transport: smtp'."\n");
	fwrite($fp, '    mailer_host: '.$database_host."\n");
	fwrite($fp, '    mailer_user: null'."\n");
	fwrite($fp, '    mailer_password: null'."\n");
	fwrite($fp, '    secret: a37bbe4372b18dc6977b831e9e6be5e0ff8d7e6e'."\n");
	fclose($fp);
}

echo exec('php app/console doctrine:database:drop --force');
echo "\n";
echo exec('php app/console doctrine:database:create');
echo "\n";
echo exec('php app/console doctrine:schema:update --force');
echo "\n";
echo exec('php app/console doctrine:fixtures:load --append');
echo "\n";

/*

    database_host: 127.0.0.1
    database_port: null
    database_name: quizi
    database_user: root
    database_password: null
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    secret: a37bbe4372b18dc6977b831e9e6be5e0ff8d7e6e

    */

?>