<?php

?>

<!DOCTYPE html>
<html>
<head>
	<title>Perguntas Frequentes</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<<?php include("header.php"); ?>
	<div class="titulo">
	<h1> Perguntas Frequentes </h1>
</div>




<h2>Não consegue aceder a sua conta?</h2>

<p>Neste caso, o problema pode ter várias causas. Se está a receber a mensagem de que Username e password estão incorretos, verifique se a tecla Caps Lock está ativada e se o teclado está no idioma correto.

Se recebe uma mensagem que diz que o e-mail é inválido, verifique se não se esqueceu ou duplicou o símbolo de @ ou se esqueceu de preencher alguma parte do e-mail.

Se a página fica a atualizar costantemente e não consegue aceder á sua conta, verifique se o seu browser/navegador está atualizado,e se não,atualize e volte a tentar. </p>

<h2>Qual a cifra( algoritmo criptográfico) que devo utilizar?</h2>

<p>
Cada um dos algoritmos criptográficos possui vantagens e desvantagens e todos eles podem ser submetidos a ataques por terceiros. Selecionamos o algoritmo criptográfico com base nas demandas do aplicativo que será utilizado. Se a confidencialidade e a integridade forem fatores importantes, o algoritmo AES pode ser selecionado. Se a demanda da aplicação for a largura de banda da rede, o DES é a melhor opção.
Contudo existem muitos outros fatores que influenciam na escolha de uma cifra,para uma comparação mais detahada entre cifras <a href="https://symbiosisonlinepublishing.com/computer-science-technology/computerscience-information-technology32.php">click aqui</a></p>

<h2>Qual o modo de cifra que devo utilizar?</h2>

<p>
	O modo de cifra Cipher Block Chaining (CBC) têm uma vantagem crucial em relação ao Electronic Code Book (ECB),blocos idênticos não têm a mesma cifra,uma vez que vetor de inicialização adiciona um fator aleatório a cada bloco,portanto os mesmos blocos em posições diferentes terão cifras diferentes.
	Isto significa que de textos limpos iguais resultam criptogramas distintos, inviabilizando ataques por code book e por repetição.
	Como isto recomenda-se o modo de cifra CBC em relacão ao ECB,uma vez que este aumenta a segurança.

</p>

</body>
</html>