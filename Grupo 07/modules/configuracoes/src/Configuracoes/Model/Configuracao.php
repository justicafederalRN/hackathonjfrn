<?php
namespace Configuracoes\Model;

class Configuracao extends ConfiguracaoBase
{
    const KEY_NOTIFICACAO_EMAIL_TITULO = 'notificacao_email_titulo';
    const KEY_NOTIFICACAO_EMAIL_TEXTO  = 'notificacao_email_texto';
    const KEY_NOTIFICACAO_SMS_TEXTO    = 'notificacao_sms_texto';
    const KEY_DIAS_ENVIO_SMS           = 'enviar_sms_dias_antes';
    const KEY_DIAS_ENVIO_EMAIL         = 'enviar_email_dias_antes';

    public function init()
    {
        //SMS são 160 caracteres no máximo:
        //48 dígitos reservados para  do código do boleto
        //14 o nome do responsável
        //8 data de vencimento
        //variavel do nome é %nome% (6 caracteres)
        //variavel do codigo de barras é %codigo% (8 caracteres)
        //variavel do codigo de barras é %data% (6 caracteres)
        //Tamanho máximo = 160 - 48 - 14 - 8 + 6 + 8 + 6 = 110
        $this->text(self::KEY_NOTIFICACAO_SMS_TEXTO, 'Texto do SMS')
            ->mandatory()
            ->maxLength(110)
            ->setInfo(
                'Tamanho Máximo: <b>110 caracteres</b>.<br /> Utilize as seguintes variáveis para adicionar as informações dinâmicas no SMS:<br />' .
                '<table>' .
                '<tr><th>%nome%</th> <td>&nbsp;&nbsp;Primeiro nome do responsável</td></tr>' .
                '<tr><th>%data%</th> <td>&nbsp;&nbsp;Data de vencimento do título</td></tr>' .
                '<tr><th>%codigo%</th><td>&nbsp;&nbsp; Linha digitável do título</td></tr>' . 
                '<tr><th>%valor%</th><td>&nbsp;&nbsp; Valor do título</td></tr>' . 
                '</table>'  
            );

        $this->text(self::KEY_NOTIFICACAO_EMAIL_TITULO, 'Assunto do e-mail de notificação')->mandatory()->maxLength(100);
        $this->richText(self::KEY_NOTIFICACAO_EMAIL_TEXTO, 'Texto do e-mail de notificação')
            ->mandatory()
            ->setInfo(
                'Utilize as seguintes variáveis para adicionar as informações dinâmicas no email:<br />' .
                '<table>' .
                '<tr><th>%nome%</th> <td>&nbsp;&nbsp;Nome do responsável</td></tr>' .
                '<tr><th>%referencia%</th><td>&nbsp;&nbsp; Mês/ano de referência</td></tr>' . 
                '<tr><th>%dias%</th><td>&nbsp;&nbsp; Quantidade de dias para o vencimento do boleto</td></tr>' . 
                '<tr><th>%titulo%</th><td>&nbsp;&nbsp; Informações sobre o título</td></tr>' . 
                '</table>'  
            );

        $this->options(self::KEY_DIAS_ENVIO_EMAIL, 'Enviar e-mail de cobrança', self::getDiasOptions());
        $this->options(self::KEY_DIAS_ENVIO_SMS, 'Enviar SMS de cobrança', self::getDiasOptions());
    }

    public static function getDaysToSendEmailNotification()
    {
        return self::get(self::KEY_DIAS_ENVIO_EMAIL, '');
    }

    public static function getDaysToSendSmsNotification()
    {
        return self::get(self::KEY_DIAS_ENVIO_SMS, '');
    }

    private static function getDiasOptions()
    {
        $options = [
            0 => 'No dia da cobrança',
            1 => 'Com 1 dia de antecedência'
        ];

        for ($i = 2; $i <= 20; ++$i) {
            $options[$i] = 'Com ' . $i . ' dias de antecedência';
        }

        return $options;
    }
}
