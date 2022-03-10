<?php
/*
    Template Name: Map Listing
*/
?>
<?php get_header(); ?>
<style>
    .map-main{
        margin: 50px 0px 50px 0px;
    }
    div#map {
    height: 500px;
}
</style>
<?php $featuredImage = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
                <div style="background:url(<?php echo $featuredImage; ?>) repeat;" class="inner-main-image">
                    <h1 class="text-center"><?php the_title(); ?></h1>
                  
</div>
<!--<div class="page-title">-->
    
  <div class="inner">
      
    <div class="container">
        

     <!-- <div class="title"><?php the_title(); ?></div>-->
    </div>
  </div>
</div>
<section class="inner-page">

<div class="container">
    <div class="switch-view"  >
             <label id="mapView" for="mapView " class="active_view">Map View</label>
             <label id="listView" for="listView" >List View</label>
 
        </div>
    <div class="row map-main">
        <div class="col-md-4">
            <input type="hidden" val"" id="new_long">
            <input type="hidden" val"" id="new_lat"> 
            <input type="hidden"  name= "selectedcatname" id="selectedcat" value="15">  
            <label>Enter your location</label>
            <input class="form-control location" id="myInput" type="text" placeholder="Enter Location or Zipcode..">
        </div>
        <!-- <div class="col-md-3">
            <label>Category</label>
            <select class="form-control loc-category">
                <option value="-1">Select Category</option>
                <?php 
                $cat_terms = get_terms( array(
                 'taxonomy' => 'location_cat',
                 'hide_empty' => false,
                ) );
                foreach ($cat_terms as $key => $value) {
                    echo '<option value="'.$value->term_id.'">'.$value->name.'</option>';
                }
                ?>
            </select>
        </div> -->
        <div class="col-md-4">
            <label>Establishment Type</label>
            <select class="form-control loc-type">
            <?php $optiont = "View All";?>
                <?php $optionst = "-1";?>
<!--                <?php //$optiontt = "View All";?> -->
                <option value="-1"><?php echo $optiont ?></option> 
<!--                <option value="1"><?php //echo $optiontt ?></option>  -->
                <?php 
                $loc_terms = get_terms( array(
                 'taxonomy' => 'location_type',
                 'hide_empty' => true,
                ) );
                foreach ($loc_terms as $key => $value) {
                    echo '<option value="'.$value->term_id.'">'.$value->name.'</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="col-md-2">
            <label>Area</label>
            <select class="form-control filtercity">
                <?php $optionv = "View All";?>
<!--                    <?php $optionvv = "View All";?> -->
                <option value="-1"><?php echo $optionv ?></option>  
<!--                    <option value="1"><?php echo $optionvv ?></option>  -->
                <?php
            $args = array(
            'post_type'  => 'location_cpt',
            'posts_per_page' => -1,
            'order'   => 'ASC',
            'post_status' => 'publish'
            );
                $query = new WP_Query($args);
             while ( $query->have_posts()) {
            $query->the_post();
             //    $post_id = get_the_ID(); 
     
            if( have_rows('address') ):
                   
                    while( have_rows('address') ): the_row();
                    $cp_city = get_sub_field('city'); 
               
                echo '<option value="'.$cp_city.' ">'.$cp_city.'</option>';
                endwhile; 
                    endif;    
        
         }
             ?>
            </select> 
        </div> 
         
        
    </div>

    <div class="row map_view_main">
        <div class="map_view">
        <div class="col-md-6">
			
                <div class="post-listing">
               <?php
					
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
            'post_type'  => 'location_cpt',
            'posts_per_page' => 10,
            'paged'          => $paged,
            'order'   => 'ASC',
		'orderby' => 'title', 
            'post_status' => 'publish'
            );
            $query = new WP_Query($args);
            $arrayData = array();   
            $i=1;
            while ( $query->have_posts()) {
            $query->the_post();
            $arrayData[] = 
array('lat'=>get_field('lat',get_the_ID()),'long'=>get_field('long',get_the_ID()),'label'=>get_the_title());
                    $type_name = wp_get_object_terms( get_the_ID(), 'location_type', array( 'fields' => 'names' ) ) ;
          
                ?>
                <div class="listing">
                    <h3><a href="<?php echo get_permalink();?>"><?php the_title(); ?></a></h3>
                    <?php 
                    $img = get_the_post_thumbnail_url(get_the_ID(),'full');
                    ?>
                    <div class="corner">
                        <?php if(!empty($img)){ ?>
                        <div class="cert"><img src="<?php echo $img; ?>"></div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        
                        <div class="col col-lg-6"><i class="fa fa-cutlery"></i><?php echo $type_name[0];?></div>
                    </div>
                    <?php  if( have_rows('address') ):
                            while( have_rows('address') ): the_row();
                             $cp_city = get_sub_field('city');?>
                    <div class="row">
                        <div class="col col-lg-6"><i class="fa fa-street-view"></i><?php echo $cp_city ;?></div>
                    </div>
                        <?php   endwhile; 
                                    endif; ?>   
                </div>
                <?php 
                $i++; }
                ?>
                        <?php if(function_exists("wds_pagination")){
            wds_pagination($query->max_num_pages);
        }

wp_reset_postdata();?> 
            </div>

         
        </div>
        <div class="col-md-6">
            <div class="mapView" id="map">
            
            </div>
        </div>
    </div>
        <div class="list_viewmain">
                
        </div>
    </div>

    
</div>
</section>
<?php get_footer();
$apiKey = 'AIzaSyADlk166150RMLLGby78Ayq9kUKyAdHtp0';

 ?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey; ?>&callback=initMap"></script>
<script type="text/javascript">
//  list view
     $("#mapView, #listView").click(function(){
          $(this).addClass("active_view");
       });
     
     $("#mapView").click(function(e){
          $(this).next().removeClass("active_view");
         setTimeout(function(){
          $(".row.map_view_main .list_view").css("display", "none"); 
          $(".map_view").css("display", "block");
           $(".list_viewmain").empty();
         }, 500);

     
     
     });
    
     $("#listView").click(function(){
          $(this).prev().removeClass("active_view");
            $(".map_view").css("display", "none");
           $(".list_viewmain").empty();
         $("#myInput").empty();
         
        $('.loc-type').find('option[value=-1]').attr('selected','selected');
         //    $('.loc-category').find('option[value=-1]').attr('selected','selected');

         $.ajax({
                type: "post",
                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                //dataType: "text",
                data: { action: 'get_list_view' },
                  success: function(data) {
                   var loctype = $(".loc-type option:selected").val() ;
                        $(".list_viewmain").append(data);
                
                  }
                }); 
         
         
     });
    
    
    
    
    
//  map view
var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    //  var locations_data = <?php echo json_encode($arrayData ); ?>;
      //  let value = locations_data ;
   
//      console.log( value.map((item)=>item.lat));
//   console.log( value.map((item)=>item.long));
       
function initMap() {

   var locations = <?php echo json_encode($arrayData); ?>;
    // console.log(locations);   
    var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: new google.maps.LatLng(40.728226, -73.794853),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
  })
  
  var infowindow = new google.maps.InfoWindow({})
  var marker, i
  for (i = 0; i < locations.length; i++) {
    var markerLabel = i+1;
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[i].lat, locations[i].long),
      //icon: '<?php //echo site_url(); ?>/wp-content/themes/searsol/images/map-marker.svg',
   
      map: map,
    })
    google.maps.event.addListener(marker,'click',(function(marker, i) {
        return function() {
          infowindow.setContent(locations[i].label)
          infowindow.open(map, marker)
          
        }
      })(marker, i)
    )
  }
}   
 

// category search
         (function($){
            $(".loc-category").change(function(){
             var selectedcat = $(".loc-category option:selected").val();
                  $('#selectedcat').val(selectedcat);
             if ( $(".loc-category option:selected").val()=='-1') {  
              window.location.reload();
             }
            else{
               $(".post-listing").empty();
                  $(".data_list").empty();
                $.ajax({
                type: "POST",
                 url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                 dataType: "json",
                data: { action: 'get_loc_cat', 
                cat_id: $(".loc-category option:selected").val() },
                    
                    success: function(data) {
                   
                  var locations =  data.location;
                        
                        var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 5,
                            center: new google.maps.LatLng(35.9113247, -78.8978018),
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                        })

                        var infowindow = new google.maps.InfoWindow({})
                        var marker, i
                        for (i = 0; i < locations.length; i++) {
                            var markerLabel = i+1;
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(locations[i].lat, locations[i].long),
                                //icon: '<?php //echo site_url(); ?>/wp-content/themes/searsol/images/map-marker.svg',

                                map: map,
                            })
                            google.maps.event.addListener(marker,'click',(function(marker, i) {
                                return function() {
                                    infowindow.setContent(locations[i].label)
                                    infowindow.open(map, marker)

                                }
                            })(marker, i)
                                                         )
                        }

                     $(".post-listing").append( data.list); 
                   }
                }); 
                
                    $.ajax({
                type: "post",
                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                // dataType: "json",
                data: { action: 'get_loc_cat_list', 
                cat_id: $(".loc-category option:selected").val() },
                    success: function(data) {
                       $(".data_list").append(data);
                          console.log(locations);    
                        }
                }); 
             }
            });
         
            })(jQuery);
     
    
    // type search
    
         (function($){
            $(".loc-type").change(function(){
                //var selectedcat = $(".loc-type option:selected").val();
                //  $('#selectedcat').val(selectedcat);
 if ( $(".loc-type option:selected").val()=='-1') {  
	      $(".post-listing").empty();
                  $(".data_list").empty();
           $.ajax({
                type: "post",
                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                 dataType: "json",
                data: { action: 'get_loc_city', 
              city_id: $(".filtercity option:selected").text(),
                // type_id: $(".loc-type option:selected").val()    
                      },
                    success: function(data) {
                        
                         var locations =  data.location;
                        
                        var map = new google.maps.Map(document.getElementById('map'), {
//                          zoom: 5,
//                          center: new google.maps.LatLng(35.9113247, -78.8978018),
 zoom: 12,
    center: new google.maps.LatLng(40.728226, -73.794853),
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                        })

                        var infowindow = new google.maps.InfoWindow({})
                        var marker, i
                        for (i = 0; i < locations.length; i++) {
                            var markerLabel = i+1;
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(locations[i].lat, locations[i].long),
                                //icon: '<?php //echo site_url(); ?>/wp-content/themes/searsol/images/map-marker.svg',

                                map: map,
                            })
                            google.maps.event.addListener(marker,'click',(function(marker, i) {
                                return function() {
                                    infowindow.setContent(locations[i].label)
                                    infowindow.open(map, marker)

                                }
                            })(marker, i)
                                                         )
                        }
                        
                         $(".post-listing").append(data.list);
                         
                        }
                }); 
	  $.ajax({
                type: "post",
                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                data: { action: 'get_loc_city_list', 
                city_id: $(".filtercity option:selected").text(),
                       type_id: $(".loc-type option:selected").val()
                      },
                    success: function(data) {
                       $(".data_list").append(data);
                        
                        }
                });
 }
			

else{
               $(".post-listing").empty();
                  $(".data_list").empty();
                $.ajax({
                type: "post",
                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                 dataType: "json",
                data: { action: 'get_loc_type', 
                type_id: $(".loc-type option:selected").val(), 
                      city_id: $(".filtercity option:selected").text()
                      },
                    success: function(data) {
                     
                      var locations =  data.location;
                         var map = new google.maps.Map(document.getElementById('map'), {
//                          zoom: 5,
//                          center: new google.maps.LatLng(35.9113247, -78.8978018),
                              zoom: 12,
    center: new google.maps.LatLng(40.728226, -73.794853),
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                        })

                        var infowindow = new google.maps.InfoWindow({})
                        var marker, i
                        for (i = 0; i < locations.length; i++) {
                            var markerLabel = i+1;
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(locations[i].lat, locations[i].long),
                                //icon: '<?php //echo site_url(); ?>/wp-content/themes/searsol/images/map-marker.svg',
                             map: map,
                            })
                            google.maps.event.addListener(marker,'click',(function(marker, i) {
                                return function() {
                                    infowindow.setContent(locations[i].label)
                                    infowindow.open(map, marker)

                                }
                            })(marker, i)
                                                     )
                        }
                        $(".post-listing").append(data.list);
                         
                        }
                }); 
                $.ajax({
                type: "post",
                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                //dataType: "text",
                data: { action: 'get_loc_type_list', 
                type_id: $(".loc-type option:selected").val(), 
                      city_id: $(".filtercity option:selected").text()
                      },
                    success: function(data) {
                       $(".data_list").append(data);
                        
                        }
                }); 
            }
            });
         
            })(jQuery);
    
    
     //city search
    
(function($){
            $(".filtercity").change(function(){
                //var selectedcat = $(".loc-type option:selected").val();
                //  $('#selectedcat').val(selectedcat);
           if ( $(".filtercity option:selected").val()=='-1') {  
			   $(".post-listing").empty();
                  $(".data_list").empty();
         $.ajax({
                type: "post",
                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                 dataType: "json",
                data: { action: 'get_loc_type', 
                type_id: $(".loc-type option:selected").val(), 
                      city_id: $(".filtercity option:selected").text()
                      },
                    success: function(data) {
                     
                      var locations =  data.location;
                         var map = new google.maps.Map(document.getElementById('map'), {
//                          zoom: 5,
//                          center: new google.maps.LatLng(35.9113247, -78.8978018),
                              zoom: 12,
    center: new google.maps.LatLng(40.728226, -73.794853),
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                        })

                        var infowindow = new google.maps.InfoWindow({})
                        var marker, i
                        for (i = 0; i < locations.length; i++) {
                            var markerLabel = i+1;
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(locations[i].lat, locations[i].long),
                                //icon: '<?php //echo site_url(); ?>/wp-content/themes/searsol/images/map-marker.svg',
                             map: map,
                            })
                            google.maps.event.addListener(marker,'click',(function(marker, i) {
                                return function() {
                                    infowindow.setContent(locations[i].label)
                                    infowindow.open(map, marker)

                                }
                            })(marker, i)
                                                     )
                        }
                        $(".post-listing").append(data.list);
                         
                        }
                }); 
			    $.ajax({
                type: "post",
                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                //dataType: "text",
                data: { action: 'get_loc_type_list', 
                type_id: $(".loc-type option:selected").val(), 
                      city_id: $(".filtercity option:selected").text()
                      },
                    success: function(data) {
                       $(".data_list").append(data);
                        
                        }
                }); 
          }         
				
				
				
				
				
				else{
               $(".post-listing").empty();
                  $(".data_list").empty();
                    
                $.ajax({
                type: "post",
                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                 dataType: "json",
                data: { action: 'get_loc_city', 
                city_id: $(".filtercity option:selected").text(),
                 type_id: $(".loc-type option:selected").val()    
                      },
                    success: function(data) {
                        
                         var locations =  data.location;
                        
                        var map = new google.maps.Map(document.getElementById('map'), {
//                          zoom: 5,
//                          center: new google.maps.LatLng(35.9113247, -78.8978018),
 zoom: 12,
    center: new google.maps.LatLng(40.728226, -73.794853),
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                        })

                        var infowindow = new google.maps.InfoWindow({})
                        var marker, i
                        for (i = 0; i < locations.length; i++) {
                            var markerLabel = i+1;
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(locations[i].lat, locations[i].long),
                                //icon: '<?php //echo site_url(); ?>/wp-content/themes/searsol/images/map-marker.svg',

                                map: map,
                            })
                            google.maps.event.addListener(marker,'click',(function(marker, i) {
                                return function() {
                                    infowindow.setContent(locations[i].label)
                                    infowindow.open(map, marker)

                                }
                            })(marker, i)
                                                         )
                        }
                        
                         $(".post-listing").append(data.list);
                         
                        }
                });     
                    
                    $.ajax({
                type: "post",
                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                data: { action: 'get_loc_city_list', 
                city_id: $(".filtercity option:selected").text(),
                       type_id: $(".loc-type option:selected").val()
                      },
                    success: function(data) {
                       $(".data_list").append(data);
                        
                        }
                });     
                    
                    
                    
                    
          }
         });
         
            })(jQuery);
    
/*** location search ***/
jQuery(document).ready(function($){
     $(document).on('keyup','.location',function(){
           var location = $(this).val();
          var category = $('.loc-category').val(); 
          var type = $('.loc-type').val(); 
        var city =   $('.filtercity').text();
         $.ajax({
             type: "post",
            dataType: "json",
             url: ajaxurl,
             data: {action:'cat_fillter',location:location,category:category,type:type, city:city},
             success: function(data){
               $('#new_long').val(data[0]) ;
                 $('#new_lat').val(data[1]);
                  updateMap();
              }
         });
         
     });
}); 
    
    
//  search by name

 
$("#myInput").on("keyup", function() {
     
     var value = $(this).val().toLowerCase();
     $("#myTable .data_list tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    
});
    
function updateMap() { 
        
              var lng_val =  $('#new_long').val();
              var lat_val =  $('#new_lat').val();
               if (lng_val != '' )  {
                  var lng = parseFloat(lng_val);
               }else{
                    var lng = -80.18752;
               }
                if (lat_val != '' )  {
                 var lat =  parseFloat(lat_val);
               }else{
                    var lat = 25.78179;
               }
                const uluru = { lat: lat , lng: lng};
                const map = new google.maps.Map(document.getElementById("map"), {
            
     zoom: 18,
     center: uluru,
     mapTypeId: google.maps.MapTypeId.ROADMAP,
  });
  
  const marker = new google.maps.Marker({
    position: uluru,
    map: map,
  });
         
     }      
  
    
    
    
//  duplicate value remove 
//   $(".filtercity option").val(function(idx, val) {
//   $(this).siblings('[value="'+ val +'"]').remove();
// });
 $(document).ready(function() {
   var usedNames = {};
   $(".filtercity > option").each(function() {
      if (usedNames[this.value]) {
         $(this).remove();
      } else {
         usedNames[this.value] = this.text;
      }
   });
});
    
const sort = (arr, p, o = "asc") => arr.sort((a, b) => {
  if (o !== "asc")[a, b] = [b, a];
  const isNum = typeof b[p] === "number";
  return (isNum ? Number(a[p]) - b[p] : String(a[p]).localeCompare(b[p]));
});


$.fn.sortChildren = function(op) {
  op = $.extend({
    by: "textContent",
    order: "asc"
  }, op);
  return this.each(function() {
    const i = $(this).prop("selectedIndex");
    $(this).html(sort($(this).children(), op.by, op.order)).prop({selectedIndex: i});
  });
};


// 1. example: sorting by value, order "asc" (default)
$(".filtercity").sortChildren({by: "value"});
    </script>


<style>
.switch-view {
    text-align: center;
} 
    .switch-view label {
    border: 2px solid;
    padding: 4px 29px;
     margin: 0 -4px;
}
    .switch-view .active_view {
    background: #224B89;
    color: #fff;
    padding: 6px 29px;
    margin: 0 -4px;
     border: transparent;
}
    i.fa.fa-cutlery {
    padding: 0 9px;
}

i.fa.fa-street-view {
    padding: 0 9px;
}
    .listing {
    background: #f5efef;
    margin: 7px 2px;
    padding: 10px 11px;
    box-shadow: 1px 2px #e8dcdc;
}
    .post-listing:empty:after
{
    content: "No result found base on your selection. Please try some other options.";
}
    .trr:nth-child(1) {
    display: block;
}
    .trr {
    display: none;
}
    .wds-pagination {
    clear: both;
    position: relative;
    font-size: 16px;
    line-height: 13px;
    float: right;
    list-style-type: none;
    width: 100%;
}
    .wds-pagination a, .wds-pagination span {
    display: block;
    float: left;
    margin: 2px 2px 2px 0;
    padding: 6px 9px 5px 9px;
    text-decoration: none;
    width: auto;
    color: #fff;
    background: #006EBF;
}
    .wds-pagination .current {
    padding: 6px 9px 5px 9px;
    background: #999;
    color: #fff;
}
.wds-pagination a:hover{
   color:#fff;
   background: #000; 
}
</style>
