/** Core functions for chart visualization with chat.js
 * Changelog
 * 10.02.2021, rk1
 *  - removed fixed y-scale fro temp
 * /

/**
 * Draw the monthly chart
 * @param {*} chart_ctx Chart Canvas to get by document.getElementById('myChart');
 * @param {*} arr_label array with labels
 * @param {*} arr_data_read array with data for read values
 * @param {*} arr_data_meas array with data for measured values
 * @param {*} temp_data_meas array with data for measured temperature values
 */
function drawChartMonthly(chart_ctx, arr_label,arr_data_read,arr_data_meas, temp_data_meas) {// draw the chart
    myChartMon = new Chart(chart_ctx, {
        type: 'bar',
        title: 'Stromverbrauch Heizung monatlich',
        data: {
            labels: arr_label,
            datasets: [{
                label:'abgelesen',
                yAxisID: 'kw',
                data: arr_data_read,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',  // blue
                borderWidth: 1,
                order: 1
                }, {
                label:'gemessen',
                yAxisID: 'kw',
                data: arr_data_meas,
                backgroundColor: 'rgba(255, 159, 64, 0.5)',  // orange
                borderWidth: 1,                   
                order: 1
                }, {
                label:'Temperatur',
                yAxisID: 'temp',
                data: temp_data_meas,
                type: 'line',
                borderColor: 'rgba(255, 99, 132, 1)',       // red
                backgroundColor: 'rgba(255, 255, 255, 0)',  // white - transparent
                borderWidth: 1,
                order: 2   
                }
            ],
        },
        options: {
            scales: {
                yAxes: [{
                    id: 'kw',
                    label: 'kW',
                    type: 'linear',
                    position: 'left',
                    ticks: { min: 0},
                    beginAtZero: true
                  }, {
                    id: 'temp',
                    label: '°C',
                    type: 'linear',
                    position: 'right',
                    // ticks: { max: 30, min: -5},
                    beginAtZero: true
                  }]
            }
        }
    });
    return;
}

/**
 * Draw the S0Counter chart
 * @param {*} chart_ctx Chart Canvas to get by document.getElementById('myChart');
 * @param {*} arr_label array with labels
 * @param {*} arr_data  array with data for kWh values
 * @param {*} temp_data array with data for temperature values
 */
function drawChartS0(chart_ctx, arr_label, arr_data, temp_data) {// draw the chart
    myChartS0 = new Chart(chart_ctx, {
        type: 'bar',
        title: 'Stromverbrauch Heizung',
        data: {
            labels: arr_label,
            datasets: [{
                label:'Stromverbrauch',
                yAxisID: 'kwh',
                data: arr_data,
                backgroundColor: 'rgba(255, 159, 64, 0.5)',  // orange
                borderWidth: 1,
                order: 1
                }, {
                label:'Temperatur',
                yAxisID: 'temp',
                data: temp_data,
                type: 'line',
                borderColor: 'rgba(255, 99, 132, 1)',       // red
                backgroundColor: 'rgba(255, 255, 255, 0)',  // white - transparent
                borderWidth: 1,
                order: 2   
                }
            ],
        },
        options: {
            scales: {
                yAxes: [{
                    id: 'kwh',
                    label: 'kWh',
                    type: 'linear',
                    position: 'left',
                    ticks: { min: 0},
                    beginAtZero: true
                  }, {
                    id: 'temp',
                    label: '°C',
                    type: 'linear',
                    position: 'right',
                    // ticks: { max: 30, min: -5},
                    beginAtZero: true
                  }]
            }
        }
    });
    return;
}

/**
 * Draw the S0Counter chart with temperature as boxplot
 * @param {*} chart_ctx Chart Canvas to get by document.getElementById('myChart');
 * @param {*} arr_label array with labels
 * @param {*} arr_data  array with data for kWh values
 * @param {*} temp_data array with data for temperature values (array with values for each day)
 */
function drawChartS0Bp(chart_ctx, arr_label, arr_data, temp_data) {// draw the chart
    myChartS0Bp = new Chart(chart_ctx, {
        type: 'bar',
        title: 'Stromverbrauch Heizung',
        data: {
            labels: arr_label,
            datasets: [{
                label:'Stromverbrauch',
                yAxisID: 'kwh',
                data: arr_data,
                backgroundColor: 'rgba(255, 159, 64, 0.5)',  // orange
                borderWidth: 1,
                order: 1
                }, {
                label:'Temperatur',
                yAxisID: 'temp',
                data: temp_data,
                type: 'boxplot',
                borderColor: 'rgba(255, 99, 132, 1)',       // red
                //backgroundColor: 'rgba(255, 255, 255, 0)',  // white - transparent
                outlierColor: '#999999',
                borderWidth: 1,
                order: 2   
                }
            ],
        },
        options: {
            scales: {
                yAxes: [{
                    id: 'kwh',
                    label: 'kWh',
                    type: 'linear',
                    position: 'left',
                    ticks: { min: 0},
                    beginAtZero: true
                  }, {
                    id: 'temp',
                    label: '°C',
                    type: 'linear',
                    position: 'right',
                    // ticks: { max: 30, min: -5},
                    beginAtZero: true,
                    responsive: true,
                  }]
            }
        }
    });
    return;
}

function drawChartS0BpTest(chart_ctx, arr_label, arr_data, temp_data) {// draw the chart
    myChartS0Bp = new Chart(chart_ctx, {
      type: 'boxplot',
      labels: arr_label,
      datasets: [{
          label:'Temperatur',
          data: temp_data,
          borderColor: 'rgba(255, 99, 132, 1)',       // red
          //backgroundColor: 'rgba(255, 255, 255, 0)',  // white - transparent
          outlierColor: '#999999',
          borderWidth: 1,
          order: 2   
      }],
      options: {
        responsive: true,
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Chart.js Box Plot Chart',
        },
        scales: {
          xAxes: [
            {
              // Specific to Bar Controller
              categoryPercentage: 0.9,
              barPercentage: 0.8,
            },
          ],
        },
      },
    });
}

/**
 * Draw the month compare chart
 * @param {*} chart_ctx Chart Canvas to get by document.getElementById('myChart');
 * @param {*} arr_label array with labels
 * @param {*} label_m1 name as label for month1
 * @param {*} arr_data_m1 array with data for month1
 * @param {*} temp_data_m1 array with data for measured temperature values of month1
 * @param {*} label_m2 name as label for month2 
 * @param {*} arr_data_m2 array with data for month2
 * @param {*} temp_data_m2 array with data for measured temperature values of month2
 */
function drawChartCompare(chart_ctx, arr_label,label_m1,arr_data_m1,temp_data_m1,label_m2,arr_data_m2,temp_data_m2) {// draw the chart
    // remove the year and month from each label in arr_label
    var arr_label_days = arr_label.map(function(item) {
                            return item.substr(item.lastIndexOf('-')+1);
                            });
    myChartMon = new Chart(chart_ctx, {
        type: 'bar',
        title: 'Stromverbrauch Heizung - Monatsvergleich',
        data: {
            labels: arr_label_days, // arr_label
            datasets: [{
                label: label_m1,
                yAxisID: 'kw',
                data: arr_data_m1,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',  // blue
                borderWidth: 1,
                order: 1
                }, {
                label: label_m2,
                yAxisID: 'kw',
                data: arr_data_m2,
                backgroundColor: 'rgba(255, 159, 64, 0.5)',  // orange
                borderWidth: 1,                   
                order: 1
                }, {
                label:'Temperatur '.concat(label_m1),
                yAxisID: 'temp',
                data: temp_data_m1,
                type: 'line',
                borderColor: 'rgba(255, 99, 132, 0.9)',       // red
                backgroundColor: 'rgba(255, 255, 255, 0)',  // white - transparent
                borderWidth: 1,
                order: 2   
                }, {
                label:'Temperatur '.concat(label_m2),
                yAxisID: 'temp',
                data: temp_data_m2,
                type: 'line',
                borderColor: 'rgba(153, 102, 255, 0.9)',    // purple
                backgroundColor: 'rgba(255, 255, 255, 0)',  // white - transparent
                borderWidth: 1,
                order: 2   
                }
            ],
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                yAxes: [{
                    id: 'kw',
                    label: 'kWh',
                    type: 'linear',
                    position: 'left',
                    ticks: { min: 0},
                    beginAtZero: true
                  }, {
                    id: 'temp',
                    label: '°C',
                    type: 'linear',
                    position: 'right',
                    // ticks: { max: 30, min: -5},
                    beginAtZero: true
                  }]
            }
        }
    });
    return;
}

/**
 * toggles visibility of a given div element
 * @param {*} div_ctx the div element to toggle
 */
function toogleVisibility(div_ctx) {
    if (div_ctx.style.display === "none") {
        div_ctx.style.display = "block";
    } else {
        div_ctx.style.display = "none";
    }
}