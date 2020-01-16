import Chart from 'chart.js';

$(document).ready(() => {
  function moonData() {
    return $.ajax({
      dataType: "json",
      url: "/api/statistics/moon_mappers"
    });
  }

  function marsData() {
    return $.ajax({
      dataType: "json",
      url: "/api/statistics/mars_mappers"
    });
  }

  function mercuryData() {
    return $.ajax({
      dataType: "json",
      url: "/api/statistics/mercury_mappers"
    });
  }

  function vestaData() {
    return $.ajax({
      dataType: "json",
      url: "/api/statistics/vesta_mappers"
    });
  }

  function displayErrors() {
    $('#stats').fadeOut("slow");
    $('#statsError').fadeIn("slow");
  }

  function getAxisValues(value, index, values) {
    // if (value < 1000)
    //   return `${value}`;
    // else if (value < 1000000)
    //   return `${value / 1000}K`
    // else return `${value / 1000000}M`;

    return `${value}%`;
  }

  function hasErrors(jqHXR) {
    return jqHXR.hasOwnProperty('errors');
  }

  var moon = "";
  var mars = "";
  var mercury = "";
  var vesta = "";
  var detectives = "";

  $.getJSON('/api/statistics/moon_mappers', (moon_data) => {
    moon = moon_data;
    $.getJSON('/api/statistics/mars_mappers', (mars_data) => {
      mars = mars_data;
      $.getJSON('/api/statistics/mercury_mappers', (mercury_data) => {
        mercury = mercury_data;
        $.getJSON('/api/statistics/vesta_mappers', (vesta_data) => {
          vesta = vesta_data;
          $.getJSON('/api/statistics/image_detectives', (detectives_data) => {
            detectives = detectives_data;

            let imagesCanvas = $('#images');
            let asOf = moon.moon_mappers["as-of"];
            let date = asOf.date;
            let tz = asOf.timezone;
            $('#as-of').text(`As of: ${date} ${tz}`);


            // let imagesData = [
            //   moon['moon_mappers']['images'],
            //   mars['mars_mappers']['images'],
            //   mercury['mercury_mappers']['images'],
            //   vesta['vesta_mappers']['images']
            // ];
            let totals = [
              moon.moon_mappers['total'],
              mars.mars_mappers['total'],
              mercury.mercury_mappers['total'],
              vesta.vesta_mappers['total'],
              detectives.image_detectives['total']
            ];
            console.log(totals);
            let imagesFinished = [
              (moon.moon_mappers['finished-images'] / totals[0]) * 100,
              (mars.mars_mappers['finished-images'] / totals[1]) * 100,
              (mercury.mercury_mappers['finished-images'] / totals[2]) * 100,
              (vesta.vesta_mappers['finished-images'] / totals[3]) * 100,
              (detectives.image_detectives['finished-images'] / totals[4]) * 100
            ];
            console.log(imagesFinished);
            let imagesUnfinished = [
              (moon.moon_mappers['unfinished-images'] / totals[0]) * 100,
              (mars.mars_mappers['unfinished-images'] / totals[1]) * 100,
              (mercury.mercury_mappers['unfinished-images'] / totals[2]) * 100,
              (vesta.vesta_mappers['unfinished-images'] / totals[3]) * 100,
              (detectives.image_detectives['unfinished-images'] / totals[4]) * 100
            ];
            console.log(imagesUnfinished);
            Chart.defaults.global.defaultFontSize = 20;
            let imagesChartOptions = {
              scales: {
                xAxes: [{
                  ticks: {
                    // beginAtZero: true,
                    // suggestedMin: 0,
                    callback: getAxisValues,
                    // stepSize: 5000,
                    // max: 100,
                  },
                  position: 'bottom',
                  // type: 'logarithmic',
                  type: 'linear',
                  stacked: true,
                }],
                yAxes: [{
                  gridLines: {
                    display: false,
                    color: "#fff",
                    zeroLineColor: "#fff",
                    zeroLineWidth: 0
                  },
                  ticks: {},
                  stacked: true
                }]
              },
              legend: {
                labels: {
                  // fontSize: '11px',
                  fontColor: 'black',
                  fontFamily: 'roboto',
                }
              },
              tooltips: {
                enabled: true,
                mode: 'index',
                callbacks: {
                  label: (tooltipItem, data) => {
                    console.log(tooltipItem);
                    console.log(data);
                    let val = tooltipItem.xLabel.toLocaleString(
                      undefined, // use a string like 'en-US' to override browser locale
                      {
                        maximumFractionDigits: 2,
                        style: "decimal"
                      }
                    )
                    let index = tooltipItem.datasetIndex;
                    let label = data.datasets[index].label;
                    return `${label}: ${val}%`;
                  }
                }
              }
            };
            Chart.defaults.global.responsive = true;
            Chart.defaults.global.maintainAspectRatio = true;
            Chart.defaults.global.onResize = (chart, size) => {
              if (size.width < 768) {
                chart.config.data.labels = ["Moon", "Mars", "Mercury", "Vesta", "Earth"];
                chart.legend.options.labels.padding = 15;
              } else {
                chart.config.data.labels = ["Moon Mappers", "Mars Mappers", "Mercury Mappers", "Vesta Mappers", "Image Detectives"];
                chart.legend.options.labels.padding = 10;
              }
              console.log(chart);
            };
            let labels = [];
            if ($(window).width() < 768) {
              labels = ["Moon", "Mars", "Mercury", "Vesta", "Earth"];
            } else {
              labels = ["Moon Mappers", "Mars Mappers", "Mercury Mappers", "Vesta Mappers", "Image Detectives"];
            }
            let imagesChart = new Chart(imagesCanvas, {
              type: 'horizontalBar',
              // type: 'pie',
              data: {
                label: "Data",
                labels: labels,

                datasets: [{
                    label: "Finished Images",
                    data: imagesFinished,
                    backgroundColor: "rgba(63,103,126,1)",
                    hoverBackgroundColor: "rgba(50,90,100,1)"
                  },
                  /* {
                                    label: "Unfinished Images",
                                    data: imagesUnfinished,
                                    backgroundColor: "rgba(163,103,126,1)",
                                    hoverBackgroundColor: "rgba(140,85,100,1)"
                                  }*/
                ]
              },
              options: imagesChartOptions,
            });
            $("#loader").fadeOut();
          });
        });
      });
    });
  });
});
