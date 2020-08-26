<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Covid 19 Interactive Map Online</title>
    <!-- Adding Jquery -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
          integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
          crossorigin=""/>
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
            integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
            crossorigin=""></script>
    <!-- <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script> -->
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <style>
        #map {
            height: 600px;
        }

        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255,255,255,0.8);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 5px;
        }
        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

        .header{
            background: #121290;
            color: white;
        }

        h2{
            margin-top: 0;
            padding-bottom: 1.20rem;
        }

        tbody{
            display: block;
            height: 450px;
            overflow: auto;
        }

        thead{
            width: calc(100% -1em);
        }

        table{
            width: 100%;
        }

        td, th{
            padding: 1px 5px;
        }

        .fixcases{
            text-align: right;
            height: 0px;
            position: relative;
            top: -24px;
        }
    </style>
</head>
<body>

<div class="header">
    <h2 class="center-align">United States Covid-19 Data</h2>
</div>

<?php $total = 0; $deaths = 0; $recovered = 0;  ?>
@foreach($arraySubtotal as $arr)
    <?php
        $total = $total + $arr["confirmed"];
        $deaths = $deaths + $arr["deaths"];
        $recovered = $recovered + $arr["recovered"];
    ?>
@endforeach

<div class="container">
    <div class="row">
        <div class="col s6 m4">
            <div class="card-panel">
                <span class="black-text">
                    <?php echo "<h5>Total US Cases: ".number_format($total)."</h5>"?>
                </span>
            </div>
        </div>

        <div class="col s6 m4">
            <div class="card-panel">
                <span class="black-text">
                    <?php echo "<h5>Total US Deaths: ".number_format($deaths)."</h5>"?>
                </span>
            </div>
        </div>

        <div class="col s6 m4">
            <div class="card-panel">
                <span class="black-text">
                    <?php echo "<h5>Total Recovered: ".number_format($recovered)."</h5>"?>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <!--<div class="col s12">
            <h2>United States Covid-19 Data</h2>
        </div>-->
        <div id="map" class="col s9"></div>
        <div class="col s3">

            <h4>Date selected </h4><input type="text" class="datepicker">

            <div class="responsive-table">
                <table class="striped">
                    <thead>
                        <tr>
                            <th>State <div class="fixcases">Cases</div></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($arraySubtotal as $arr)
                        <tr>
                                <td>{{$arr["name"]}}</td>
                                <td>{{$arr["confirmed"]}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p>COVID-19 Data Repository by the Center for Systems Science and Engineering (CSSE) at Johns Hopkins University</p>

</div>

<script type="text/javascript">

    var t = "pk.eyJ1IjoiY2hhcmx5Z2FyY2lhIiwiYSI6ImNpbnl0aG5jaDE4OWV1aWtqdnI5MDdraGkifQ.pUGx97qxozeYMYidl_B09Q";
    var map = L.map('map').setView([37.8, -96], 4);

    var geojsonFeature = @json($arrayJson, JSON_PRETTY_PRINT);

    /* Inicializar el datepicker */
    var datepicker = $('.datepicker').datepicker({format : 'yyyy-mm-dd'});

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+t+'', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>, <a href="https://github.com/charlygg">Charly G</a>',
        maxZoom: 18,
        id: 'mapbox/light-v9',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: t
    }).addTo(map);

    L.geoJSON(geojsonFeature).addTo(map);

    function getColor(d){
        return d > 500000 ? '#800026' :
                d > 250000  ? '#BD0026' :
                d > 100000  ? '#E31A1C' :
                d > 50000  ? '#FC4E2A' :
                d > 25000   ? '#FD8D3C' :
                d > 10000   ? '#FEB24C' :
                d > 5000   ? '#FED976' :
                    '#FFEDA0';
    }

    function style(feature){
        return{
            fillColor: getColor(feature.covid_cases.confirmed),
            weight: 2,
            opacity: 1,
            color: 'white',
            dashArray: '3',
            fillOpacity: 0.7
        };
    }

    L.geoJSON(geojsonFeature, {style: style}).addTo(map);

    function highlightFeature(e){
        var layer = e.target;

        layer.setStyle({
            weight: 5,
            color: "#666",
            dashArray: '',
            fillOpacity: 0.7
        });

        if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
            layer.bringToFront();
        }

        info.update(layer.feature.covid_cases);
    }

    function resethighlightFeature(e){
        geojson.resetStyle(e.target);
        info.update();
    }

    function zoomToFeature(e) {
        map.fitBounds(e.target.getBounds());
    }

    function onEachFeature(feature, layer) {
        layer.on({
            mouseover: highlightFeature,
            mouseout: resethighlightFeature,
            click: zoomToFeature
        });
    }

    geojson = L.geoJson(geojsonFeature, {
        style: style,
        onEachFeature: onEachFeature
    }).addTo(map);


    /***
     * Código para agregar los Diálogos Modales en el Mapa
     * Show a basic dialog info when mouse hover over on a state
     */
    var info = L.control();

    info.onAdd = function (map){
        this._div = L.DomUtil.create('div','info');
        this.update();
        return this._div;
    }

    info.update = function(covid_cases){
        this._div.innerHTML = '<h5>Casos de Covid-19</h5>' + (covid_cases ? '<p>Estado: '+ covid_cases.name +  '</p>' +  ' <b> ' + 'Confirmados: ' +
            '</b><br>' + covid_cases.confirmed + ' people' : 'Hover over on a state' );
    }

    info.addTo(map);

</script>

</body>

</html>
