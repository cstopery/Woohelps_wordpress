<?php
/**
 * map-google.php
 *
 * @author      mogita
 * @created_by  PhpStorm
 * @created_at  8/28/16 12:52
 */
?>
<!-- section divider -->
<div class="section-divider">
    <div class="container">
        <div class="row">
            <div class="title">
                <h2 class="text-center">
                    萨斯卡通地图
                </h2>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyD8FnboaQRJ4Q9Xq6QHOay2p23dtt0P75U"></script>
<script src="<?=get_stylesheet_directory_uri()?>/js/gmaps.js"></script>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-9">
            <div class="gmap" id="gmap">

            </div>
        </div>

        <div class="col-xs-12 col-sm-3">
            <div class="locations-list" id="locations-list">
                <h3>常用地点</h3>
                <ul id="locations-list-ul">

                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    var $ = jQuery;
    var map;

    var markers = [
        {lat: 52.146630, lng: -106.659510, title: '餐厅'},
        {lat: 52.141376, lng: -106.651952, title: '大超市'},
        {lat: 52.145440, lng: -106.658522, title: '停车场'},
        {lat: 52.142530, lng: -106.652570, title: '学校'},
        {lat: 52.144222, lng: -106.657524, title: '市政大厅'},
        {lat: 52.143351, lng: -106.653524, title: '喷泉广场'},
        {lat: 52.140466, lng: -106.656514, title: '医院'}
    ];

    $('body').on('click', '.pan-to-marker', function(e) {
        e.preventDefault();

        var lat, lng;
        var $lat = $(this).data('lat');
        var $lng = $(this).data('lng');

        lat = $lat;
        lng = $lng;

        map.setCenter(lat, lng);
    });

    $(function() {
        map = new GMaps({
            el: '#gmap',
            lat: 52.146524,
            lng: -106.671453
        });

        $.each(markers, function(index, marker) {
            var elMarker = '<li><a href="#" class="pan-to-marker" data-lat="' + marker.lat + '" data-lng="' + marker.lng + '">' + marker.title + '</a></li>';

            $(elMarker).appendTo($('#locations-list-ul'));
            map.addMarker({
                lat: marker.lat,
                lng: marker.lng,
                title: marker.title,
                infoWindow: {
                    content : '<p>' + marker.title + '</p>'
                }
            });
        });
    });
</script>