import { observable, action, computed, reaction, runInAction } from 'mobx';
import { createContext } from 'react';
//mobx.configure({ enforceActions: "observed" })
class CartStore {
    @observable productsInCart = [];
    @observable state = "pending" // "pending" / "done" / "error"
    @observable msg = "" // "pending" / "done" / "error"

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

    async fetchCart() {
      let apiCart = await fetch(`http://www.mocky.io/v2/5e4d11932d00007f00c0d983`);
      let jsonCart = await apiCart.json();
      return jsonCart.data.items;
    }

    @action
    async fetchProjects() {
        this.productsInCart = []
        this.state = "pending"
        try {
            const projects = await this.fetchCart();
            runInAction(() => {
                this.state = "done"
                this.productsInCart = projects
            })
        } catch (error) {
            runInAction(() => {
                this.state = "error"
                console.log(error)
            })
        }
    }
} 

const cartStore = new CartStore();
export default cartStore; // для классов
export const CartStoreContext = createContext(new CartStore()); // для функциональных компонентов