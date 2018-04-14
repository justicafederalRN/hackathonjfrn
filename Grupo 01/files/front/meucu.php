$query = "SELECT * from cadastro";
$stmt = mysql_query($query,$conn); //lembrando que aqui deve vir a sua conexão com o banco de dados
echo "<table>";
echo "<tr><td>Nome:</td><td>Cidade</td></tr>";
while($resultado = mysql_fetch_array($stmt)){
echo "<tr><td>".$resultado['Nome']."</td><td>".$resultado['Cidade']."</td></tr>";
}
echo "</table>";