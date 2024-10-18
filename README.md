#Lista de Compras Laravel [API REST]

Background: O projeto foi desenvolvido sobre o sistema operacional Windows 11, com o uso do framework Laravel Sail, executando sobre um ambiente WSL2. Devido a sua natureza conteinerizada, o Sail deve necessitar apenas do Docker instalado para a realização de um deploy local.

Instalação: Uma vez dentro do diretório do projeto, execute os seguintes passos:
Para montar o projeto:		  	$ ./vendor/bin/sail build 
Para criar as tabelas no banco: 	$./vendor/bin/sail artisan migrate
Para executar o projeto:	  	$./vendor/bin/sail up -d
Se tudo der certo, será possível visualizar os dois contêineres que compõem o projeto: o Laravel e o MySQL, como mostra a figura abaixo:
![screenshot](Docker-screenshot.png)
