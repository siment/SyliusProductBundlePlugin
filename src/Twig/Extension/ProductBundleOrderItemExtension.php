<?php

declare(strict_types=1);

namespace BitBag\SyliusProductBundlePlugin\Twig\Extension;

use BitBag\SyliusProductBundlePlugin\Entity\ProductInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProductBundleOrderItemExtension extends AbstractExtension
{
    /** @var RepositoryInterface */
    private $productBundleOrderItemRepository;

    /** @var EngineInterface */
    private $twig;

    public function __construct(RepositoryInterface $productBundleOrderItemRepository, Environment $twig)
    {
        $this->productBundleOrderItemRepository = $productBundleOrderItemRepository;
        $this->twig = $twig;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_render_product_bundle_order_items', [$this, 'renderProductBundleOrderItems'], ['is_safe' => ['html']]),
        ];
    }

    public function renderProductBundleOrderItems(OrderItemInterface $orderItem): string
    {
        /** @var ProductInterface $product */
        $product = $orderItem->getProduct();

        if (!$product->isBundle()) {
            return '';
        }

        $items = $this->productBundleOrderItemRepository->findBy([
            'orderItem' => $orderItem,
        ]);

        return $this->twig->render('@BitBagSyliusProductBundlePlugin/Admin/Order/Show/_productBundleOrderItems.html.twig', [
            'items' => $items,
        ]);
    }
}
