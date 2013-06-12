<?php $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] ); require_once( $parse_uri[0] . 'wp-load.php' ); ?>
<!DOCTYPE html>
<html lang="en">
   <head>
        <title>get directions</title>
		<link rel="stylesheet" href="<?php echo plugins_url(); ?>/get-directions-from-mobile/css/style.css" type="text/css" media="screen" />
	<?php
	function mda_script_init() {
    wp_register_script( 'jquerycustom', plugins_url() .'/get-directions-from-mobile/js/custom.js', array( 'jquery' ), '1.0' );
    wp_enqueue_script( 'jquerycustom' );
	wp_register_script( 'jquerypremobile', plugins_url() .'/get-directions-from-mobile/js/mobile.js', array( 'jquery' ), '1.0' );
    wp_enqueue_script( 'jquerypremobile' );
	wp_register_script( 'googlemaps', plugins_url() .'/get-directions-from-mobile/js/googlemaps.js', array( 'jquery' ), '1.0' );
    wp_enqueue_script( 'googlemaps' );
    // etc...
}
add_action('wp_enqueue_scripts', 'mda_script_init');
	wp_head();
?>
		
        <script type="text/javascript">

            var map,
                currentPosition,
                directionsDisplay, 
                directionsService;

            function initialize(lat, lon)
            {
                directionsDisplay = new google.maps.DirectionsRenderer(); 
                directionsService = new google.maps.DirectionsService();

                currentPosition = new google.maps.LatLng(lat, lon);

                map = new google.maps.Map(document.getElementById('map_canvas'), {
                   zoom: 15,
                   center: currentPosition,
                   mapTypeId: google.maps.MapTypeId.ROADMAP
                 });

                directionsDisplay.setMap(map);

                 var currentPositionMarker = new google.maps.Marker({
                    position: currentPosition,
                    map: map,
                    title: "Current position"
                });

                var infowindow = new google.maps.InfoWindow();
                google.maps.event.addListener(currentPositionMarker, 'click', function() {
                    infowindow.setContent("Current position: latitude: " + lat +" longitude: " + lon);
                    infowindow.open(map, currentPositionMarker);
                });
            }

            function locError(error) {
             alert("you must activate google location service in your phone if you want to be found");
            }

            function locSuccess(position) {
                initialize(position.coords.latitude, position.coords.longitude);
            }

            function calculateRoute() {
                var targetDestination = $("#target-dest").val();
                if (currentPosition && currentPosition != '' && targetDestination && targetDestination != '') {
                    var request = {
                        origin:currentPosition, 
                        destination:targetDestination,
                        travelMode: google.maps.DirectionsTravelMode["DRIVING"]
                    };

                    directionsService.route(request, function(response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            directionsDisplay.setPanel(document.getElementById("directions"));
                            directionsDisplay.setDirections(response); 

                            /*
                                var myRoute = response.routes[0].legs[0];
                                for (var i = 0; i < myRoute.steps.length; i++) {
                                    alert(myRoute.steps[i].instructions);
                                }
                            */
                            $("#results").show();
                        }
                        else {
                            $("#results").hide();
                        }
                    });
                }
                else {
                    $("#results").hide();
                }
            }

            $(document).live("pagebeforeshow", "#map_page", function() {
                navigator.geolocation.getCurrentPosition(locSuccess, locError);
            });

            $(document).on('click', '#directions-button', function(e){
                e.preventDefault();
                calculateRoute();
            });
        </script>
    </head>
    <body>
        <div id="basic-map" data-role="page">
            <div data-role="content">   
                <div class="ui-bar-c ui-corner-all ui-shadow" style="padding:1em;">
                    <div id="map_canvas" style="height:550px;"></div>
                </div>
                <div data-role="fieldcontain">
                    <label for="target-dest">Target Destination (Business Location): <?php echo get_option('googlemap_link'); ?></label>
                    <input type="hidden" name="target-dest" id="target-dest" value="<?php echo get_option('googlemap_link'); ?>"  />
					<br/>*remember to allow google GPS in your device
                </div>
                <a href="#" id="directions-button" data-role="button" data-inline="true" data-mini="true">Get Directions To: <?php echo get_option('googlemap_link'); ?> </a>
                <div id="results" style="display:none;">
                    <div id="directions"></div>
                </div>
            </div>
        </div>      
    </body>
</html>