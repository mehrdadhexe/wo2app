<?php
/**
 * Created by PhpStorm.
 * User: Hani
 * Date: 8/29/2018
 * Time: 7:40 PM
 */

if(isset($_POST['insert_loc'])){
    $table_name = $wpdb->prefix . 'location_gps';
    $r = $wpdb->insert(
        $table_name,
        array(
            'lg_lat' => $_POST['lat'],
            'lg_lng' => $_POST['lng'],
            'lg_title' => $_POST['title'],
        )
    );
    if($r){
        echo '<p style="color: green;"> لوکیشن به درستی ثبت شد .</p>';
    }
    else{
        echo '<p style="color: red;">  خطایی رخ داده است ، لطفا  مجدد امتحان کنید ...</p>';
    }
}
?>


<script src="https://addmap.parsijoo.ir/leaflet/leaflet.js"></script>
<link rel="stylesheet" href="https://addmap.parsijoo.ir/leaflet/leaflet.css" />
<div id="map" style="width:100%;height:500px;margin-bottom: 10px"></div>
<form method="post">
    <input type="text" class="regular-text" name="title" />
    <input type="hidden" class="regular-text" name="lat" id="lat" />
    <input type="hidden" class="regular-text" name="lng" id="lng" />
    <input type="submit" name="insert_loc" class="button button-primary" value=" ذخیره">
</form>
<hr>
<script>
    var map = L.map('map').setView([35.70163, 51.39211], 12);
    L.tileLayer('https://developers.parsijoo.ir/web-service/v1/map/?type=tile&x={x}&y={y}&z={z}&apikey=efe3224bd92441a7abc28cffc591f2f4', {
        maxZoom: 17,
    }).addTo(map);

    var marker;
    map.on("click",function(e){
        if (marker) { // check
            map.removeLayer(marker); // remove
        }
        //        console.log(e.latlng.lat)
        //      console.log(e.latlng.lng)
        jQuery("#lat").val(e.latlng.lat);
        jQuery("#lng").val(e.latlng.lng);
        marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);

    });
</script>
