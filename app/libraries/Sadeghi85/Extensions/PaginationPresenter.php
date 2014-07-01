<?php namespace Sadeghi85\Extensions;

class PaginationPresenter extends \Illuminate\Pagination\Presenter
{

    public function getActivePageWrapper($text)
    {
        return '<td class="current"><a href="">'.$text.'</a></td>';
    }

    public function getDisabledTextWrapper($text)
    {
        return '<td class="unavailable">'.$text.'</td>';
    }

    public function getPageLinkWrapper($url, $page, $rel = null)
    {
        return '<td><a href="'.$url.'">'.$page.'</a></td>';
    }

}