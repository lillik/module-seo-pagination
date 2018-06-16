<?php
/**
 * @category   Lillik_SeoPagination
 * @package    Lillik_SeoPagination
 * @copyright  Copyright (c) 2017 LillikPro (http://lillik.pro)
 * @author     LillikPro <support@lillik.pro>
 */

namespace Lillik\SeoPagination\Model;


interface ConfigInterface
{

    const XML_PATH_GENERA_ENABLE = 'catalog/seo/lillik_seo_pagination_enable';

    /**
     * @return bool
     */
    public function isEnable();
}
