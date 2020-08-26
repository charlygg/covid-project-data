<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CovidController extends Controller
{
    //

    public function index($date = null){

        /** Leer el contenido del GeoJson de los mapas |
         *  Datasorce
         *  -  https://github.com/CSSEGISandData/COVID-19/blob/master/csse_covid_19_data/csse_covid_19_daily_reports_us/08-13-2020.csv
         *  -  https://coronavirus-tracker-api.herokuapp.com/v2/locations
         */

        $geoJson = file_get_contents(resource_path('geojson/US.json'));
        $array = json_decode($geoJson, true);

        /* Obtener el contenido diario de casos de covid | Get the content of the covid from MySQL */
        $dbInfo = DB::table('states')
                        ->join("covid_cases_us", "states.state_code", "=", "covid_cases_us.state_code")
                        ->select("states.state","covid_cases_us.*")
                        ->where('covid_cases_us.date',"=",$date)
                        ->orderBy("covid_cases_us.confirmed","desc")
                        ->get();

        $exp = array("Database" => $dbInfo);

        /* Fusionar la información de la Base de Datos de Covid19 con los datos del GeoJson */
        /* Merge the information from the database Covid19 MySQL with the data from GeoJson */
        $i = 0;
        $conteoPorEstado = array();
        foreach($array["features"] as $ar){

            $estado_json = $ar["properties"]["name"];
            $found = false;
            foreach($exp as $d){

                foreach($d as $v){

                    if(($estado_json == $v->state) && $found == false) {

                        /* Insertar los datos del covid en el array */
                        $array['features'][$i]['covid_cases'] = array( 'name'       => $v->state,
                                                                        'confirmed' => $v->confirmed,
                                                                        'deaths'    => $v->deaths,
                                                                        'recovered' => $v->recovered);

                        $conteoPorEstado[] =  array( 'name'       => $v->state,
                                                     'confirmed'  => $v->confirmed,
                                                     'deaths'     => $v->deaths,
                                                     'recovered'  => $v->recovered);

                        $found = true;

                    } elseif($found == false) {

                        /* Insertar valores vacios para que no falle el mapa interactivo */
                        $array['features'][$i]['covid_cases'] = array(   'name'      => $estado_json,
                                                                         'confirmed' => '0',
                                                                         'deaths'    => '0',
                                                                         'recovered' => '0');
                    }
                }
            }

            $i++;
        }

        return view('index', array('arrayJson' => $array, 'arraySubtotal' => $conteoPorEstado));
    }

     public function buscarInfo(){
        /* TODO: Create a basic form in which we can find information in the github api
                 to insert into local MySQL Server to display in the index page
         */
        return view('buscar');
    }
    /*
     *
     *  Sample
     *
    public function extraerInfo(){
        $ch = curl_init();
        $config['useragent'] = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $config['useragent']);
        curl_setopt($ch, CURLOPT_REFERER, 'https://www.domain.com/');
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/CSSEGISandData/COVID-19/git/blobs/a6f7a1d04bdf4ef7693842f3c7bd8ad070d61990");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if(FALSE === ($retval = curl_exec($ch))) {
            error_log(curl_error($ch));
        } else {
            $res =  $retval;
        }

        // $file = file_get_contents("https://api.github.com/repos/CSSEGISandData/COVID-19/git/blobs/a6f7a1d04bdf4ef7693842f3c7bd8ad070d61990");
        // $array = json_decode($file, true);

        return view('extraer', array('array' => $res));
    } */

}
