<?php
    include 'header.php';?>

<!DOCTYPE html>

<div class="jumbotron d-flex align-items-center min-vh-100">
    <div class="container-sm text-center p-4 mt-4 border border-dark rounded-2 w-75">
<head>
	<title>Help</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	

<br>




<h1>Help</h1>

<h2>Funcionamento geral</h2>


<p> O objetivo principal é que os nossos utilizadores possam assinar e transmitir ficheiros de um modo seguro, com outros utilizadores,permitindo que o 
	utilizador escolha a cifra que desejar e o comprimento da chave da mesma,para usufruir desta aplicação só necessita de criar uma conta(sign-up) no nosso site.
</p>

<h2>Como funciona?</h2>

<p> No inicio do processo é gerada uma chave de sessão,esta chave é estabelecida entre o emissor e o receptor e utilizada durante um curto periodo de tempo,ou 
	seja, se noutra altura for estabelecida uma comunicação,outra chave de sessão será gerada.Isto significa que se a chave for corrompida,o canal entre esse par de utilizadores continua a ser seguro,uma vez que a chave de sessão é diferente.
	Uma das principais fragilidades numa comunicação é a garantia de que se está efectivamente a falar com o utilizador pretendido e não com outra entidade estranha/maliciosa,a solução arranjada consiste na introdução no sistema de um Agente de Confiança ou Trusted Agent que irá verificar a identidade de cada utilizador.
	Neste caso, só precisamos do agente de confiança para trocar a chave de sessão. Depois dessa troca, as comunicações fazem-se diretemente entre as duas entidades.
	O Kerberos é um exemplo de um protocolo comercial baseado nesta filosofia.
</p>


<h2>Cifras disponíveis </h2>


<h4>Rivest Cipher 4 (RC4)</h4>

<p>
	RC4 significa Rivest Cipher 4. RC4 é uma cifra de chave simétrica e foi inventada por Ron Rivest em 1987. Como RC4 é uma cifra de chave simétrica, esta cifra o fluxo de dados byte a byte. De todas as cifras de chave simétrica, RC4 é uma cifra amplamente usada devido à sua velocidade de operações e simplicidade.

</p>

<h4>Advanced Encryption Standard (AES)</h4>

<p>

	Advanced Encryption Standard (AES) — ou Padrão de Criptografia Avançada, em português — é o algoritmo padrão do governo dos Estados Unidos e de várias outras organizações. Este é confiável e excepcionalmente eficiente na sua forma em 128 bits, mas também é possível usar chaves e 192 e 256 bits para informações que precisam de proteção maior.

	O AES é amplamente considerado imune a todos os ataques, exceto aos ataques de força bruta, que tentam decifrar o código em todas as combinações possíveis em 128, 192 e 256 bits, o que é imensamente difícil na atualidade.</p>


<h4>Data Encryption Standard (DES)</h4>
<p>
	Data Encryption Standard (DES) é uma das primeiras criptografias utilizadas e é considerada uma proteção básica de poucos bits (cerca de 56). O seu algoritmo é o mais difundido mundialmente e realiza 16 ciclos de codificação para proteger uma informação.

	A complexidade e o tamanho das chaves de criptografia são medidos em bits. Quando uma criptografia é feita com 128 bits, significa que 2128 é o número de chaves possíveis para decifrá-la. Atualmente, essa quantidade de bits é considerada segura, mas quanto maior o número, mais elevada será a segurança.

	Quando dizemos que um bloco foi criptografado em bits, significa que um conjunto de informações passou pelo mesmo processo da chave, tornando-se ilegível para terceiros.

	O DES pode ser decifrado com a técnica de força bruta. Por essa razão, os desenvolvedores precisaram de pensar em alternativas de proteção mais complexas do que o DES.
	</p>


<h4>3DES(Data Encryption Standard)</h4>


	<p>
	O Triple DES foi originalmente desenvolvido para substituir o DES, já que hackers aprenderam a superá-lo com relativa facilidade,foi durante algum tempo o padrão recomendado para segurança.

	Esta criptografia recebe este nome pelo fato de trabalhar com três chaves de 56 bits cada, o que gera uma chave com o total de 168 bit. </p>



	<h2>Modos de Cifra disponíveis </h2>


	<h4>Electronic Code Book (ECB)</h4>

		<p>O modo mais simples e mais direto de usar uma cifra de chave simétrica por blocos é conhecido por Electronic Code Book
		(ECB). Neste modo, e visto que este tipo de cifra só opera sobre blocos de tamanho fixo, a mensagem acifrar é simplesmente 
		dividida em blocos de tamanho n, e cada bloco é cifrado independentemente dos outros, e com a mesma chave.
		</p>


	<h4>Cipher Block Chaining (CBC)</h4>

	<p>	Inventado em 1976 e similar ao ECB, o encadeamento de blocos fornece uma maneira consistente de crifrar e desifrar grandes quantidades de dados. 
		Em um processo de cifra por blocos, os blocos de texto são tratados como unidades isoladas a serem cifradas e desifradas sequencialmente. 
		No encadeamento de blocos, cada bloco de texto é desifrado em um processo que requer a observação dos blocos que já foram processados. O processo de encadeamento de blocos de cifras usa uma porta lógica chamada XOR para administrar esse processo de observação,e ao contrário do modo ECB este modo contém um vetor de inicialização,que adiciona um fator de aleatoriedade no processo de cifragem.</p>

	
	<br>	
	<a href="/" class="btn btn-primary">HomePage</a>
	<a href="perguntasfrequentes.php" class="btn btn-primary">Seguinte</a>
</body>
</div>
</div>
<?php
  include '../footer.php';?>
