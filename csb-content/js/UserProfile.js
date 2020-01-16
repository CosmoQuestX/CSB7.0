import Chart from 'chart.js';

$(document).ready(() => {
  let userName = window.location.pathname.replace("/user/", "");
  Chart.defaults.global.defaultFontSize = 40;
  let statsXHR = $.get(`/api/statistics/${userName}`)
                .done((result) => {
                  if(result.errors)
                    return displayErrors();
                if(result == null || result.data == null) return;
                  console.log(result.data);
                  let moonStats = result.data["moon_mappers"];
                  let marsStats = result.data["mars_mappers"];
                  let mercuryStats = result.data["mercury_mappers"];
                  let vestaStats = result.data["vesta_mappers"];

                  if(!$('#marks').length)
                    return displayErrors();
                  let marksCTX = $('#marks');
                  let verifiedMarksCTX = $('#verifiedMarks');
                  let imagesCTX = $('#images');

                  let marksChart = new Chart(marksCTX, {
                    type: 'horizontalBar',
                    data: {
                      labels: ["Moon", "Mars", "Mercury", "Vesta"],
                      datasets: [{
                        label: "Total Marks",
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 1,
                        data: [
                          moonStats[0],
                          marsStats[0],
                          mercuryStats[0],
                          vestaStats[0]
                        ]
                      }]
                    },
                    options: {
                    }
                  });
                  if(moonStats[1] != 0 && marsStats[1] != 0 && mercuryStats[1] != 0 && vestaStats[1] != 0)
                  {
                    let verifiedMarksChart = new Chart(verifiedMarksCTX, {
                      type: 'horizontalBar',
                      data: {
                        labels: ["Moon", "Mars", "Mercury", "Vesta"],
                        datasets: [{
                          label: "Verified Marks",
                          backgroundColor: 'rgba(54, 162, 235, 0.2)',
                          borderWidth: 1,
                          data: [
                            moonStats[1],
                            marsStats[1],
                            mercuryStats[1],
                            vestaStats[1]
                          ]
                        }]
                      },
                      options: {
                      }
                    });
                  }

                  let imagesChart = new Chart(imagesCTX, {
                    type: 'horizontalBar',
                    data: {
                      labels: ["Moon", "Mars", "Mercury", "Vesta"],
                      datasets: [{
                        label: "Images Completed",
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 1,
                        data: [
                          moonStats[2],
                          marsStats[2],
                          mercuryStats[2],
                          vestaStats[2]
                        ]
                      }]
                    },
                    options: {
                    }
                  });
                })
                .fail((result) => {
                  return displayErrors();
                });
});


function displayErrors()
{
  $('#marks').remove();
  $('#verifiedMarks').remove();
  $('#images').remove();
  $('#statsError').toggle();
}
