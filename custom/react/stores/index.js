import CartStore from './CartStore'
import CounterStore from './CartStore'
import catalogStore from './CatalogStore'

export default {
    cartStore: new CartStore(),
    counterStore: new CounterStore(),
    catalogStore: new CatalogStore(),
}