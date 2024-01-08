<?php

$conn = new mysqli("localhost", "root", "", "kalam_test");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$columns = array(
    0 => 'Lead_ID',
    1 => 'Name',
    2 => 'Mobile',
    3 => 'Email'
);

$requestData = $_REQUEST;

$query = "SELECT Lead_ID, Name, Mobile, Email FROM crm_lead_master_data";
$totalData = mysqli_num_rows(mysqli_query($conn, $query));


if (!empty($requestData['search']['value'])) {
    $query .= " WHERE name LIKE '%" . $requestData['search']['value'] . "%' ";
}

$totalFiltered = mysqli_num_rows(mysqli_query($conn, $query));


$query .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'];

$result = mysqli_query($conn, $query);
$data = array();

while ($row = mysqli_fetch_array($result)) {
    $nestedData = array();
    $nestedData[] = $row["Lead_ID"];
    $nestedData[] = $row["Name"];
    $nestedData[] = $row["Mobile"];
    $nestedData[] = $row["Email"];
    $data[] = $nestedData;
}


$json_data = array(
    "draw" => intval($requestData['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
);

echo json_encode($json_data);

$conn->close();
?>
