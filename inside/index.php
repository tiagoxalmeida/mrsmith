<?php
    include "../header.php";
    include "../inc/con_inc.php";
    
    
  
      function upd($a){
        $tes = mysqli_query($a,"SELECT u_name FROM users u, online o where u.u_id = o.u_id");
        while($row =$tes->fetch_assoc()){
      
          $array[] = $row;
          }
          return json_encode($array);
      }
        
?>

<section class="container-sm p-4 mt-4 border border-dark rounded-2 w-75"> 
<style>

/* Full-width textarea */
.form-container textarea {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
  resize: none;
  min-height: 200px;
}

/* When the textarea gets focus, do something */
.form-container textarea:focus {
  background-color: #ddd;
  outline: none;
}
.open-button {
  background-color: #455;
  color: white;
  padding: 1% 1.4%;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 40px;
  width: 280px;
}
.open-button-top {
  background-color: #455;
  color: white;
  padding: 1% 1.4%;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  top: 23px;
  right: 40px;
  width: 280px;
}
.chat-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}
.conexao-popup {
  display: none;
  position: fixed;
  top: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}


.form-container .btn {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover, .open-button-top:hover {
  opacity: 1;
}
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 30%;
  border: 1px solid #ddd;
  margin: left;
  overflow-y: scroll;
}

th, td {
  text-align: left;
  padding: 8px;
}
#tabela th{
    background-color: #33C4FF;
}
tr:nth-child(even){background-color: #f2f2f2}
</style>

    <h1 class="text-center" id="teste">Mr. Smith</h1>
    <h2 class="text-center">Estás conectado a:</h2>

    <button type="button" class="btn btn-primary" onclick="window.location.reload();">Atualizar</button>
    <button class="open-button" onclick="openForm()" id="chat">Send</button>
    <button class="open-button-top" onclick="openForm1()" id="con">Conect</button>
    <div class="chat-popup" id="pop">
  <form action="/index.php" class="form-container">
    <h1>Send</h1>
    <input type="checkbox" id="cifrar" name="cifra" value="cifra">
    <label for="vehicle1"> I want to cypher</label><br>
    <input type="checkbox" id="assinar" name="assinatura" value="assinatura">
    <label for="vehicle1"> I want to sign it</label><br>
    <label for="myfile">Select a file:</label>
    <input type="file" id="myfile" name="myfile"><br><br>

    <button type="submit" class="btn">Send</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
  </form>
</div>
<div class="conexao-popup" id="conpop">
  <form action="/index.php" class="form-container">
    <h1>Connect</h1>
    <textarea placeholder="Name of the user you want to conect" name="user" type="submit" required ></textarea>
    <button type="submit" class="btn">Connect</button>
    <button type="button" class="btn cancel" onclick="closeForm1()">Close</button>
  </form>
</div>
    <div style="overflow-x:auto;">
        <table id = "tabela"> 
        <tr>
        <th>Users Online</th>
        </tr>
        <script>
       window.onload= function atualiza(){
      
            var table = document.getElementById("tabela");
            for (var i = 1; i < table.rows.length; i++) {
               table.deleteRow(1);
            }
            var users =<?php echo upd($conn);?>
           
        for (i=0; i<users.length; i++) {
            var row = table.insertRow(1);
            var cell1 = row.insertCell(0);
            cell1.innerHTML = users[i].u_name;
        }
    }
    function openForm() {
  document.getElementById("pop").style.display = "block";
 
  document.getElementById("chat").style.display = "none";
}

function closeForm() {
  document.getElementById("pop").style.display = "none";
 
  document.getElementById("chat").style.display = "block";
}
function openForm1() {
  
  document.getElementById("conpop").style.display = "block";
  document.getElementById("con").style.display = "none";
}

function closeForm1() {
  
  document.getElementById("conpop").style.display = "none";
  document.getElementById("con").style.display = "block";
}

</script>
  
  
        </table>
    </form>
</section>
<script>
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>

<?php
    include "../inc/close_con.php";
    include "../footer.php";
?>