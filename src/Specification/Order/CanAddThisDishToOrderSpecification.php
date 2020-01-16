<?php


namespace App\Specification\Order;


use App\Entity\Dish;
use App\Entity\Order;
use Tanigami\Specification\Specification;

class CanAddThisDishToOrderSpecification extends Specification
{
    /**
     * @var Order
     */
    private $order;

    /**
     * CanAddThisDishToOrderSpecification constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param Dish $dish
     * @return bool
     */
    public function isSatisfiedBy($dish): bool
    {
        $category = $dish->getCategory();
        $limit = $category->getDishLimit();
        $dishes = array_filter($this->order->getDishes()->toArray(), function (Dish $d) use ($category){
            return $d->getCategory() === $category;
        });
        return ($limit == 0 || count($dishes) < $limit );
    }
}