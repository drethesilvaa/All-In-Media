<?php
include("../config.php");
if( isset($_REQUEST['action']) ){
	switch( $_REQUEST['action'] ){
		case "SendMessage":
		session_start();
		if(grupoedele($_REQUEST['idgrupo']) == 1)
		{
		echo '<script>alert("hello!");</script>';
			$query = $db->prepare("insert INTO chat SET idQuemEnviou=?, message=?, idGrupo=?");
			$query->execute([$_SESSION['id'], $_REQUEST['message'], $_REQUEST['idgrupo']]);
		}
		break;
		case "getChat":
		session_start();
		$GrupoId=$_REQUEST['idgrupo'];
		if(grupoedele($GrupoId) == 1)
		{
		//Trocar o grupo 1 pelo numero do grupo selecionado
			$query = $db->prepare("select chat.*, grupo.nome, users.name, users.password, users.email from chat, users, grupo WHERE grupo.id = $GrupoId AND idQuemEnviou = users.id GROUP By id");
			$query->execute();
			$rs = $query->fetchAll(PDO::FETCH_OBJ);
			
			$chat = '';
			foreach( $rs as $r ){
				$chat .=  '<div class="siglemessage"><strong>'.$r->name.' says:  </strong>'.$r->message.'</div>';
			}
			echo $chat;
		}
		break;
		case "AdicionarPessoa":
		session_start();
		$GrupoId=$_REQUEST['idgrupo'];
		if(grupoedele($GrupoId) == 1)
		{
		//Trocar o grupo 1 pelo numero do grupo selecionado
			$query = $db->prepare("select amigos.*, users.name  AS nome FROM amigos, users WHERE ((idPedido = ".$_SESSION['id']." AND idAceitar = users.id) OR (idAceitar = ".$_SESSION['id']." AND idPedido = users.id)) AND Aceite = 1");
			$query->execute();
			$rs = $query->fetchAll(PDO::FETCH_OBJ);
			
			$chat = '';
			foreach( $rs as $r ){
				if($_SESSION['id'] != $r->idPedido)
				{
					$chat .=  '<button onclick="Addpersonahref('.$r->idPedido.')" class="siglemessagse"><strong>'.$r->nome.'</strong></button>';
				}
				else
				{
					$chat .=  '<button onclick="Addpersonahref('.$r->idAceitar.')" class="siglemessasge"><strong>'.$r->nome.'</strong></button>';
				}
			}
			echo $chat;
		}
		break;
		case "AdicionarPersonahref":
		echo '<script>alert("hello!");</script>';
		session_start();
		if(grupoedele($_REQUEST['idgrupo']) == 1)
		{
		echo '<script>alert("hello!");</script>';
		
			$query = $db->prepare("insert INTO pessoasdogrupo SET idpessoa=?, idgrupo=?, IsAdmin=3");
			$query->execute([$_REQUEST['idpessoa'],  $_REQUEST['idgrupo']]);
		}
		break;
		case "AceitarPdd":
		session_start();
			$query = $db->prepare("update pessoasdogrupo SET IsAdmin=0 WHERE id=?");
			$query->execute([$_REQUEST['id']]);
		break;
  }
}
function grupoedele($id)
{
    $con = mysqli_connect("localhost","root","", "phpteste");
    $contador=0;
    $sql = "select * FROM pessoasdogrupo, grupo WHERE idpessoa = '".$_SESSION['id']."' and pessoasdogrupo.idgrupo = grupo.id and pessoasdogrupo.idgrupo='" .$id. "'";
    $query=mysqli_query($con, $sql);
    $num=mysqli_num_rows($query);
    for($i=0;$i<$num;$i++)
    {
    $contador = 1;
    }
    mysqli_close($con);
    if($contador == 1)
    {
        return 1;
    }
    else
    {
        return 0;
    }
}
?>