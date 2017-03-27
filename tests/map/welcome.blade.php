<?php 
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
?>

<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <script src="https://code.jquery.com/jquery-3.2.1.js"></script>

        <!-- map dependencies -->
        <script type="text/javascript" src="/js/micro-event.js"></script>
        <script type="text/javascript" src="/js/map/google-maps-functions.js"></script>
        <script type="text/javascript" src="/js/map/map-class.js"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100% !important;
                width: 100% !important;
                margin: 0;
            }

            #map-container {
                height: 100% !important;
                width: 100% !important;
            }
            #map-container .map-canvas {
                height: 100% !important;
                width: 100% !important;
            }

            .map-control-pn-horisontal {
                height: auto;
                width: auto;
                text-align: center;
                margin-left: -34px;
                margin-top: 5px;
                vertical-align: middle;
                text-align: center;
                font-size: 1.44em;
                display: block !important;
            }

            .map-control-pn-vertical {
                height: auto;
                width: auto;
                min-width: 32px;
                text-align: center;
                margin-left: 8px;
                margin-top: 31px;
                vertical-align: middle;
                text-align: center;
                font-size: 1.44em;
                display: block !important;
                position: absolute;
                float: left;
            }

            .map-control-pn-vertical div {
                display: block !important;
                margin-bottom: 2px;
                float: none;
            }

            .map-control-element {
                background-color: #fff;
                float: left;
                width: 30px;
                height: 100%;
                overflow: hidden;
                vertical-align: middle;
                margin-right: 2px;
                padding: 1px;
                padding-top: 4px;
                position: relative;
            }

            .map-control-element:hover {
                background-color: #eee !important;
            }
        </style>
    </head>

    <body>
        <div id="map-container">
            <div class="map-canvas"></div>
        </div>
    </body>

    <script type="text/javascript">
    function randomString(len) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 5; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    function log(arg) { console.log(arg) };


    $(function() {

        var mapOptions = {
            zoom: 14,
            mapTypeId: 'OSM',
            center: {lat: 53.53326804753752, lng: 33.7254524230957},
            disableDefaultUI: true
        };

        googleMapsLoaded(function() {

            // map shell
            var shellmap = new MapShell( mapOptions, 'map-container' );

            // drawing manager shell
            var shellmanager = new DrawingManagerShell( shellmap, null );

            log(shellmap);
            log(shellmanager);


            var ce1 = shellmanager.controls.create( '<i class="fa fa-remove"></i>', 'Remove selected overlay' );
            var ce2 = shellmanager.controls.create( '<i class="fa fa-telegram"></i>', 'Remove selected overlay' );
            var ce3 = shellmanager.controls.create( '<i class="fa fa-bluetooth"></i>', 'Remove selected overlay' );
            var ce4 = shellmanager.controls.create( '<i class="fa fa-bell-o"></i>', 'Remove selected overlay' );
            var ce5 = shellmanager.controls.create( '<i class="fa fa-bell-o"></i>', 'Remove selected overlay' );
            var ce6 = shellmanager.controls.create( '<i class="fa fa-bell-o"></i>', 'Remove selected overlay' );
            var ce7 = shellmanager.controls.create( '<i class="fa fa-bell-o"></i>', 'Remove selected overlay' );
            var ce8 = shellmanager.controls.create( '<i class="fa fa-bell-o"></i>', 'Remove selected overlay' );
            var ce9 = shellmanager.controls.create( '<i class="fa fa-bell-o"></i>', 'Remove selected overlay' );
            var ce10 = shellmanager.controls.create( '<i class="fa fa-bell-o"></i>', 'Remove selected overlay' );
            var ce11 = shellmanager.controls.create( '<i class="fa fa-bell-o"></i>', 'Remove selected overlay' );

            shellmanager.controls.add( ce1, ControlsManagerShell.position.HORISONTAL );
            shellmanager.controls.add( ce2, ControlsManagerShell.position.HORISONTAL );
            shellmanager.controls.add( ce3, ControlsManagerShell.position.HORISONTAL );

            shellmanager.controls.add( ce4, ControlsManagerShell.position.HORISONTAL );
            shellmanager.controls.add( ce5, ControlsManagerShell.position.HORISONTAL );
            shellmanager.controls.add( ce6, ControlsManagerShell.position.HORISONTAL );
            shellmanager.controls.add( ce7, ControlsManagerShell.position.HORISONTAL );
            shellmanager.controls.add( ce8, ControlsManagerShell.position.HORISONTAL );
            shellmanager.controls.add( ce9, ControlsManagerShell.position.VERTICAL );
            shellmanager.controls.add( ce10, ControlsManagerShell.position.VERTICAL );
            shellmanager.controls.add( ce11, ControlsManagerShell.position.VERTICAL );

            // shellmanager.controls.remove( ce2 );
        });
        

    });
    </script>

</html>
