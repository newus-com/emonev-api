<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "emonev";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

for($i = 167; $i <= 170; $i++){
    $insertUserQuery = "INSERT INTO role_permission (permissionId,roleId,createAt) VALUES ('$i','2','2023-08-24 02:40:54')";

    if ($conn->query($insertUserQuery) === FALSE) {
        echo "Error inserting user: " . $conn->error;
    } else {
        echo "Permission inserted successfully. $i";
    }
}

// $json_data = file_get_contents('permission.json');
// $data = json_decode($json_data, true);

// $userId = 1;
// $userName = "SuperAdmin";
// $userEmail = "admin@mail.com";
// $userPassword = password_hash("admin99", PASSWORD_DEFAULT);
// $userStatus = 0;
// $userCreatedAt = 123459372;

// $checkUserQuery = "SELECT id FROM User WHERE id = $userId";
// $userResult = $conn->query($checkUserQuery);

// if ($userResult->num_rows == 0) {
//     $insertUserQuery = "INSERT INTO User (id, name, email, password,status, createAt) VALUES ($userId, '$userName', '$userEmail','$userPassword','$userStatus', $userCreatedAt)";

//     if ($conn->query($insertUserQuery) === FALSE) {
//         echo "Error inserting user: " . $conn->error;
//     } else {
//         echo "User inserted successfully.";
//     }
// } else {
//     echo "User with ID $userId already exists, skipping...";
// }

// foreach ($data['module'] as $module) {
//     $moduleName = $module['name'];

//     $checkModuleQuery = "SELECT id FROM module WHERE name = '$moduleName'";
//     $moduleResult = $conn->query($checkModuleQuery);

//     if ($moduleResult->num_rows == 0) {
//         $insertModuleQuery = "INSERT INTO module (name,createAt) VALUES ('$moduleName','1692726391209')";
//         if ($conn->query($insertModuleQuery) === FALSE) {
//             echo "Error inserting module: " . $conn->error;
//         } else {
//             $moduleId = $conn->insert_id;

//             foreach ($module['permission'] as $perm) {
//                 $permName = $perm['name'];
//                 $permDescription = $perm['description'];

//                 $checkPermissionQuery = "SELECT id FROM permission WHERE moduleId = $moduleId AND name = '$permName'";
//                 $permissionResult = $conn->query($checkPermissionQuery);

//                 if ($permissionResult->num_rows == 0) {
//                     $insertPermissionQuery = "INSERT INTO permission (moduleId, name, description, createAt) VALUES ($moduleId, '$permName', '$permDescription','1692726391209')";
//                     if ($conn->query($insertPermissionQuery) === FALSE) {
//                         echo "Error inserting permission: " . $conn->error;
//                     }
//                 } else {
//                     echo "Permission '$permName' for module '$moduleName' already exists, skipping...";
//                 }
//             }
//         }
//     } else {
//         echo "Module '$moduleName' already exists, skipping...";
//     }
// }

// $createAT = 1692726391209;

// $permissionIds = array();
// $getPermissionIdsQuery = "SELECT id FROM permission";
// $permissionResult = $conn->query($getPermissionIdsQuery);

// if ($permissionResult->num_rows > 0) {
//     while ($row = $permissionResult->fetch_assoc()) {
//         $permissionIds[] = $row['id'];
//     }
// }

// foreach ($permissionIds as $permissionId) {
//     $insertUserPermissionQuery = "INSERT INTO user_permission (userId, permissionId, createAt) VALUES ($userId, $permissionId, $createAT)";
//     if ($conn->query($insertUserPermissionQuery) === FALSE) {
//         echo "Error inserting user_permission record: " . $conn->error;
//     }
// }

// echo "User permissions inserted successfully.";

$conn->close();
