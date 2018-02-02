<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            ul {
                list-style:none;
                margin:0;
                padding :0;
            }

            a  {
                text-decoration:none;      
            }

            .LigneNomber{
                display: inline-block;
                width: 50px;
                border: 1px solid black;
                margin-top: 20px;
                margin: 10px;
                padding: 10px;
                text-align: -webkit-center;
                font-size: 25px;
            }
                
           


        </style>
    </head>
    <body>


    

    
       
            <ul>
            @foreach  ($routes as $route)
            @foreach  ($route->route_direction as $slug => $direction)
                <li> 
                
                <a href="{{ route( "show" ,[ 'id' => $route ->route_short_name , 'direction'=>$slug] ) }}">
                
                    <span class="LigneNomber" style="background-color: {{ $route->route_color }}; color: {{ $route->route_text_color }};">{{ $route ->route_short_name }}</span>
                    <span class="LigneName">{{ $direction }}</span>
                
                </a>
                
                
                 </li>
             @endforeach
             @endforeach
            </ul>

    </body>
</html>
