<?php

session_start();
require_once "db.php";

?>
<div class="manager_nav">
  <div class="manager_nav-item" onclick="manager.navScrollTo(0)">
    Statistics
  </div>
  <div class="manager_nav-item" onclick="manager.navScrollTo(1)">
    Manage employees
  </div>
  <div class="manager_nav-item" onclick="manager.navScrollTo(2)">
    Manage items
  </div>
</div>
<div class="global_logout" onclick="window.location.href='logout.php'"><?php
echo $_SESSION["user_info"]["u_name_private"];
?> - Logout</div>
<div class="manager_content">
  <div class="manager_content-stats manager_content-item" id="manager_content-stats">
    <h1 class="manager_content-heading1">Statistics</h1>
    <!--canvas id="myChart" width="400" height="400"></canvas>
    <script>
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
    </script-->
    <?php

    $query = "SELECT * FROM stats WHERE shop_id=:shop_id";
    $getStatsStmt = $pdo->prepare($query);
    $getStatsStmt->execute([
      "shop_id" => $_SESSION["user_info"]["shop_id"]
    ]);
    $fetched = $getStatsStmt->fetch(PDO::FETCH_ASSOC);

    $income = json_decode(openssl_decrypt($fetched["income"], "AES-256-CBC", $_SESSION["pwd"], 0, $_SESSION["iv"]), true);
    $outcome = json_decode(openssl_decrypt($fetched["outcome"], "AES-256-CBC", $_SESSION["pwd"], 0, $_SESSION["iv"]), true);

    echo "<div class='manager_content-stats-profit'>Total profit: " .
         number_format($income["2018"] - $outcome["2018"], 0, '.', '') . "THB</div>";
    echo "<div class='manager_content-stats-profit'>Income: " . $income['2018'] . "THB</div>";
    echo "<div class='manager_content-stats-profit'>Outcome: " . $outcome['2018'] . "THB</div>";

    ?>
  </div>
  <div class="manager_content-manemp manager_content-item" id="manager_content-manemp">
    <h1 class="manager_content-heading1">Manage employees</h1>
    <table id="employee-table">
      <thead>
        <th>Name</th>
        <th>Salary</th>
        <th>Work committed</th>
        <th>Work since</th>
        <th>Employ type</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php

        require_once "db.php";
        $query = "SELECT * FROM user WHERE shop_id=:shop_id";
        $getEmployeesStmt = $pdo->prepare($query);
        $getEmployeesStmt->execute([
          "shop_id" => $_SESSION["user_info"]["shop_id"]
        ]);
        $employees = $getEmployeesStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($employees as $emp) {
          if($emp["u_type"] == 0)
          {
            continue;
          }
          echo "<tr>";
          echo "<td class='manager_employee-table-name' data-uid='" . $emp["uid"] . "'>" . openssl_decrypt($emp["u_name_stats"], "AES-256-CBC", $_SESSION["pwd"], 0, $_SESSION["iv"]) . "</td>";
          echo "<td class='manager_employee-table-salary' data-uid='" . $emp["uid"] . "'>" . openssl_decrypt($emp["u_salary_stats"], "AES-256-CBC", $_SESSION["pwd"], 0, $_SESSION["iv"]) . "THB</td>";
          echo "<td class='manager_employee-table-work' data-uid='" . $emp["uid"] . "'>" . openssl_decrypt($emp["u_work_stats"], "AES-256-CBC", $_SESSION["pwd"], 0, $_SESSION["iv"]) . "</td>";
          echo "<td class='manager_employee-table-start' data-uid='" . $emp["uid"] . "'>" . openssl_decrypt($emp["u_start_stats"], "AES-256-CBC", $_SESSION["pwd"], 0, $_SESSION["iv"]) . "</td>";
          if($emp["u_type"] == 0)
          {
            echo "<td class='manager_employee-table-type' data-uid='" . $emp["uid"] . "'>Manager</td>";
          }
          else if($emp["u_type"] == 1)
          {
            echo "<td class='manager_employee-table-type' data-uid='" . $emp["uid"] . "'>Waiter</td>";
          }
          else if($emp["u_type"] == 2)
          {
            echo "<td class='manager_employee-table-type' data-uid='" . $emp["uid"] . "'>Cook</td>";
          }
          else if($emp["u_type"] == 3)
          {
            echo "<td class='manager_employee-table-type' data-uid='" . $emp["uid"] . "'>Part-time</th>";
          }
          echo "<td><div class='manager_resign-btn' data-uid='" . $emp["uid"] . "'>Resign</div></td>";
          echo "</tr>";
        }

        ?>
      </tbody>
    </table>
    <div class="manager_content-manemp-add">
      <div id="manager_employee-add-btn">Add new employee</div>
    </div>
  </div>
  <div class="manager_content-manitem manager_content-item" id="manager_content-manitem">
    <h1 class="manager_content-heading1">Manage items</h1>
    <table id="item-table">
      <thead>
        <th>Name</th>
        <th>Price</th>
        <th>Cooking time</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php

        require_once "db.php";
        $query = "SELECT * FROM items WHERE shop_id=:shop_id";
        $getItemsStmt = $pdo->prepare($query);
        $getItemsStmt->execute([
          "shop_id" => $_SESSION["user_info"]["shop_id"]
        ]);
        $items = $getItemsStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $it) {
          echo "<tr>";
          echo "<td class='manager_item-table-name' data-itemid='" . $it["item_id"] . "'>" . $it["item_name"] . "</td>";
          echo "<td class='manager_item-table-price' data-itemid='" . $it["item_id"] . "'>" . number_format($it["item_price"],0,'.','') . "THB</td>";
          echo "<td class='manager_item-table-time' data-itemid='" . $it["item_id"] . "'>" . $it["item_time"] . "min</td>";
          echo "<td><div class='manager_remove-btn' data-itemid='" . $it["item_id"] . "'>Remove</div></td>";
          echo "</tr>";
        }

        ?>
      </tbody>
    </table>
    <div class="manager_content-manitem-add">
      <div id="manager_item-add-btn">Add new item</div>
    </div>
  </div>
</div>
<script class="spa_js_import" data_src="req/js/manager.js"></script>
