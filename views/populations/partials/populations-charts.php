


<h2 id="charts" class="section-header">Charts</h2>

    <div class="chart-container">
        <canvas id="piechart"></canvas>
    </div>

    <div class="chart-container">
        <canvas id="barchart"></canvas>
    </div>



  <?php

?>

<script type="text/javascript">
//chart js for tables

const populationsTable = document.getElementById('epita_populations');
const tableCheckbox = document.getElementById('show-table')
tableCheckbox.addEventListener('click', ()=> {
    populationsTable.parentNode.classList.toggle('hide')
})

const populations = <?php echo $json_populations ?>;
const names = populations.map((pop) => pop['sudo'])
const popCount = populations.map((pop)=> pop['population_count'] )
const totalSessions = populations.map((pop)=> pop['total_sessions'] )
const attendedSessions = populations.map((pop)=> pop['total_attended'] )


// Population Pie
const pieCanvas = document.getElementById('piechart');
const attendanceCanvas= document.getElementById('barchart');

const pieCheckbox = document.getElementById('show-pie')
const attCheckbox = document.getElementById('show-bar')



const chartLabel = document.getElementById('charts')
function hideLabel(){
    if  (attCheckbox.checked == false && pieCheckbox.checked == false ){
        chartLabel.classList.add('hide')
    }else{
        chartLabel.classList.remove('hide')
    }
}

pieCheckbox.addEventListener('click', ()=> {
    pieCanvas.parentNode.classList.toggle('hide')
    hideLabel()
})

attCheckbox.addEventListener('click', ()=> {
    attendanceCanvas.parentNode.classList.toggle('hide')
    hideLabel()
})


const populationPieChart = new Chart(pieCanvas, {
    type:'doughnut', 
    data: {
    labels:names,
    datasets: [{

        data: popCount,
        hoverOffset: 20
    }]},
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: 30,
        },
        plugins: {
            legend: {
                labels: {
                    font: {
                        size: 14,
                        color: 'rgba(255, 0, 0)'
                    }
                }
            },
            title: {
                display: true,
                text: 'POPULATION BREAKDOWN'
            },
        },

    }
})

// Attendance
const attendanceChart = new Chart(attendanceCanvas, {
      type: 'bar',
      data: {
        labels: names,
        datasets: [
          {
            label: 'Total Sessions',
            data: totalSessions,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            yAxisID: 'y-axis-1',
          },
          {
            label: 'Attended Sessions',
            data: attendedSessions,
            hoverBackgroundColor: 'rgba(255, 192, 192, 0.6)',
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            yAxisID: 'y-axis-1',
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,

        layout: {
            padding: 30,
        },

        plugins: {
            title: {
                display: true,
                text: 'AVERAGE ATTENDANCE PER PROGRAM',
                padding: {
                    bottom: 30
                }
            },
        },
        scales: {
          y: 
            {
            //   id: 'y-axis-1',
            //   type: 'linear',
              position: 'left',
              ticks: {
                display: false,
                // color: 'red'
                // beginAtZero: true,
                // stepSize: 10,
                    },

              title: {
                display: true,
                text: 'Sessions and Attendance'
                    }
            },
          
            x:{
                display: true,
                title: {
                    display: true,
                    text: 'Populations',
                    // color: 'red',
                    font: {
                        size: 18,
                    },
                    padding: {top: 40, left: 0, bottom: 0}
                    }
                },
        },
      },
    });


    const goToPopulation = (event, chart) => {
    const activeSegment = chart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
    if (activeSegment.length > 0) {
        const popIndex = activeSegment[0].index;
   
        const population= populations[popIndex];
        const link = '/populations/' + population['slug'];
        window.location.href = link;
      }
    }
    
    attendanceChart.canvas.addEventListener('click', (event) => goToPopulation(event, attendanceChart))
    populationPieChart.canvas.addEventListener('click', (event) => goToPopulation(event, populationPieChart))
    

</script>
