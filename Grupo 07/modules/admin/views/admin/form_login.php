<form class="span4 form-stacked" style="display:block;margin:0 auto 40px auto;float:none" method="post" 
      action="<?php echo URL::site(Request::current()->uri()) . (empty($redirect_url) ? '' : '?redirect=' . $redirect_url) ?>" 
      id="form-login">
        <?php echo AdminUi::get_flash()?>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
        <?php echo $error_message ?>
        </div>
    <?php endif; ?>
    <label>Login:</label>
    <input type="text" name="email" id="login" class="span4" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
    <label>Senha:</label>
    <input type="password" id="senha" name="senha" class="span4">
    <div style="text-align:right;margin-top:10px">
        <button type="submit" class="btn btn-large">Entrar</button>
    </div>
</form>
