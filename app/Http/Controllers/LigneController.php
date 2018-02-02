<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LigneController extends Controller
{
    
    function index() {
        $routes = $this->getroutes();
        
        //$position = $this->positionVehicule();
        //dump($position);
       
        return view('lignes')->with("routes", $routes);
    }

    function getroutes($route_id = null){
        $path = storage_path('stib/routes.json');
        $data = file_get_contents($path);
        $routes = json_decode($data);
        $routes = $this->getRouteDirections($routes->routes);

        if ($route_id){

            $key = array_search($route_id, array_column($routes, 'route_short_name'));

            return $routes[$key];
        }

        return $routes;
    }


    function positionVehicule($route_id) {

        $token = $this->gettoken();
       
        $curl = curl_init();
     
        curl_setopt_array($curl, array(

            CURLOPT_RETURNTRANSFER => 1,

            CURLOPT_URL => 'https://opendata-api.stib-mivb.be/OperationMonitoring/1.0/VehiclePositionByLine/'.$route_id,

            CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $token)
        ));
      
        $resp = curl_exec($curl);
    
        curl_close($curl);

        $data = json_decode($resp);

        return $data->lines[0]->vehiclePositions;
      
    }


    function gettoken() {

        $curl = curl_init();
     
        curl_setopt_array($curl, array(

            CURLOPT_RETURNTRANSFER => 1,

            CURLOPT_POST => 1,

            CURLOPT_URL => 'https://opendata-api.stib-mivb.be/token',

            CURLOPT_POSTFIELDS => "grant_type=client_credentials",

            CURLOPT_HTTPHEADER => array('Authorization: Basic T3c2WDdZZkcyQUdpcUhZZzdVbklOczVjS1I4YTpGWGpCaExmZTh5Nk1LUGE1VE1XenlaZmFkM2th')
        ));
      
        $resp = curl_exec($curl);
    
        curl_close($curl);

        //dump($resp); 

        $data = json_decode($resp);

        return $data->access_token;

    }

    function getRouteDirections($routes) {

        foreach ($routes as $key => $route){
                
                $directions = explode(' - ', $route->route_long_name);                
                $routes[$key]->route_direction = array(
                    $this->slugify( $directions[0] ) => $directions[0],
                    $this->slugify( $directions[1] ) => $directions[1],

                );
        }


        return $routes;
        
    }

    function slugify($string, $replace = array(), $delimiter = '-') {
        // https://github.com/phalcon/incubator/blob/master/Library/Phalcon/Utils/Slug.php
        if (!extension_loaded('iconv')) {
          throw new Exception('iconv module not loaded');
        }
        // Save the old locale and set the new locale to UTF-8
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        if (!empty($replace)) {
          $clean = str_replace((array) $replace, ' ', $clean);
        }
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        // Revert back to the old locale
        setlocale(LC_ALL, $oldLocale);
        return $clean;
      }

      function show($route_id,$route_direction) {

        $route = $this->getroutes($route_id);
        $stops = $this->stops($route,$route_direction);
        $positions = $this->positionVehicule($route_id);

        $route_stops_with_live_potion = $this->makeItHappen($stops,$positions);
          
        return view('ligne')->with( 'stops' , $route_stops_with_live_potion);

      }




      function makeItHappen($stops,$positions) {

        $last_stop_id = end($stops)->stop_id;

        foreach ($stops as $key => $stop){
            foreach ($positions as $position){
                if ($position->directionId != $last_stop_id) continue;

                if ($position->pointId == $stop->stop_id){
                    $stops[$key]->ici = true;
                } 
            }
        }

        return $stops;

        
      }

      function stops($route,$direction) {

        $path = storage_path("stib/routes_stops/$route->route_short_name.$route->route_long_name.json");
        $data = file_get_contents($path);
        $routes = json_decode($data);

        $direction_name = $route->route_direction[$direction];

        $key = array_search($direction_name, $routes->directions);

        return $routes->stops[$key];


       

      }
     




}


