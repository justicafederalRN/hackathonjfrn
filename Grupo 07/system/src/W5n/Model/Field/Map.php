<?php
namespace W5n\Model\Field;

class Map extends Field
{

    protected static $scriptAdded = false;

    protected $lat  = 0;
    protected $lng  = 0;
    protected $zoom = 0;
    protected $latField  = 0;
    protected $lngField  = 0;
    protected $zoomField = 0;

    function __construct(
        $name, $label,
        $searchByAdress = true,
        $startLat = 0,
        $startLng = 0,
        $startZoom = 14,
        $latField  = 'latitude',
        $lngField  = 'longitude',
        $zoomField = 'zoom'
    ) {
        parent::__construct($name, $label);
        $this->lat  = $startLat;
        $this->lng  = $startLng;
        $this->zoom = $startZoom;

        $this->latField  = $latField;
        $this->lngField  = $lngField;
        $this->zoomField = $zoomField;
        $this->setPersistent(false);
        $this->setOption('searchByAddress', $searchByAdress);
    }

    public function setModel(\W5n\Model\Model $model)
    {
        parent::setModel($model);
        $name   = $this->getName();
        if (!$model->hasField($this->latField) && $this->latField !== false) {
            $model->text($this->latField, 'Latitude');
        }

        if (!$model->hasField($this->lngField) && $this->lngField !== false) {
            $model->text($this->lngField, 'Longitude');
        }

        if (!$model->hasField($this->zoomField) && $this->zoomField !== false) {
            $model->text($this->zoomField, 'Zoom');
        }
    }

    public function afterModelPopulate($operation)
    {
        $values = $this->getModel()->toArray();
        $name   = $this->getName();
        if ($this->latField !== false && isset($values[$this->latField])) {
            $this->lat = $values[$this->latField];
        }

        if ($this->lngField !== false && isset($values[$this->lngField])) {
            $this->lng = $values[$this->lngField];
        }

        if ($this->zoomField !== false && isset($values[$this->zoomField])) {
            $this->zoom = $values[$this->zoomField];
        }

        parent::afterModelPopulate($operation);
    }

    public function toHtml(Field $field)
    {
        $container = \W5n\Html\HtmlBuilder::tag('div', array('class' => 'input-map-container row'));
        $input     = \W5n\Html\HtmlBuilder::input($this->getName(), $this->getValue());
        $inputLat  = \W5n\Html\HtmlBuilder::input($this->latField, $this->lat, 'hidden')->addClass('input-map-lat');
        $inputLng  = \W5n\Html\HtmlBuilder::input($this->lngField, $this->lng, 'hidden')->addClass('input-map-lng');
        $inputZoom = \W5n\Html\HtmlBuilder::input($this->zoomField, $this->zoom, 'hidden')->addClass('input-map-zoom');

        if ($this->hasError()) {
            $input->addClass('has-error');
        }

        $input->addClass('input-map form-control')->setAttr('style', 'width:100%');
        $this->applyValidationHtmlModifications($input);
        $mapContainer = \W5n\Html\HtmlBuilder::tag('div', array('class' => 'input-map-map'));
        $mapContainer->setAttr('style', 'height:350px');

        if ($this->getOption('searchByAddress', false)) {
            $inputGroup                = \W5n\Html\HtmlBuilder::tag('div', array('class' => 'input-group'));
            $buttonContainerInputGroup = \W5n\Html\HtmlBuilder::tag('span', array('class' => 'input-group-btn'));

            $buttonInputGroup          = \W5n\Html\HtmlBuilder::tag('button', array('type' => 'button', 'class' => 'btn btn-default input-map-btn-search'));
            $buttonInputGroup->appendText('Buscar pelo endereço');
            $buttonContainerInputGroup->appendChild($buttonInputGroup);
            $inputGroup->appendChild($input);
            $inputGroup->appendChild($buttonContainerInputGroup);
            $container->appendChild($inputGroup);
        } else {
            $container->appendChild($input);
        }



        $container->appendText('<br />');
        $container->appendChild($inputLat);
        $container->appendChild($inputLng);
        $container->appendChild($inputZoom);
        $container->appendChild($mapContainer);

        if (!self::$scriptAdded) {
            //$gmScript = \W5n\Html\HtmlBuilder::script('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');

            $fieldScript = \W5n\Html\HtmlBuilder::script();
            $fieldScript->appendText($this->getScriptContent());

            //$container->appendChild($gmScript);
            $container->appendChild(
                \W5n\Html\HtmlBuilder::script('https://maps.googleapis.com/maps/api/js?libraries=places&language=pt-BR&key=AIzaSyC3tdEFepRD-SDv6aNqZ8p4PD8JdcfpHd8'
            ));
            $container->appendChild($fieldScript);
        }


        return $container;
    }


    private function getScriptContent()
    {
        return <<<MAP
window.onload = function() {
     (function ($){



         var geocoder = new google.maps.Geocoder();
         function renderMap(\$el) {
            var inputLat  = $('.input-map-lat', \$el.parent().parent());
            var inputLng  = $('.input-map-lng', \$el.parent().parent());
            var inputZoom = $('.input-map-zoom', \$el.parent().parent());
            var searchButton = $('.input-map-btn-search', \$el.parent().parent());


            var latlng = new google.maps.LatLng(inputLat.val(), inputLng.val());
            var args = {
                zoom		: parseInt(inputZoom.val()),
                center		: latlng,
                mapTypeId	: google.maps.MapTypeId.ROADMAP
            };

            var map = new google.maps.Map($('.input-map-map', \$el.parent().parent())[0], args);
            google.maps.event.addDomListener(window, 'resize', function() {

                map.setCenter(latlng);
                map.setZoom(map.getZoom());
            });
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href") // activated tab
                google.maps.event.trigger(map, 'resize');
                google.maps.event.trigger(window, 'resize');
                map.setCenter(latlng);
                map.setZoom(map.getZoom());
                $(window).trigger('resize');
            });
            // add a markers reference
            map.marker = new google.maps.Marker({
                map: map,
                position: latlng
            });

            google.maps.event.addDomListener(window, 'resize', function() {
                map.setCenter(latlng);
            });

            google.maps.event.addListener(map, 'zoom_changed', function() {
                inputZoom.val(map.getZoom());
            });

            //map.controls[google.maps.ControlPosition.TOP_LEFT].push(\$el[0]);
            var searchBox = new google.maps.places.SearchBox(\$el[0]);

            google.maps.event.addListener(searchBox, 'places_changed', function() {
                var places  = searchBox.getPlaces();
                var markers = map.markers;
                if (places.length == 0) {
                  return;
                }
                if (map.marker != null) {
                    map.marker.setMap(null);
                    map.marker = null;
                }

                // For each place, get the icon, place name, and location.
                markers = [];
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0, place; place = places[i], i <= 0; i++) {
                  var image = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                  };
                  console.log(place.icon);
                  // Create a marker for each place.
                  map.marker = new google.maps.Marker({
                    map: map,
                    icon: image,
                    title: place.name,
                    position: place.geometry.location
                  });
                  inputLat.val(place.geometry.location.lat());
                  inputLng.val(place.geometry.location.lng());
                  bounds.extend(place.geometry.location);
                }

                map.fitBounds(bounds);
            });

            searchButton.on('click', function() {
                var logradouro = $('#field_endereco').val();
                var numero     = $('#field_numero').val();
                var bairro     = $('#field_bairro').val();
                var cidade     = $('#field_cidade').val();
                var uf         = $('#field_uf').val();

                if (logradouro == '') {
                    $('#field_endereco').focus();
                    return false;
                }

                if (numero == '') {
                    $('#field_numero').focus();
                    return false;
                }



                if (bairro == '') {
                    $('#field_bairro').focus();
                    return false;
                }

                if (cidade == '') {
                    $('#field_cidade').focus();
                    return false;
                }

                if (uf == '') {
                    $('#field_uf').focus();
                    return false;
                }

                var oldText = $(this).text();
                $(this).text('Atualizando mapa...');
                var btn = this;
                var address = logradouro + ', ' + numero + ' - ' + cidade + ' - ' + uf;
                console.log(address);
                geocoder.geocode( { 'address': address}, function(results, status) {
                    $(btn).text(oldText);
                    if (status == google.maps.GeocoderStatus.OK) {
                      latlng = results[0].geometry.location;
                      inputLat.val(results[0].geometry.location.lat());
                      inputLng.val(results[0].geometry.location.lng());
                      map.setCenter(results[0].geometry.location);
                      google.maps.event.trigger(map, 'resize');
                      if (map.marker != null) {
                        map.marker.setPosition(results[0].geometry.location);
                      } else {
                        var marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location
                        });
                      }
                    } else {
                      alert('Endereço não encontrado. Motivo: ' + status);
                    }
                });

            });

            return map;
        }

        var searchTime    = 500;
        var searchTimeout = null;

        function search(place, map) {

        }

        $('.input-map').each(function(){
            var input = $(this);
            var map   = renderMap(input);
            input.keyup(function(){
                if (searchTimeout != null)
                    clearTimeout(searchTimeout);

                searchTimeout = setTimeout(function(){
                    searchTimeout = null;
                    search(input.val(), map);
                }, searchTime);
            }).keypress(function(event) { return event.keyCode != 13; });

        });

     })(jQuery);
};
MAP;
    }

}

