<?php
use Configuracoes\Model\Configuracao as C;
?>
<footer>
    <div class="footer__divider">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-items-center justify-content-center">
                    Siga
                    <a href="https://www.facebook.com/ybynatureza/" target="_blank" class="facebook">
                        <i class="fa fa-facebook-square fa-lg"></i></a>
                        <a href="https://www.instagram.com/ybynatureza/" target="_blank" class="instagram">
                        <i class="fa fa-instagram fa-lg"></i></a>
                    ybynatureza
                </div>
            </div>
        </div>
    </div>
    <div class="disclaimer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <?=C::get('texto_footer')?>
                    </p>
                </div>
            </div>
        </div>
    </div>

</footer>
