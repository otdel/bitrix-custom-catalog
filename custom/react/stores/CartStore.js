import { observable, action, computed, reaction, runInAction } from 'mobx';
import { createContext } from 'react';
//mobx.configure({ enforceActions: "observed" })
class CartStore {
    @observable productsInCart = [];
    @observable state = "pending";          // "pending" / "done" / "error"
    @observable stateRemove = "pending";    // "pending" / "done" / "error"
    @observable stateRemoveAll = "pending"; // "pending" / "done" / "error"
    @observable stateOrder = "pending";     // "pending" / "done" / "error"
    @observable stateAdding = "pending";    // "pending" / "done" / "error"
    @observable msg = "";
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
    //@action.bound
    /*
    addToCart(product) {
        this.productsInCart.push(product);
    }*/
    @action.bound
    removeFromCart(product) {
        const index = this.productsInCart.indexOf(product);
        if (index >= 0) {
            this.productsInCart.splice(index, 1);
        }
    }

    /* add to cart */
    async fetchAddToCart(productId) {
        let api = await fetch(`/api/v1/cart/add?cartUserId=${this.userId}&productId=${productId}`);
        let json = await api.json();
        return json;
    }
    @action.bound
    async addToCart(productId) {
        this.stateAdding = "pending"
        try {
            const response = await this.fetchAddToCart(productId);
            runInAction(() => {
                if (response.status === "success") {
                    this.stateAdding = "done"
                    //this.productsInCart.push(product);
                } else {
                    this.stateAdding = "error"
                    this.msg = response.msg
                }
            })
        } catch (error) {
            runInAction(() => {
                this.stateAdding = "error"
                console.log(error)
            })
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
                    this.msg = response.msg
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
        return json;
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
                    this.msg = response.msg
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
        return json;
    }
    @action
    async getCart() {
        this.productsInCart = []
        this.state = "pending"
        try {
            const response = await this.fetchCart();
            const products = JSON.parse(response.data);
            runInAction(() => {
                if (response.status === "success") {
                    this.state = "done"
                    this.productsInCart = products
                } else {
                    this.stateOrder = "error"
                    this.msg = response.msg
                }
            })
        } catch (error) {
            runInAction(() => {
                this.state = "error"
                console.log(error)
            })
        }
    }

    /* checkout (order) */
    async fetchOrder() {
        let api = await fetch(`/api/v1/cart/createOrder?cartUserId=${this.userId}`);
        let json = await api.json();
        return json;
    }
    @action.bound
    async createOrder() {
        const response = await this.fetchOrder();
        this.stateOrder = "pending"
        try {
            if (response.status === "success") {
                this.stateOrder = "done"
                this.productsInCart = []
            } else {
                this.stateOrder = "error"
                this.msg = response.msg
            }
        } catch (error) {
            runInAction(() => {
                this.stateOrder = "error"
                console.log(error)
            })
        }
    }
} 

const cartStore = new CartStore();
export default cartStore; // для классов
export const CartStoreContext = createContext(new CartStore()); // для функциональных компонентов