<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="<?=$this->url('/favicon.png')?>">
        <!-- Chrome, Firefox OS and Opera -->
        <meta name="theme-color" content="#003732">
        <!-- Windows Phone -->
        <meta name="msapplication-navbutton-color" content="#003732">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-status-bar-style" content="#003732">
        <style media="screen">
            .teste {
                sadfasdf
            }
        </style>
        <title>
            <?php if ($this->hasBlock('metaTitle')):?>
                <?=$this->renderBlock('metaTitle')?> |
            <?php endif?>
                YBY Natureza Condomínio Reserva
        </title>
        <?php if ($this->hasBlock('metaDescription')):?>
                <meta name="description"
                      content="<?= htmlentities(
                          $this->renderBlock('metaDescription'), ENT_QUOTES, 'utf-8'
                      )?>"
        <?php endif?>
        <?php if ($this->hasBlock('metaKeywords')):?>
                <meta name="keywords"
                      content="<?= htmlentities(
                          $this->renderBlock('metaKeywords'), ENT_QUOTES, 'utf-8'
                      )?>"
        <?php endif?>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?=$this->url('/assets/dist/css/site.main.min.css')?>" />
        <script src="http://habitax.housecrm.com.br/track/origem.js" async></script>
        <script language="javascript">
            var hc_dominio_chat	= "habitax.housecrm.com.br";
            var hc_filial	= "920";
        </script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-109238049-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-109238049-1');
        </script>
        <?=$this->renderBlock('head')?>
    </head>
    <body class="<?=$this->isMobile ? 'mobile' : ''?><?=$this->isTablet ?' tablet' : ''?><?=$this->isPhone ? ' phone' : ''?>">
        <?=$this->getContent()?>
        <div class="form-bar">
            <div class="container h-100">
                <?php if ($this->isPhone):?>
                    <?=$this->renderPartial('site/components/form-phone')?>
                <?php else:?>
                    <?=$this->renderPartial('site/components/form')?>
                <?php endif?>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="<?=$this->url('/assets/dist/js/site.min.js')?>" type="text/javascript"></script>
        <?php if (isset($this->hasForm) && $this->hasForm):?>
            <script>
                (function ($) {
                    $(document).ready(function () {
                        $('.carousel').each(function() {
                            var prev = '#carousel-prev';
                            var next = '#carousel-next';
                            var self = this;
                            var initSlick = function() {
                                if ($.fn.slick) {
                                    $(self).slick({
                                        slidesToShow: 1,
                                        arrows: true,
                                        prevArrow: prev,
                                        nextArrow: next,
                                        lazyLoad: 'ondemand',
                                        fade: true,
                                        controls: true,
                                        dots: true,
                                        adpativeHeight:true
                                    });
                                } else {
                                    setTimeout(initSlick, 500);
                                }
                            };
                            initSlick();
                        });
                        function downloadFolder() {
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', '<?= $this->url('/assets/site/folder-yby-natureza.pdf') ?>', true);
                            xhr.responseType = 'blob';

                            xhr.onload = function(e) {
                                if (this.status === 200) {
                                    var blob = this.response;
                                    download(
                                        blob,
                                        'folder-yby-natureza.pdf'
                                    );
                                }
                            };

                            xhr.send();
                        }
                        if ($.fn.formValidation) {
                            $('form.validate').formValidation({
                                framework: 'bootstrap',
                                locale: 'pt_BR',
                                live: 'submitted',
                                verbose: false,
                                row: {
                                    selector: '.form-group',
                                    valid: 'has-success',
                                    invalid: 'has-error',
                                    feedback: 'has-feedback'
                                },
                                err: {
                                    clazz: 'invalid-feedback'
                                }
                            }).on('err.field.fv', function (e, data) {
                                if (data.fv.getSubmitButton()) {
                                    data.fv.disableSubmitButtons(false);
                                }

                            }).on('success.field.fv', function (e, data) {
                                    if (data.fv.getSubmitButton()) {
                                        data.fv.disableSubmitButtons(false);
                                    }
                            }).on('err.form.fv', function (e) {
                                $(this).addClass('was-validated');
                            }).on('success.form.fv', function (e, data) {
                                e.preventDefault();
                                var $form = $(e.target);
                                $inputs = $form.find('input').attr('disabled', 'disabled');
                                $submit = $form.find('button');
                                $message = $('.form-bar__message');
                                $submit.text('Enviando...').attr('disabled', 'disabled');

                                //to not freeze
                                setTimeout(function() {
                                    var nome          = $('#field_nome').val();
                                    var email         = $('#field_email').val();
                                    var fullTelefone  = $('#field_telefone').val();
                                    var ddd           = fullTelefone.substring(1, 3);
                                    var telefone      = fullTelefone.substring(4);
                                    var result = JSON.parse(
                                        hc_envia_mensagem("13942", nome, email, ddd, telefone, 'Formulário pré cadastro')
                                    );
                                    if (result && result.sucesso) {
                                        $form.hide();

                                        $.ajax({
                                            url: '<?=$form->getAttr('action')?>',
                                            data: {
                                                nome: nome,
                                                email: email,
                                                telefone: fullTelefone
                                            },
                                            type: 'POST',
                                            beforeSend: function() {
                                                $message.text('O download do folder irá começar em breve. Muito obrigado.');
                                                $message.show().addClass('fadeInUp animated');
                                                downloadFolder();
                                            },
                                            complete: function() {
                                                setTimeout(function() {
                                                    $inputs.each(function() {
                                                        $(this).val('');
                                                    });
                                                    $('.form-bar__message').hide();
                                                    $form.show().addClass('fadeInDown animated');
                                                    <?php if ($this->isPhone):?>
                                                        $('.callout__action').trigger('click');
                                                    <?php endif?>
                                                    $inputs.attr('disabled', false);
                                                    $submit.attr('disabled', false).text('Enviar');
                                                }, 4000);
                                            }
                                        });
                                    } else {
                                        $form.hide();
                                        $message.text('Não foi possível adicionar suas informações. Tente novamente.');
                                        $message.show().addClass('shake animated');
                                        setTimeout(function() {
                                            $message.hide();
                                            $form.show().addClass('fadeInDown animated');
                                            $inputs.attr('disabled', false);
                                            $submit.attr('disabled', false).text('Enviar');
                                        }, 5000);

                                    }
                                }, 50);

                            });

                        }
                        var PhoneBehaviour = function (val) {
                            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                        };
                        var phoneOptions = {
                            onKeyPress: function(val, e, field, options) {
                                field.mask(PhoneBehaviour.apply({}, arguments), options);
                            }
                        };
                        $('.input-phone').mask(PhoneBehaviour, phoneOptions);
                        $('.main-menu__item a').on('click', function(e) {
                            e.preventDefault();
                            var target = $(this).attr('href');
                            $('html, body').animate({
                                scrollTop: $(target).offset().top
                            }, 500);
                        });
                    });
                    $('.callout__action').on('click', function() {
                        var bar = $('.form-bar');
                        if (!bar.hasClass('open')) {
                            bar.find('form').show();
                            bar.addClass('open');
                        } else {
                            bar.find('form').hide();
                            bar.removeClass('open');
                        }
                    });
                    <?php if ($this->isPhone):?>
                        $('.form-bar input').trigger('input');
                    <?php endif?>
                    var bgInterval = null;
                    <?php
                    $suffix = $this->isPhone ? '-mobile' : '';
                    ?>
                    var bgs        = [
                        '<?=$this->url('/assets/dist/images/bg-hero' . $suffix . '.jpg')?>',
                        '<?=$this->url('/assets/dist/images/bg-hero2' . $suffix . '.jpg')?>',
                        '<?=$this->url('/assets/dist/images/bg-hero3' . $suffix . '.jpg')?>'
                    ];
                    var totalBgs  = bgs.length;
                    var loadedBgs = 0;

                    var loadImage = function (src, callback) {
                        var img    = new Image();
                        img.onload = callback;
                        img.src    = src;
                    };

                    var loadComplete = function() {
                        var currentBg = 0;
                        $('.hero').css('background', 'url(' + bgs[currentBg] + ') no-repeat top center');
                        bgInterval = setInterval(function() {
                            currentBg = (currentBg + 1) % totalBgs;
                            $('.hero').css('background', 'url(' + bgs[currentBg] + ') no-repeat top center');
                        }, 5000);
                    };

                    var loadImageCallback = function() {
                        ++loadedBgs;
                        if (loadedBgs === totalBgs) {
                            loadComplete();
                            return;
                        }

                        loadImage(bgs[loadedBgs], loadImageCallback);
                    };


                    loadImage(bgs[0], loadImageCallback);




                })(jQuery.noConflict());
            </script>
        <?php endif?>
        <?=$this->renderBlock('footer')?>
    </body>
</html>
