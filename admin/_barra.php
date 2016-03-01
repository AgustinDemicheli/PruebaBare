<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="63" colspan="2" bgcolor="#FFFFFF" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom; padding:7px 7px 12px 7px;">
        <table cellspacing="0" cellpadding="0" width="1495" align="left">
          <tr>
            <td width="125" valign="top" bgcolor="#FFFFFF"><a href="index.php"><img src="images/logo.png" style="border:none;" alt="Logo" height="50" /></a></td>
            <td width="1" valign="bottom" bgcolor="#FFFFFF" style="padding-bottom:5px;"></td>
            <td width="1203" valign="top" bgcolor="#FFFFFF"><? include_once("menu.php");?></td>
          </tr>
        </table>
    </td>
  </tr>
  
  <tr>
	  <td>
		  <table>
			  <tr>
				 <?if($_GET["g"]=="yes"){?>
					<td bgcolor="#FFFFFF" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom; padding:0px 7px 0px 7px;" align="right">
						<img src="images/status_A.gif" border="0">&nbsp;&nbsp;<span class="arial12"><font color="#C50000"><b> La home ha sido generada</b></font>
					</td>
				<?}?>
<!--				<td bgcolor="#FFFFFF" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom; padding:0px 7px 0px 7px;" align="right">
					<form name="F" method="POST" action="generar_contenidos.php">
						<input name="tipo" type="hidden" value="HOME">
						<input name="genera" type="hidden" value="1">
						<input name="ret" type="hidden" value="<?=$_SERVER["REQUEST_URI"]?>">
						<input name="imageField2" type="image" id="imageField2" src="images/btn_generar.gif">
					</form>
				</td>  -->
			  </tr>
		  </table>
	  </td>
  </tr>
  
</table>