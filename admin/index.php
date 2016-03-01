<? 
include_once("../includes/DB_Conectar.php");
include_once("../includes/lib/auth.php"); 

include_once("../includes/lib/class.usuario.php");

?>
<HTML>
  <HEAD>
  <Title><?=$TITULO_SITE?></Title>
  <link rel="stylesheet" href="css/stylo.css" type="text/css">
</HEAD>
<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="body">
<script type="text/javascript" src="../includes/lib/jQuery/jquery.js"></script>
    <style type="text/css">
        #feedback {
            display: none;
            position: absolute;
            top: 150px;
            left: 0;
            background-color: #0066cc;
            height: 120px;
            width: 360px;
            margin: 0 0 0 -390px;
            padding-left: 25px;
        }
        
        #feedback img.img_feedback{
            position: absolute;
            right: -40px;
            top: 0;
            
        }
        h3 {
			font:bold 20px Arial, Helvetica, sans-serif; color:#FFF; padding:0; margin:15px 0 4px 0;
        }
        #div_listado_notas{
            border:1px solid #FFF;
            height: 100px;
            margin-top: 20px;
            overflow-y: scroll;
            padding: 11px 5px;
            width: 320px;
			background:#F5F5F5;
			
        }
        #tbl_listado_notas{
			width:300px;
			font:normal Arial, Helvetica, sans-serif; color:#333;
        }
        
        #tbl_listado_notas td.txt_nota{
           color:#333;
           padding:5px;
		   border-bottom:1px solid #999;
			 color:#333;
			font-family:Arial;font-size:12px;
        }
        .pointer{
            cursor:pointer;
        }
		
	
					
    </style>
<?include_once("_barra.php");?>
<table width="1004" border="0" align="center" cellpadding="15" cellspacing="0">
  <tr>
    <td><table width="600" border="0" align="center" cellpadding="0" cellspacing="10" bgcolor="#FFFFFF">
      <tr>
        <td width="41" height="41"><img src="images/avatar.jpg" width="41" height="41"></td>
        <td width="565" align="left" valign="middle"><span class="titulo_grande">Bienvenido <?=$_SESSION["sessUsuario"]?></span><br>
          <!--<span class="textomediano">Tu ultimo ingreso fue el 3/8/2011 a las 11:05 hs.</span>--></td>
        <td width="134" align="right" valign="middle"><a href="logout.php"><img src="images/btn_salir.png" width="60" height="24"></a></td>
      </tr>
    </table></td>
  </tr>
</table>
</BODY>
</HTML>