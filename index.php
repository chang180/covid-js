<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="COVID-19 trend Taiwan">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="張建文">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COVID-19</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.css" />

    <script src="jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.js"></script>
</head>

<body>
    <h1 class="text-center">臺灣COVID-19趨勢圖(第18週起)</h1>
    <div class="float-right" id="nowdate"></div>
    <div id="taiwan"><canvas id="Chart" height="80" width="400"></canvas></div>
    <select id="city">
    </select>
    <select id="county">
    </select>
    <h1 class="text-center"><span id="countyText"></span>COVID-19趨勢圖(第18週起)</h1>
    <div id="dist"><canvas id="myChart" height="80" width="400"></canvas></div>
</body>
<footer>
    <h3 class="text-center">資料來源</h3>
    <div class="text-center"><a href="https://data.gov.tw/dataset/118038"
            target="_blank">疫管署-地區年齡性別統計表-嚴重特殊傳染性肺炎(以週為單位)</a></div>
</footer>
<script>
    // let city = "新北市"
    // let county = "永和區"
    let cities, counties, city, county, myChart, myChart2
    const nowdate = new Date();
    const formatDate = (nowdate) => {
        let formatted_date = nowdate.getFullYear() + "年" + (nowdate.getMonth() + 1) + "月" + nowdate.getDate() + "日"
        return formatted_date;
    }
    $('#nowdate').html('資料更新時間：'+formatDate(nowdate))
    // $.getJSON("./taipei.json", e => {
    $.getJSON("./taiwan_districts.json", e => {
        for (item in e) {
            cities += `<option>${e[item].name}</option>`
        }
        counties = ''
        $("#city").html(cities)
        city = e[0].name
        for (item in e[0].districts) counties += `<option>${e[0].districts[item].name}</option>`
        // console.log(e[0].districts)
        // console.log(counties)
        $("#county").html(counties)
        county = e[0].districts[0].name
        $('#countyText').html(city + county)
        $("#county").on('change', () => {
            city = $('#city').val()
            county = $('#county').val()
            $('#countyText').html(city + county)
            draw()
            // console.log('hi3')
        })
        // $('#dist #myChart').html('<canvas id="myChart" height="80" width="400">')
        draw()
        // console.log('hi4')
    })

    $("#city").on('change', () => (
        // console.log($("#city").val())
        // $.getJSON("./taipei.json", e => {
        $.getJSON("./taiwan_districts.json", e => {
            counties = ''
            for (item in e)
                if (e[item].name == $("#city").val()) arr = e[item].districts
            // counties += `<option>${e[item].districts[item].name}</option>`
            // console.log(arr)
            for (item in arr) counties += `<option>${arr[item].name}</option>`
            // console.log(counties)
            city = $('#city').val()
            county = arr[0].name
            $("#county").html(counties)
            $('#countyText').html(city + county)

            // 不須重複註冊事件
            // $("#county").on('change', () => {
            //     // city = $('#city').val()
            //     // county = $('#county').val()
            //     $('#countyText').html(city + county)
            //     draw()
            //     console.log('hi1')
            // })
            draw()
        })
    ))

    function draw() {
        // console.log(myChart2)
        if (myChart != null) myChart.destroy()
        if (myChart2 != null) myChart2.destroy()
        // console.log(myChart2)
        $.getJSON("./curlget.php", e => {
            let data = e
            // console.log(data)
            var dataTotal = data.length; //資料長度
            var obj = {} //新物件
            var all = {} //全國性資料
            for (var i = 0; i < dataTotal; i++) {
                if (all[data[i]['發病週別']]) {
                    all[data[i]['發病週別']].value = Number(all[data[i]['發病週別']].value) + Number(data[i]['確定病例數']);
                } else {
                    all[data[i]['發病週別']] = {
                        value: Number(data[i]['確定病例數'])
                    }
                }
                if (data[i]['縣市'] == city && data[i]['鄉鎮'] == county) {
                    // console.log("區域:" + data[i]['縣市'] + " 鄉鎮:" + data[i]['鄉鎮'] + " 發病週別:" + data[i]['發病週別'] + " 確定病例數:" + data[i]['確定病例數']);
                    // for (var i in data) {
                    if (obj[data[i]['發病週別']]) {
                        obj[data[i]['發病週別']].value = Number(obj[data[i]['發病週別']].value) + Number(data[i]['確定病例數']);
                    } else {
                        obj[data[i]['發病週別']] = {
                            value: Number(data[i]['確定病例數']) ?? 0
                        }
                    }
                    // console.log(obj)
                }
            }
            // console.log(all)
            // 無確診週數補0進去
            let last = Object.keys(all)[Object.keys(all).length - 1]
            for (let i = 1; i <= last; i++) all[i] = all[i] ?? {
                value: 0
            }
            for (let i = 1; i <= last; i++) {
                // console.log(i)
                obj[i] = obj[i] ?? {
                    value: 0
                }
            }
            // console.log(i)
            // console.log(all)
            // console.log(obj)


            var label = []
            var number = []
            // obj.forEach(element => {
            //     // label.push(key)
            //     // number.push(element.value)
            //     console.log(element)
            // });
            for (var i in all) {
                label.push(i)
                number.push(all[i].value)
            }
            label = label.slice(17)
            number = number.slice(17)
            // console.log(label)
            // console.log(number)
            var ctx = document.getElementById('Chart');
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: label,
                    datasets: [{
                        label: '當週發病人數',
                        data: number,
                        backgroundColor: 'green',
                        borderColor: 'darkblue',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: false
                            }
                        }]
                    }
                }
            });
            var label2 = []
            var number2 = []
            // console.log(obj)
            for (var i in obj) {
                label2.push(i)
                number2.push(obj[i].value)
            }
            // console.log(number)
            label2 = label2.slice(17)
            number2 = number2.slice(17)
            var ctx2 = document.getElementById('myChart');
            // console.log(myChart2)
            myChart2 = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: label2,
                    datasets: [{
                        label: county + '當週發病人數',
                        data: number2,
                        backgroundColor: 'darkblue',
                        borderColor: 'green',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // console.log(myChart)
            // console.log(myChart2)
        })
    }
</script>

</html>