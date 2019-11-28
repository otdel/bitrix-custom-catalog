<?php

namespace Oip\RelevantProducts;

use Oip\RelevantProducts\Config\Configuration;

class RelevantCategory
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var RelevantProduct[] $relevantProducts */
    private $relevantProducts;
    /** @var int */
    private $viewsCount;
    /** @var int */
    private $likesCount;
    /** @var int */
    private $weight;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return RelevantProduct[]
     */
    public function getRelevantProducts()
    {
        return $this->relevantProducts;
    }

    /**
     * @param RelevantProduct[] $relevantProducts
     */
    public function setRelevantProducts($relevantProducts)
    {
        $this->relevantProducts = $relevantProducts;
    }

    /**
     * @return int
     */
    public function getViewsCount()
    {
        return $this->viewsCount;
    }

    /**
     * @param int $viewsCount
     */
    public function setViewsCount($viewsCount)
    {
        $this->viewsCount = $viewsCount;
    }

    /**
     * @return int
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }

    /**
     * @param int $likesCount
     */
    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return int
     */
    public function calcViewsCount()
    {
        // Обнуляем количество просмотров
        $this->viewsCount = 0;

        // Пробегаемся по всем товарам в категории и считаем сколько в сумме лайков
        foreach ($this->relevantProducts as $relevantProduct) {
            $this->viewsCount += $relevantProduct->getViewsCount();
        }

        return $this->viewsCount;
    }

    /**
     * @return int
     */
    public function calcLikesCount()
    {
        // Обнуляем количество лайков
        $this->likesCount = 0;

        // Пробегаемся по всем товарам в категории и считаем сколько в сумме лайков
        foreach ($this->relevantProducts as $relevantProduct) {
            $this->likesCount += $relevantProduct->getLikesCount();
        }

        return $this->likesCount;
    }

    /**
     * @return int
     */
    public function calcWeight()
    {
        // Обнуляем вес
        $this->weight = 0;

        // Пробегаемся по всем товарам в категории и считаем вес
        foreach ($this->relevantProducts as $relevantProduct) {
            $this->weight += $relevantProduct->getViewsCount() * Configuration::PRODUCT_VIEW_WEIGHT;
            $this->weight += $relevantProduct->getLikesCount() * Configuration::PRODUCT_LIKE_WEIGHT;
        }

        return $this->weight;
    }

    /**
     * Фукнция пересчета всех счетчиков в категории
     */
    public function calcAllCounters() {
        $this->calcViewsCount();
        $this->calcLikesCount();
        $this->calcWeight();
    }

    /**
     * @param RelevantProduct $product
     */
    public function addRelevantProduct(RelevantProduct $product)
    {
        // Каждый товар в категории должен быть уникальным (ключевое поле - id)
        foreach ($this->relevantProducts as $existingProduct) {
            if ($existingProduct->getId() == $product->getId()) {
                return;
            }
        }
        // Добавляем товар в общий список товаров категории
        $this->relevantProducts[] = $product;
    }

}
