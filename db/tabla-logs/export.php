<?php  
//export.php  
$connect = mysqli_connect("localhost", "root", "", "empleados");
$output = '';
if(isset($_POST["export"]))
{
 $query = "SELECT * FROM logs ORDER BY id DESC";
 $result = mysqli_query($connect, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>id</th>  
                         <th>nombre</th>  
                         <th>apellidos</th>  
                          <th>RFID</th>
                          <th>Hora</th>
                          <th>Fecha</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
    <tr>  
       <td>'.$row["id"].'</td>  
       <td>'.$row["nombre"].'</td>  
       <td>'.$row["apellidos"].'</td>  
       <td>'.$row["RFID"].'</td>  
       <td>'.$row["Hora"].'</td>
       <td>'.$row["Fecha"].'</td>
     </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=Horas.xls');
  echo $output;
 }
}
?>