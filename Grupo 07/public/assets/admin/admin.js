(function ($) {
    $(document).ready(function() {
        $('[data-confirm]').each(function(e) {
            $(this).on('click', function() {
                var href = $(this).attr('href');
                var type = $(this).data('confirmType');
                var text = $(this).data('confirm');

                swal({
                    type: type,
                    text: text,
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não'
                }).then(function() {
                    window.location.href = href;
                }).catch($.noop);

                return false;
            });
        });


        tinymce.init({
            selector: '.input-richtext',
            height: 500,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code codesample imagetools',
                'textcolor',
            ],
            image_caption: true,
            toolbar1: 'insert | fontsizeselect | formatselect | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link  | forecolor backcolor',
            //body_class: 'interna__content dinamyc-content',
            // ontent_css: [
            //     Admin.baseUrl + 'assets/site/plugins/bootstrap/dist/css/bootstrap.min.css',
            //     Admin.baseUrl + 'site/css/font-awesome/css/font-awesome.min.css',
            //     Admin.baseUrl + 'assets/site/css/site.css?' + (new Date().getTime())
            // ],
            //content_style: '.interna__content {margin: 0; padding: 20px !important;}',
            style_formats: [
                {title: 'Cabeçalhos', items: [
                    {title: 'Cabeçalho 1', format: 'h1'},
                    {title: 'Cabeçalho 2', format: 'h2'},
                    {title: 'Cabeçalho 3', format: 'h3'},
                    {title: 'Cabeçalho 4', format: 'h4'},
                    {title: 'Cabeçalho 5', format: 'h5'},
                    {title: 'Cabeçalho 6', format: 'h6'}
                ]},
                {title: 'Inline', items: [
                    {title: 'Negrito', icon: 'bold', format: 'bold'},
                    {title: 'Itálico', icon: 'italic', format: 'italic'},
                    {title: 'Underline', icon: 'underline', format: 'underline'},
                    {title: 'Strikethrough', icon: 'strikethrough', format: 'strikethrough'},
                    {title: 'Superscript', icon: 'superscript', format: 'superscript'},
                    {title: 'Subscript', icon: 'subscript', format: 'subscript'},
                    {title: 'Código', icon: 'code', format: 'code'},
                    {title: 'Pequeno', icon: 'small', format: 'small'}
                ]},
                {title: 'Blocos', items: [
                    {title: 'Parágrafo', format: 'p'},
                    {title: 'Citação', format: 'blockquote'},
                    {title: 'Div', format: 'div'},
                    {title: 'Pre', format: 'pre'}
                ]},
                {title: 'Alinhamento', items: [
                    {title: 'Esquerda', icon: 'alignleft', format: 'alignleft'},
                    {title: 'Centro', icon: 'aligncenter', format: 'aligncenter'},
                    {title: 'Direita', icon: 'alignright', format: 'alignright'},
                    {title: 'Justificado', icon: 'alignjustify', format: 'alignjustify'}
                ]}
            ]
        });

        $('.input-datetime').each(function() {
            $(this).datetimepicker({
                locale: 'pt-BR',
                icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-2x fa-arrow-down"
                }
            });
        });


        $('.input-time').each(function() {
            $(this).timeEntry({
                show24hours: true,
                spinnerimage: null,
                useMouseWheel: false
            });
        });

        $('.input-habtm').select2();



        var PhoneBehaviour = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        };
        var phoneOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(PhoneBehaviour.apply({}, arguments), options);
            }
        };
        $('.form-validation').formValidation({
            framework: 'bootstrap',
            locale: 'pt_BR',
            live: 'submitted',
            verbose: false
        }).on('err.field.fv', function(e, data) {
            if (data.fv.getSubmitButton()) {
                data.fv.disableSubmitButtons(false);
            }
        }).on('success.field.fv', function(e, data) {
            if (data.fv.getSubmitButton()) {
                data.fv.disableSubmitButtons(false);
            }
        }).on('err.form.fv', function(e) {
            if (console) {
                console.log(e.fv.getInvalidFields()());
            }
        });

        $('.input-phone').mask(PhoneBehaviour, phoneOptions);
        $('.input-date').datepicker({
            autoclose: true,
            language: 'pt-BR',
            format: 'dd/mm/yyyy',
            todayHighlight: true
        });

        $('.input-color-container').colorpicker({
            colorSelectors: {
                '#000000': '#000000',
                '#FFFFFF': '#ffffff',
                '#F56954': '#F56954',
                '#00A65A': '#00A65A',
                '#3C8BDC': '#3C8BDC',
                '#001F3F': '#001F3F',
                '#605CA8': '#605CA8',
                '#F39C12': '#F39C12',
                '#D81B60': '#D81B60'
            }
        });

        $('input[type=radio],.fancy-checkbox').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });



        $('.input-dependent-options').each(function() {
            var otherFieldName = $(this).data('dependentField');
            var ajaxHandler    = $(this).data('ajaxHandler');
            var $field         = $(this);
            var value          = $field.data('dependentValue');


            $('[name=' + otherFieldName + ']').on('change', function() {
                $field.html('');
                var val = $(this).val();
                if (val == '') {
                    $field.attr('disabled', true);
                    return;
                }

                var data = {};
                data[otherFieldName] = val;

                $.ajax({
                    dataType: 'json',
                    method: 'post',
                    url: Admin.baseUrl + 'ajax/' + ajaxHandler,
                    data: data,
                    success: function(result) {
                        $field.html('');
                        if (!result) {
                            $field.html('<option value="">(Nenhum resultado encontrado.)</option>');
                            return;
                        }
                        $field.append('<option value="">(Selecione)</option>');
                        var hasAny = false;
                        for (var key in result) {
                            var item = result[key];
                            hasAny   = true;
                            if (item.id == value) {
                                $field.append('<option selected value="' + item.id + '">' + item.text + '</option>');
                            } else {
                                $field.append('<option value="' + item.id + '">' + item.text + '</option>');
                            }
                        }
                        if (hasAny) {
                            $field.attr('disabled', false);
                        } else {
                            $field.html('<option value="">(Nenhum resultado encontrado.)</option>');
                        }
                    },
                    beforeSend: function() {
                        $field.attr('disabled', true);
                        $field.html('<option>Carregando...</option>');
                    }
                });
            });
            if (!value) {
                $field.trigger('change');
            }
        });


        $('[data-contact-card]').each(function() {
            var contactId = $(this).data('contactCard');
            var $this     = $(this);

            $this.webuiPopover({
                type: 'async',
                url: Admin.baseUrl + 'ajax/responsaveis?id=' + contactId,
                content: function(data) {
                    console.log(data);
                    return data;
                },
                trigger: 'click',
                animation: 'pop',
                closeable: true
            });
            // $.ajax({
            //     url: Admin.baseUrl + 'ajax/contact-card?id=' + id,
            //     success: function(content) {
            //         $('#' + contentId).html(content);
            //     }
            // });
        });
        $('.tooltips, .tooltip').tooltip();

        var $cep = $('#field_cep');
        if ($cep.length > 0) {
            var $endereco = $('#field_endereco');
            var $bairro = $('#field_bairro');
            var $cidade = $('#field_municipio_id');
            var $numero = $('#field_numero');

            function limpa_formulario_cep() {
                // Limpa valores do formulÃ¡rio de cep.
                $endereco.val("");
                $bairro.val("");
                $cidade.val("");
                $uf.val("");
            }

            $cep.blur(function() {
                var cep = $(this).val();
                if (cep != "") {

                    var validacep = /^[0-9]{5}-?[0-9]{3}$/;
                    if (validacep.test(cep)) {
                        $endereco.val("...");
                        $bairro.val("...");
                        $cidade.val("...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $endereco.val(dados.logradouro);
                                $bairro.val(dados.bairro);
                                var cidadeUf = (dados.localidade + '/' + dados.uf).toLowerCase();
                                var options = $cidade.find('option');
                                for (var i = 0; i < options.length; i++) {
                                    var option = options[i];
                                    if (option.text.toLowerCase() === cidadeUf) {
                                        $cidade.val(option.value);
                                        break;
                                    }
                                }
                                $numero.focus();
                            } //end if.
                            else {
                                //CEP pesquisado nÃ£o foi encontrado.
                                limpa_formulario_cep();
                                alert("CEP nÃ£o encontrado.");
                            }
                        });
                    } else {
                        //cep Ã© invÃ¡lido.
                        limpa_formulario_cep();
                        alert("Formato de CEP invÃ¡lido.");
                    }
                } else {
                    //cep sem valor, limpa formulÃ¡rio.
                    limpa_formulario_cep();
                }
            });
        }
});
})(jQuery);
