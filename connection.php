<?php

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "weather_db";
$api_key = "ed9df2220a6916b925408e169eecb6d5";


$conn = new mysqli($host, $user, $pass);


if ($conn->connect_error) {
    echo json_encode(["cod" => 500, "message" => "Server connection failed"]);
    exit;
}


$db_create = "CREATE DATABASE IF NOT EXISTS `$dbname`";
if ($conn->query($db_create) !== TRUE) {
    echo json_encode(["cod" => 500, "message" => "Database creation failed: " . $conn->error]);
    $conn->close();
    exit;
}

$conn->select_db($dbname);


$table_sql = "CREATE TABLE IF NOT EXISTS `weather_history` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `city_name` varchar(100) NOT NULL,
    `temp` double NOT NULL,
    `pressure` int(11) NOT NULL,
    `humidity` int(11) NOT NULL,
    `wind_speed` double NOT NULL,
    `wind_deg` int(11) NOT NULL,
    `weather_main` varchar(50) NOT NULL,
    `weather_desc` varchar(100) NOT NULL,
    `weather_icon` varchar(10) NOT NULL,
    `dt` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `city_name` (`city_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($table_sql) !== TRUE) {
    echo json_encode(["cod" => 500, "message" => "Table creation failed: " . $conn->error]);
    $conn->close();
    exit;
}

// 4. Delete old data (>2 hours)
$conn->query("DELETE FROM weather_history WHERE created_at < NOW() - INTERVAL 2 HOUR");

// 5. Get city (default Bristol)
$city = isset($_GET['city']) ? $conn->real_escape_string($_GET['city']) : "Bristol";

// 6. Check entered city in database as  there will be data within 2 hour only and remaining get deleted
$result = $conn->query("SELECT * FROM weather_history WHERE city_name = '$city' AND created_at >= NOW() - INTERVAL 2 HOUR
    ORDER BY created_at DESC LIMIT 1");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "name" => $row['city_name'],
        "dt" => (int)$row['dt'],
        "main" => ["temp" => (float)$row['temp'], "pressure" => (int)$row['pressure'], "humidity" => (int)$row['humidity']],
        "wind" => ["speed" => (float)$row['wind_speed'], "deg" => (int)$row['wind_deg']],
        "weather" => [["main" => $row['weather_main'], "description" => $row['weather_desc'], "icon" => $row['weather_icon']]],
        "cod" => 200,
        "source" => "database"
    ]);
} else {
    
    $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=$api_key&units=metric";
    $api_data = @file_get_contents($url);
    
    if ($api_data === false) {
        echo json_encode(["cod" => 502, "message" => "Weather API unavailable"]);
        $conn->close();
        exit;
    }
    
    $data = json_decode($api_data, true);

    if ($data && isset($data['cod']) && $data['cod'] == 200) {
        
        $match = $conn->prepare("INSERT INTO weather_history (city_name, temp, pressure, humidity, wind_speed, wind_deg, weather_main, weather_desc, weather_icon, dt, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $match->bind_param("sddiddsssi", 
            $data['name'], $data['main']['temp'], $data['main']['pressure'], 
            $data['main']['humidity'], $data['wind']['speed'], $data['wind']['deg'], 
            $data['weather'][0]['main'], $data['weather'][0]['description'], 
            $data['weather'][0]['icon'], $data['dt']
        );
        $match->execute();
        
        $data['source'] = "api";
        echo json_encode($data);
    } else {
        echo json_encode(["cod" => $data['cod'] ?? 404, "message" => $data['message'] ?? "City not found"]);
    }
}
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn->close();
?>
