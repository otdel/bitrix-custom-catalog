<?php


namespace Oip\SocialStore\Product\Price;


class Price
{
    /** @var int $productId */
    private $productId;

    /** @var float $value */
    private $value;

    /**
     * @param int $productId
     * @param float $value
     */
    public function __construct(int $productId, float $value)
    {
        $this->productId = $productId;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }


}