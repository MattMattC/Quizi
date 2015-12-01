<?php

require('Color.php');

$color = new Color();

print("\n-------------------------------------- \n");
print($color->getColoredString("Bienvenue au lancement de Quizi \n", "cyan"));
print("-------------------------------------- \n\n");

print("Veuillez entrer les différents paramètres \n\n ");


// Demande du host
print("Host ".$color->getColoredString("[127.0.0.1]", "green").":" );

$host = fscanf(STDIN, "%d.%d.%d.%d");
print(var_dump($host));

if($host==NULL){
	$database_host="127.0.0.1\n";
}else{
	$database_host=$host[0].".".$host[1].".".$host[2].".".$host[3]."\n";
}

print("\n\n");
print("Port ".$color->getColoredString("[null]", "green").":" );
if( !($database_port = fgets(STDIN))){	$database_port="null"; }

print("Nom de la base de donnee ".$color->getColoredString("[quizi]", "green").":" );
if( !($database_name = fgets(STDIN))){	$database_name="quizi"; }

print("Nom de l'utilisateur de la base de donnee ".$color->getColoredString("[user]", "green").":" );
if( !($database_user = fgets(STDIN))){	$database_user="user"; }

print("Mot de passe de la base de donnee ".$color->getColoredString("[password]", "green").":" );
if( !($database_password = fgets(STDIN))){	$database_password="password"; }


generateParams($database_host,$database_port, $database_name, $database_user, $database_password );


function generateParams($database_host, $database_port, $database_name, $database_user, $database_password){
	print($database_host."\n");
	print($database_port."\n");
	print($database_name."\n");
	print($database_user."\n");
	print($database_password."\n");

	echo exec('rm app/config/parameters.yml');
	
	$fp = fopen('app/config/parameters.yml', 'c');
	fwrite($fp, 'parameters :'."\n");
	fwrite($fp, '    database_host: '.$database_host);
	fwrite($fp, '    database_port: '.$database_port);
	fwrite($fp, '    database_name: '.$database_name);
	fwrite($fp, '    database_user: '.$database_user);
	fwrite($fp, '    database_password: '.$database_password);
	fwrite($fp, '    mailer_transport: smtp'."\n");
	fwrite($fp, '    mailer_host: '.$database_host."\n");
	fwrite($fp, '    mailer_user: null'."\n");
	fwrite($fp, '    mailer_password: null'."\n");
	fwrite($fp, '    secret: null'."\n");
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