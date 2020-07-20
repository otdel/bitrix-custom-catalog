import { observable, action, computed, reaction, runInAction, configure } from 'mobx';
import { createContext } from 'react';
configure({ enforceActions: "observed" })

class CartStore {
    @observable productsInCart = [];
    @observable state = "pending";          // "pending" / "done" / "error"
    @observable stateRemove = "pending";    // "pending" / "done" / "error"
    @observable stateRemoveAll = "pending"; // "pending" / "done" / "error"
    @observable stateOrder = "pending";     // "pending" / "done" / "error"
    @observable stateAdding = "pending";    // "pending" / "done" / "error"
    @observable msg = "";
    @observable userId = undefined;
    @observable productIdByFilter = null;   // устанавливаем ID товара при выборе схлопнутого товара в деталке

    @action.bound
    setProductIdByFilter(productId) {
        this.productIdByFilter = productId;
        console.log(this.productIdByFilter)
    }
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
    removeFromCart(product) {
        const index = this.productsInCart.indexOf(product);
        if (index >= 0) {
            this.productsInCart.splice(index, 1);
        }
    }

    /* add to cart */
    async fetchAddToCart(productId) {
        let api = await fetch(`/api/v1/cart/add/?cartUserId=${this.userId}&productId=${productId}`);
        let json = await api.json();
        return json;
    }
    @action.bound
    async addToCart(productId) {
        this.stateAdding = "pending"
        if (this.productIdByFilter !== null) {
            productId = this.productIdByFilter
        }
        try {
            const response = await this.fetchAddToCart(productId);
            runInAction(() => {
                if (response.status === "success") {
                    this.getCart();
                    this.stateAdding = "done"
                    // если мини-корзина будет в каталоге, то здесь нужно будет вызывать получение корзины
                } else {
                    this.stateAdding = "error"
                    this.msg = response.message
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
        let api = await fetch(`/api/v1/cart/removeAll/?cartUserId=${this.userId}`);
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
                    this.productsInCart = []
                    this.stateRemoveAll = "done"
                } else {
                    this.stateRemoveAll = "error"
                    this.msg = response.message
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
        let api = await fetch(`/api/v1/cart/remove/?cartUserId=${this.userId}&productId=${productId}`);
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
                    const index = this.productsInCart.indexOf(product);
                    if (index >= 0) {
                        this.productsInCart.splice(index, 1);
                    }
                    this.getCart();
                    this.stateRemove = "done"
                } else {
                    this.stateRemove = "error"
                    this.msg = response.message
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
        let api = await fetch(`/api/v1/cart/getByUserId/?cartUserId=${this.userId}`);
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

                    this.productsInCart = products
                    this.state = "done"
                } else {
                    this.stateOrder = "error"
                    this.msg = response.message
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
        let api = await fetch(`/api/v1/cart/createOrder/?cartUserId=${this.userId}`);
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
                this.msg = response.message
                // в случае ошибки, когда не найден менеджер следует очищать корзину:
                if (response.message.includes("менеджер") && response.message.includes("самостоятельно")) {
                    this.productsInCart = []
                }
                console.log(response)
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
