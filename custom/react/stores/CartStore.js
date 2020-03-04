import { observable, action, computed, reaction, runInAction } from 'mobx';
import { createContext } from 'react';
//mobx.configure({ enforceActions: "observed" })
class CartStore {
    @observable productsInCart = [];
    @observable state = "pending"; // "pending" / "done" / "error"
    @observable stateRemove = "pending"; // "pending" / "done" / "error"
    @observable stateRemoveAll = "pending"; // "pending" / "done" / "error"
    @observable msg = ""; // "pending" / "done" / "error"
    @observable userId = undefined;

    @computed get count() {
        return this.productsInCart.length;
    }
    @computed get totalAmount() {
        let total = 0;
        for (let item of this.productsInCart) {
            total = total + item.price
        }
        return total;
    }
    @action.bound
    setUserId(userId) {
        this.userId = userId;
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

    /* remove All */
    async fetchRemoveAllCart() {
        let api = await fetch(`/api/v1/cart/removeAll?cartUserId=${this.userId}`);
        let json = await api.json();
        return json;
    }

    @action.bound
    async removeAllCart() {
        this.stateRemoveAll = "pending"
        try {
            const response = await this.fetchRemoveAllCart();
            runInAction(() => {
                if (response.status === "success") {
                    this.stateRemoveAll = "done"
                    this.productsInCart = []
                } else {
                    this.stateRemoveAll = "error"
                }
            })
        } catch (error) {
            runInAction(() => {
                this.stateRemoveAll = "error"
                console.log(error)
            })
        }
    }

    /* remove product */
    async fetchRemoveProduct(productId) {
        let api = await fetch(`/api/v1/cart/remove?cartUserId=${this.userId}&productId=${productId}`);
        let json = await api.json();
        return JSON.parse(json.data);
    }

    @action
    async removeProduct(product) {
        this.stateRemove = "pending"
        try {
            const response = await this.fetchRemoveProduct(product.id);
            runInAction(() => {
                if (response.status === "success") {
                    this.stateRemove = "done"
                    const index = this.productsInCart.indexOf(product);
                    if (index >= 0) {
                        this.productsInCart.splice(index, 1);
                    }
                } else {
                    this.stateRemove = "error"
                }
            })
        } catch (error) {
            runInAction(() => {
                this.stateRemove = "error"
                console.log(error)
            })
        }
    }

    /* get current Cart by user */
    async fetchCart() {
        let api = await fetch(`/api/v1/cart/getByUserId?cartUserId=${this.userId}`);
        let json = await api.json();
        return JSON.parse(json.data);
    }

    @action
    async getCart() {
        this.productsInCart = []
        this.state = "pending"
        try {
            const products = await this.fetchCart();
            runInAction(() => {
                this.state = "done"
                this.productsInCart = products
            })
        } catch (error) {
            runInAction(() => {
                this.state = "error"
                console.log(error)
            })
        }
    }

    @action.bound
    checkout() {
        console.log("checkout!");
    }
} 

const cartStore = new CartStore();
export default cartStore; // для классов
export const CartStoreContext = createContext(new CartStore()); // для функциональных компонентов