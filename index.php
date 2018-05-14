<?php
  
  $str = file_get_contents('config.json');
  $db_info = explode(",", $str);  
//  $json = json_decode($str, true);
  define("servername", $db_info[0]);
  define("username", $db_info[1]);
  define("password", $db_info[2]);
  define("dbname", $db_info[3]);  


  function createTableIfNotExist(){
    // Create connection
    $conn = new mysqli(servername, username, password);
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "CREATE DATABASE IF NOT EXISTS " . dbname . ";"; 
    
    $query_response = $conn->query($sql);
    
    if ($query_response === TRUE) {
      $sql = "CREATE TABLE IF NOT EXISTS ". dbname . ".customer (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(50),  
    phone VARCHAR(50),
    address VARCHAR(100)
    )";
      $conn->query($sql);
//      echo "Table MyGuests created successfully";
      return true;
        
    } else {
      return false;
        echo "Error creating table: " . $conn->error;
    }

    $conn->close();
  }

  function getDataFromDb(){
    // Create connection
    $conn = new mysqli(servername, username, password);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT * FROM " . dbname . ".customer order by id desc";
    $result = $conn->query($sql);    
    $response = array();
    if($result != null){
      if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
              $response[] = $row;
          }
        return $response;
      } else {
        return $response;
  //        echo "0 results";
      }
    }
    $conn->close();
  }


  function saveDataToDb(){
    // Create connection
    $conn = new mysqli(servername, username, password);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "INSERT INTO " . dbname . ".customer (name, phone, address) VALUES ('" . $_POST['name'] . "', '" . $_POST['phone'] . "', '" . $_POST['address'] . "')";
    $result = $conn->query($sql);    
    if($result){
      $customer_data = getDataFromDb();
    }
    $conn->close();
  }

  if($_POST){
    saveDataToDb();
  }
  else{
    createTableIfNotExist();
  }

  $customer_data = getDataFromDb();
  
  
?>
<html>
  <head>
    <style>
      div{
        padding: 10px;
      }
      
      #customers {
          font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
          border-collapse: collapse;
          width: 100%;
      }

      #customers td, #customers th {
          border: 1px solid #ddd;
          padding: 8px;
      }

      #customers tr:nth-child(even){background-color: #f2f2f2;}

      #customers tr:hover {background-color: #ddd;}

      #customers th {
          padding-top: 12px;
          padding-bottom: 12px;
          text-align: left;
          background-color: #4CAF50;
          color: white;
      }
    
    </style>
  </head>
  
  <body style="text-align: center;">
    <form action="index.php" method="post" style="text-align: center">
      <h1> Information Form </h1>
      <div>
        <span>Name: </span>
        <input type="text" name="name">

      </div>
      <div>
        <span>Phone#: </span>
        <input type="number" name="phone">
      </div>
      <div>
        <span>Address: </span>
        <input type="text" name="address">
      </div>
      <div>
        <input type="submit" value="Submit">
      </div>
    </form>
    
    <div style="margin-top: 30px;">      
      
      <table id="customers">
        <tr>
          <th>Name</th>
          <th>Phone</th>
          <th>Address</th>
        </tr>
        <?php
          foreach ($customer_data as $customer) {
        ?>
          <tr>
            <td><?php echo $customer['name'];?></td>
            <td><?php echo $customer['phone'];?></td>
            <td><?php echo $customer['address'];?></td>
          </tr>
        <?php
        }
        ?>
      </table>    
    </div>
  </body>
</html>
