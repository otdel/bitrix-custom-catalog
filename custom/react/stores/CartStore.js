  
import { observable, action, computed, reaction } from 'mobx';
import { createContext } from 'react';

class CartStore {
    @observable productsInCart = [];
    @observable counter = 0;

    @computed get count() {
        return this.productsInCart.length;
    }
    @computed get totalAmount() {
        let total = 0;
        for (let item of this.productsInCart) {
          total = total + (item.price)
        }
        return total;
    }
    @action.bound
    addToCart(product) {
        this.productsInCart.push(product);
    }
    @action.bound
    removeFromCart(product) {
      const index = this.productsInCart.indexOf(product);
      if (index >= 0) {
        this.productsInCart.splice(index, 1);
      }
    }
    @action.bound
    fetchCart() {
        return this.productsInCart;
    }
} 

export default new CartStore(); // для классов
export const CartStoreContext = createContext(new CartStore()); // для функциональных компонентов