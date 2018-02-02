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
                margin:10px;
                padding :10px;
                font-size: 30px;
            }

            li span{

                color:red;
                font-style: italic;
            }



        </style>
    </head>
    <body>


    
            <ul>
                @foreach($stops as $stop)
                   <li>
                   {{ $stop->stop_name }}

                   @if(isset($stop->ici))
                   <span>X</span>
                   @endif
                   </li>
                @endforeach
            </ul>


    </body>
</html>
