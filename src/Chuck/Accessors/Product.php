<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck\Accessors;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;
use Chuckbe\Chuckcms\Models\Repeater as ProductModel;
use Exception;
use ChuckEcommerce;
use Illuminate\Support\Facades\Schema;

use App\Http\Requests;

class Product
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository) 
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Return the product attributes array
     *
     * @var ProductModel $product
     **/
    private function getProductAttributes(ProductModel $product)
    {
        return $this->productRepository->getAttributes($product);
    }

    public function getFeatured()
    {
        return $this->productRepository->getFeatured();
    }

    public function forCollection(string $collection, $parent = null)
    {
        return $this->productRepository->forCollection($collection, $parent);
    }

    public function forBrand(string $brand)
    {
        return $this->productRepository->forBrand($brand);
    }

    public function combinationBySKU($sku)
    {
        return $this->productRepository->combinationBySKU($sku);
    }

    public function defaultCombination(ProductModel $product)
    {
        return $this->productRepository->defaultCombination($product);
    }

    public function defaultCombinationKey(ProductModel $product)
    {
        return $this->productRepository->defaultCombinationKey($product);
    }

    public function defaultSKU(ProductModel $product)
    {
        return $this->productRepository->defaultSKU($product);
    }

    public function title(ProductModel $product)
    {
        return $this->productRepository->title($product);
    }

    public function brand(ProductModel $product)
    {
        return $this->productRepository->brand($product);
    }

    public function sku($sku)
    {
        return $this->productRepository->sku($sku);
    }

    public function collection(ProductModel $product)
    {
        return $this->productRepository->getCollection($product);
    }

    public function images(ProductModel $product)
    {
        return $this->productRepository->images($product);
    }

    public function inStock(ProductModel $product)
    {
        return $this->productRepository->inStock($product);
    }

    public function featuredImage(ProductModel $product)
    {
        return $this->productRepository->featuredImage($product);
    }

    public function featuredImageBySKU($sku)
    {
        return $this->productRepository->featuredImageBySKU($sku);
    }

    public function getFullUrlBySKU($sku)
    {
        return $this->productRepository->getFullUrlBySKU($sku);
    }

    public function getImageHeight(array $image)
    {
        return $this->productRepository->getImageHeight($image);
    }

    public function getImageWidth(array $image)
    {
        return $this->productRepository->getImageWidth($image);
    }

    public function fullUrl(ProductModel $product)
    {
        return $this->productRepository->fullUrl($product);
    }

    public function hasDiscount(ProductModel $product)
    {
        return $this->productRepository->hasDiscount($product);
    }

    public function priceRaw(ProductModel $product, $sku)
    {
        return $this->productRepository->priceRaw($product, $sku);
    }

    public function priceNoTaxRaw(ProductModel $product, $sku)
    {
        return $this->productRepository->priceNoTaxRaw($product, $sku);
    }

    public function lowestPrice(ProductModel $product)
    {
        return $this->productRepository->lowestPrice($product);
    }

    public function highestPrice(ProductModel $product)
    {
        return $this->productRepository->highestPrice($product);
    }

    public function taxRate(ProductModel $product)
    {
        return $this->productRepository->taxRate($product);
    }

    public function taxRateBySKU($sku)
    {
        return $this->productRepository->taxRateBySKU($sku);
    }

    public function taxRateForSKU(ProductModel $product, $sku)
    {
        return $this->productRepository->taxRateForSKU($product, $sku);
    }

    public function quantity(ProductModel $product, $sku)
    {
        return $this->productRepository->quantity($product, $sku);
    }

    public function getOptions(ProductModel $product, $sku, $options = [])
    {
        return $this->productRepository->getOptions($product, $sku, $options);
    }

    public function isBuyable(ProductModel $product)
    {
        return array_key_exists('is_buyable', $product->json) ? $product->json['is_buyable'] : false;
    }

    public function isDisplayed(ProductModel $product)
    {
        return array_key_exists('is_displayed', $product->json) ? $product->json['is_displayed'] : false;
    }

}