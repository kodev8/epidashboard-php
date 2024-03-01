
<?php

    require_once component('large-card.php');

    $labels = array(array("SampleX", "SampleY"));
    $empty = array_map(function() {
                        return array(" ", rand(3,10));
                      }, 
                      range(0, 4)
                    );
    $default_data = array_merge($labels, $empty);
    $json_default_data = json_encode($default_data)
  ?>



<div  id="group" class="card-container">
    <?php

    $population = large_card(
            'Population Chart', 
            'Breakdown of the Population',
            '/populations#piechart',
            'fa-solid fa-chart-pie',
            '<canvas id="defpie"  class="def-chart"></canvas>'
          );

    $attendance = large_card(
            'Attendance Chart', 
            "Let's see the Average Attendance per course",
            '/populations#barchart',
            'fa-solid fa-chart-column',
            '<canvas id="defbar" class="def-chart"></canvas>'
          );

    echo $population;
    echo $attendance;

    ?>
</div>

<script type="text/javascript">
 

    const default_data = <?= $json_default_data ?>
      
      const defPie = document.getElementById('defpie');
      const defBar = document.getElementById('defbar');
      const defConfig = {
          type: 'pie',
          data: {
            labels: ['', '', '', '', ''],
            datasets: [{
              label: '',
              data: [10, 8, 3, 5, 2],
              borderWidth: 1
            }]
          },
          options: {
            resposive: true,
              plugins: {
                legend: {
                  display: false
                },
                tooltip: {
                  enabled: false
                }
              },
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        };

      new Chart(defPie,defConfig);
      

      defBarConfig = defConfig
      defBarConfig = {...defConfig, type : 'bar'}
      new Chart(defBar,defBarConfig);

    

</script>





