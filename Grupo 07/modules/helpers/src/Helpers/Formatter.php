<?php
namespace Helpers;

class Formatter
{
    public static function statusInterval($from, $to, $gender = 'm')
    {
        $suffix = $gender == 'f' ? 'a' : 'o';
        if (empty($from) && empty($to)) {
            return self::label('green', 'Publicad' . $suffix);
        }

        /* @var $from \DateTime */
        $from = date_create_from_format('Y-m-d H:i:s', $from);

        if (empty($from)) {
            return self::label('black', 'Desconhecid' . $suffix);
        }

        $now  = new \DateTime();
        $now  = $now->getTimestamp();
        $from = $from->getTimestamp();

        $to   = empty($to) ? null : date_create_from_format('Y-m-d H:i:s', $to);

        if (empty($to)) {
            if ($now >= $from) {
                return self::label('green', 'Publicad' . $suffix);
            } else {
                return self::label('orange', 'Aguardando');
            }
        }
        $to = $to->getTimestamp();

        if ($now < $from) {
            return self::label('orange', 'Aguardando');
        }

        if ($now > $to) {
            return self::label('red', 'Expirad' . $suffix);
        }

        return self::label('green', 'Publicad' . $suffix);
    }

    public static function dateTime($date, $fromFormat = 'Y-m-d H:i:s')
    {
        $date = empty($date) ? null : date_create_from_format($fromFormat, $date);

        return empty($date) ? '&ndash;' : $date->format('d/m/Y H:i');
    }

    public static function dateString($date, $fromFormat = 'Y-m-d H:i:s')
    {
        $date = empty($date) ? null : date_create_from_format($fromFormat, $date);

        if (empty($date)) {
            return '-';
        }

        $months = [
            1 => 'janeiro',
            'fevereiro',
            'março',
            'abril',
            'maio',
            'junho',
            'julho',
            'agosto',
            'setembro',
            'outubro',
            'novembro',
            'dezembro'
        ];
        $time  = $date->getTimestamp();
        $day   = date('d', $time);
        $month = $months[date('n', $time)];
        $year  = date('Y', $time);

        return sprintf(
            '%s de %s de %s',
            $day,
            $month,
            $year
        );
    }

    public static function monthName($date, $fromFormat = 'Y-m-d H:i:s')
    {
        $date = empty($date) ? null : date_create_from_format($fromFormat, $date);

        if (empty($date)) {
            return '';
        }

        $months = [
            1 => 'janeiro',
            'fevereiro',
            'março',
            'abril',
            'maio',
            'junho',
            'julho',
            'agosto',
            'setembro',
            'outubro',
            'novembro',
            'dezembro'
        ];

        return $months[date('n', $date->getTimestamp())];
    }

    public static function address($row)
    {
        $endereco = isset($row['endereco']) ? $row['endereco'] : null;
        if (empty($endereco)) {
            return '';
        }

        $address = [$endereco];


        foreach (['numero', 'bairro', 'complemento'] as $field) {
            if (!empty($row[$field])) {
                $address[] = $row[$field];
            }
        }

        $state = '';
        if (!empty($row['cidade'])) {
            $state = $row['cidade'];
        }

        if (!empty($row['uf'])) {
            $state = empty($state) ? $row['uf'] : $state . '/' . $row['uf'];
        }

        $address[] = $state;

        if (!empty($row['cep'])) {
            $address[] = '<br />CEP: ' . $row['cep'];
        }

        return implode(', ', $address);
    }

    public static function youtubeEmbed($youtubeUrl)
    {
        parse_str(parse_url($youtubeUrl, PHP_URL_QUERY), $queryString);
        $youtube = '<iframe width="560" height="315" src="//www.youtube.com/embed/' . $queryString ['v'] . '?controls=0&showinfo=0" frameborder="0" allowfullscreen></iframe>';
        return $youtube;
    }

    private static function label($color, $text)
    {
        return sprintf(
            '<span class="label bg-%s">%s</span>',
            $color,
            $text
        );
    }

    public static function mask($mask, $value, $errorReturn = null, $placeHolder = '#')
    {
        $strLen            = strlen($value);
        if (!$strLen)
            return $errorReturn;
        $totalPlaceHolders = strlen(
            preg_replace('/[^' . preg_quote($placeHolder, '/') . ']/', '', str_replace('\\' . $placeHolder, '', $mask)));

        if ($totalPlaceHolders != $strLen)
            return $errorReturn;
        $parts    = str_split($mask);
        $pointer  = 0;
        $isEscape = false;
        $out      = '';
        foreach ($parts as $p) {
            if ($p == '\\') {
                if ($isEscape)
                    $out      .= '\\';
                else
                    $isEscape = true;
            } else if ($p == $placeHolder) {
                if ($isEscape) {
                    $out      .= $placeHolder;
                    $isEscape = false;
                } else
                    $out .= $value[$pointer++];
            } else {
                if ($isEscape) {
                    $out      .= '\\';
                    $isEscape = false;
                } else
                    $out .= $p;
            }
        }
        return $out;
    }

}
