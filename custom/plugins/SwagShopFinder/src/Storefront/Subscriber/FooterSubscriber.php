<?php declare(strict_types=1);

namespace SwagShopFinder\Storefront\Subscriber;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use SwagShopFinder\Core\Content\ShopFinder\ShopFinderCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FooterSubscriber implements EventSubscriberInterface {

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    /**
     * @var EntityRepositoryInterface
     */
    private $shopFinderRepository;

    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepositoryInterface $shopFinderRepository
    )
    {
        $this->systemConfigService = $systemConfigService;
        $this->shopFinderRepository = $shopFinderRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            FooterPageletLoadedEvent::class => 'onPageletLoaded'
        ];
    }

    public function onPageletLoaded(FooterPageletLoadedEvent $event): void
    {
        if(!$this->systemConfigService->get('SwagShopFinder.config.showInStorefront')){
            return;
        }

        $shops = $this->fetchShops($event->getContext());

        $event->getPagelet()->addExtension('swag_shop_finder', $shops);
    }

    private function fetchShops(Context $context): ShopFinderCollection
    {
        $criteria = new Criteria();
        $criteria->addAssociation('country');
        $criteria->addFilter(new EqualsFilter('active', "1"));
        $criteria->setLimit(5);

        /** @var  ShopFinderCollection $shopFinderCollection */
        $shopFinderCollection = $this->shopFinderRepository->search($criteria, $context)->getEntities();

        return $shopFinderCollection;

    }
}
