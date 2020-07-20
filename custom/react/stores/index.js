import CartStore from './CartStore'
import CounterStore from './CartStore'
import ProductStore from './ProductStore' 

export default {
    cartStore: new CartStore(),
    counterStore: new CounterStore(),
    productStore: new ProductStore(),
}