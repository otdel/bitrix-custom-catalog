import { observable, action, computed, reaction, runInAction } from 'mobx';
import { createContext } from 'react';

class LikeStore {
  @observable likesProducts = [];
  @observable state = "pending";          // "pending" / "done" / "error"
  @observable stateRemove = "pending";    // "pending" / "done" / "error"
  @observable stateAdding = "pending";    // "pending" / "done" / "error"
  @observable msg = "";
  @observable userId = undefined;

  @action.bound
  setUserId(userId) {
    this.userId = userId;
  }
  @action.bound
  removeLike(product) {
    const index = this.likesProducts.indexOf(product);
    if (index >= 0) {
      this.likesProducts.splice(index, 1);
    }
  }

  /* пользователь ставит лайк */
  async fetchLike(productId) {
    let api = await fetch(`http://www.mocky.io/v2/5e730f5730000069002e61f2?cartUserId=${this.userId}&productId=${productId}`)  //await fetch(`/api/v1/cart/add?cartUserId=${this.userId}&productId=${productId}`);
    let json = await api.json();
    return json;
  }
  @action.bound
  async like(productId) {
    this.stateAdding = "pending"
    try {
      const response = await this.fetchLike(productId);
        runInAction(() => {
          if (response.status === "success") {
            this.stateAdding = "done"
            //this.likesProducts.push(product);
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
  /* пользователь ставит дизлайк  */
  async fetchDislike(productId) {
    let api = await fetch(`http://www.mocky.io/v2/5e730f5730000069002e61f2?cartUserId=${this.userId}&productId=${productId}`);
    let json = await api.json();
    return json;
  }
  @action
  async dislike(product) {
    this.stateRemove = "pending"
    try {
      const response = await this.fetchDislike(product.id);
      runInAction(() => {
        if (response.status === "success") {
          this.stateRemove = "done"
          const index = this.likesProducts.indexOf(product);
          if (index >= 0) {
            this.likesProducts.splice(index, 1);
          }
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

  /* get current Likes by user */
  async fetchLikes() {
    let api = await fetch(`/api/v1/cart/getByUserId?cartUserId=${this.userId}`); // временно такие же как корзина
    let json = await api.json();
    return json;
  }
  @action
  async getLikes() {
    this.likesProducts = []
    this.state = "pending"
    try {
      const response = await this.fetchLikes();
      const products = JSON.parse(response.data);
      runInAction(() => {
        if (response.status === "success") {
          this.state = "done"
          this.likesProducts = products
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
}

const likeStore = new LikeStore();
export default likeStore; // для классов
export const LikeStoreContext = createContext(new LikeStore()); // для функциональных компонентов
