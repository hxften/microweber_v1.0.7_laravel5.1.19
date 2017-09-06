<?php 
// Saving
/*$save = array(
    'name' => 'wangkai',
    'description' => 'Things to buy from the car'
);*/
// 返回插入id
//$id = db_save('cars', $save);
//print_r($id);

// Getting
/*$data = db_get("table=cars");
print_r($data);*/
//返回单个行
// db_get('table=cars&name=wangkai1&single=true')
/*$row = db_get(array(
    'table' => 'cars',
    'name' => 'wangkai1',
    'single' => true
));
print_r($row);*/


// Update
// Gets single row with id = 3
/*$row = db_get(array(
    'table' => 'cars',
    'id' => 3,
    'single' => true
    ));
$row['name'] = 'My Awesome Painting';
echo 'Updating row with ID ', $row['id'];
db_save('cars', $row);*/

// Deleting
//$id =db_delete('cars', 5);print_r($id);
?>