# mrsmith
Trabalho SI


- Registo 
    - Criar uma chave privada e pública - cifrar ficheiros
    - Criar uma chave privada e pública - assinar ficheiros
- Login


### Verficação se está online ou não
     - flag se iniciar a sessão fica on
     - De 6 em 6 segundos dá reset
     - O cliente está a mandar sempre mensagem de 5 em 5 segundos a perguntar quem está online, a flag no servidor fica on esta mensagem chegar

- Só podemos mandar mensagem a quem está online

### 
Pedro inicia a sessão -> manda password e user
Server envia a chave pública para o Pedro
O Pedro escolhe com quem quer falar e se quer cifrar ou assinar (gera chave de sessão). (fica em sala de espera)
---------
A Alice recebe uma notificação a dizer que o pedro quer comunicar com ela. (Sim ou Não) ( de 5 em 5 segundos está a pesquisar )
- Sim -> envia à alice a chave de sessão cifrada pela chave privada da
O Pedro cria a chave de sessão e cifrar com a chave pública 
Server recebe o criptograma e decifra para saber qual é a chave de sessão





### Para mandar uma mensagem


browser                    servidor

asjdkdfj (java) ----->  sassdsdrw (php) //para fazer password hash -sha256


