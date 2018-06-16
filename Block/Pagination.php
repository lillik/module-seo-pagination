<?php
/**
 * @category   Lillik_SeoPagination
 * @package    Lillik_SeoPagination
 * @copyright  Copyright (c) 2017 LillikPro (http://lillik.pro)
 * @author     LillikPro <support@lillik.pro>
 */

namespace Lillik\SeoPagination\Block;

use Lillik\SeoPagination\Model\ConfigInterface;
use Magento\Catalog\Model\Category;
use Magento\Framework\View\Element\Template;
use Lillik\SeoPagination\Model\Link;

class Pagination extends Template
{
    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    private $layerResolver;

    /**
     * @var
     */
    private $currentCategory;

    /**
     * @var \Magento\Theme\Block\Html\Pager
     */
    private $pager;

    /**
     * @var \Magento\Theme\Block\Html\Pager
     */
    private $currentPager;

    /**
     * @var \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    private $toolbar;
    /**
     * @var ConfigInterface
     */
    private $configModule;

    /**
     * Pagination constructor.
     *
     * @param Template\Context                                   $context
     * @param \Magento\Catalog\Model\Layer\Resolver              $layerResolver
     * @param \Magento\Theme\Block\Html\Pager                    $pager
     * @param \Magento\Catalog\Block\Product\ProductList\Toolbar $toolbar
     * @param ConfigInterface                                    $configModule
     * @param array                                              $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Theme\Block\Html\Pager $pager,
        \Magento\Catalog\Block\Product\ProductList\Toolbar $toolbar,
        ConfigInterface $configModule,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->layerResolver = $layerResolver;
        $this->pager = $pager;
        $this->toolbar = $toolbar;
        $this->configModule = $configModule;
    }

    /**
     * @return Category
     */
    public function getCurrentCategory(): Category
    {
        if (!$this->currentCategory) {
            $this->currentCategory = $this->layerResolver->get()->getCurrentCategory();
        }
        return $this->currentCategory;
    }

    /**
     * @return array
     */
    public function getPageHeaderLinks(){
        $result = [];

        if ($this->configModule->isEnable()) {
            $pager = $this->getCurrentPager();
            if ($pager->getCurrentPage() > 1) {
                $result[] = new Link('prev', $this->getPreviousPageUrl());
            }

            if ($pager->getCurrentPage() < $pager->getLastPageNum()) {
                $result[] = new Link('next', $pager->getNextPageUrl());
            }
        }

        return $result;
    }

    /**
     * @return \Magento\Theme\Block\Html\Pager
     */
    public function getPager(): \Magento\Theme\Block\Html\Pager
    {
        return $this->pager;
    }

    /**
     * @return \Magento\Theme\Block\Html\Pager
     */
    public function getCurrentPager(): \Magento\Theme\Block\Html\Pager
    {
        if (!$this->currentPager) {
            $this->currentPager = $this->getPager()
                ->setCollection(
                    $this->getCurrentCategory()->getProductCollection()
                );
            $this->currentPager->setShowPerPage($this->getLimit());
        }

        return $this->currentPager;
    }

    /**
     * @return \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    public function getToolbar(): \Magento\Catalog\Block\Product\ProductList\Toolbar
    {
        return $this->toolbar;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->getToolbar()->getLimit();
    }

    /**
     * @return string
     */
    private function getPreviousPageUrl(): string
    {
        $pager = $this->getCurrentPager();
        if ($pager->getCurrentPage() > 2) {
            return $pager->getPreviousPageUrl();
        }
        return $this->getFirstPageUrl();
    }

    /**
     * @return string
     */
    private function getFirstPageUrl(): string
    {
        $pager = $this->getCurrentPager();
        return $pager->getPagerUrl([$pager->getPageVarName() => null]);
    }

}
