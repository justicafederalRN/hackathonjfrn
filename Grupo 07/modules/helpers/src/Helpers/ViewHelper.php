<?php
namespace Helpers;

class ViewHelper
{
    public static function paginate(
        $view,
        $currentPage,
        $totalPages,
        $routeName,
        $params = array(),
        $displayPages = 9
    ) {
        if ($totalPages < 2) {
            return '';
        }

        $query_string = '';
        $query_params = $_GET;




        $half = floor($displayPages / 2);
        $first_page = max(1, $currentPage - $half);
        $last_page = min($totalPages, max($displayPages, $currentPage + $half));
        $first_page = max(1, $last_page - $displayPages + 1);
        $show_before_dots = $first_page != 1;
        $show_after_dots = $last_page != $totalPages;
        $show_prev = $currentPage > 1;
        $show_next = $currentPage < $totalPages;

        $paginacao = '<div><ul class="pagination justify-content-center">';

        if ($show_prev) {
            $query_params['page'] = $currentPage - 1;

            $href = $view->routeUrl($routeName, $params);
            $paginacao .= '<li class="page-item prev"><a href="'
                . $href . '?' . http_build_query($query_params) . '"'
                . ' title="Página anterior" class="page-link">&laquo;</a></li>';
        }

        if ($show_before_dots) {
            $paginacao .= '<li class="page-item disabled"><a href="javascript:void(0);" class="page-link">...</a></li>';
        }

        for ($i = $first_page; $i <= $last_page; $i++) {
            $class = '';
            $title = 'Ir para página ' . $i;
            $query_params['page'] = $i;
            $tmp_url = $view->routeUrl($routeName, $params);

            $is_current = $i == $currentPage;

            if ($is_current) {
                $class = ' active';
                $title = 'Página atual';
                $tmp_url = '#';
            }

            $paginacao .= '<li class="page-item ' . $class . '"><a href="' . $tmp_url . '?' . http_build_query($query_params) . '" title="'
                . $title . '" class="page-link">' . $i . '</a></li>';
        }

        if ($show_after_dots) {
            $paginacao .= '<li class="page-item disabled"><a href="javascript:void(0);" class="page-link">...</a></li>';
        }

        if ($show_next) {
            $query_params['page'] = $currentPage + 1;
            $href = $view->routeUrl($routeName, $params);
            $paginacao .= '<li class="page-item next"><a href="'
                . $href . '?' . http_build_query($query_params) . '"'
                . ' title="Página seguinte" class="page-link">&raquo;</a></li>';
        }

        $paginacao .= '</ul></div>';

        return $paginacao;
    }


    public static function getMonthName($monthNumber)
    {
        if ($monthNumber < 1 || $monthNumber > 12) {
            return null;
        }

        switch ($monthNumber) {
            case 1:
                return 'Janeiro';
            case 2:
                return 'Fevereiro';
            case 3:
                return 'Março';
            case 4:
                return 'Abril';
            case 5:
                return 'Maio';
            case 6:
                return 'Junho';
            case 7:
                return 'Julho';
            case 8:
                return 'Agosto';
            case 9:
                return 'Setembro';
            case 10:
                return 'Outubro';
            case 11:
                return 'Novembro';
            case 12:
                return 'Dezembro';
        }
    }
}

