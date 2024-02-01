
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Parser</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        main {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 80%;
            max-width: 600px;
            box-sizing: border-box;
            margin-top:2em;
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
        }

        input[type="file"] {
            padding: 8px;
            margin-bottom: 16px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            width:10em;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            text-align:center;
        }
        h2{
            text-align:center;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
        }
        
        th {
            background-color: #4caf50;
            color: #fff;
        }

        p {
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <main>
        <h2>Cloud Bank CSV Parser </h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
            $csvFile = $_FILES['csv_file']['tmp_name'];
        
            if (($handle = fopen($csvFile, "r")) !== FALSE) {
                $data = [];
                $rowCount = 0; 
        
                // Read CSV file
        
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $rowCount++; 
                    if($rowCount>1){
                        $category = $row[3];
                    $financialValue = floatval(str_replace(",", "", $row[8]));
        
                    // Sum financial values for each category
                    if (isset($data[$category])) {
                        $data[$category] += $financialValue;
                    } else {
                        $data[$category] = $financialValue;
                    }
                    }                    
                }        
                fclose($handle);
        
                echo "<p>Total Rows: {$rowCount}</p>";
            } else {
                echo "Error reading the CSV file.";
            }
        }
        ?>

        <form method="post" enctype="multipart/form-data">
            <label for="csv_file">Choose a CSV file to display values:</label>
            <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
            <br>
            <input type="submit" value="Parse and Display">
        </form>

        <table>
            <thead>
                <tr>
                <th>Category</th>
                <th>Financial Value Sum</th>
            </tr>
            </thead>
            
            <tbody>
                <?php
            // Display result as a table
            if(isset($data)){
                foreach ($data as $category => $sum) {
                echo "<tr><td>{$category}</td><td>{$sum}</td></tr>";
            }
            }else{
                echo "<tr><td colspan='2'>No data</td></tr>";
            }
            ?>
            </tbody>
            
        </table>

        <!-- Display the total number of rows -->
        <p>Total Rows: <?php echo isset($rowCount)?$rowCount:0; ?></p>
        <p>Developed by Chidubem Uzochukwu</p>
    </main>
</body>
</html>
