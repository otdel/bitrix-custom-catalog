import { observable, action, computed, reaction, runInAction } from 'mobx';
import { createContext } from 'react';

class LikeStore {
  @observable likesProducts = [];
  @observable state = "pending";          // "pending" / "done" / "error"
  @observable stateDislike = "pending";    // "pending" / "done" / "error"
  @observable stateLike = "pending";    // "pending" / "done" / "error"
  @observable msg = "";
  @observable userId = undefined;

  @action.bound
  setUserId(userId) {
    this.userId = userId;
  }

  /* пользователь ставит лайк */
  async fetchLike(productId) {
    console.log(`http://www.mocky.io/v2/5e730f5730000069002e61f2?cartUserId=${this.userId}&productId=${productId}`)
    let api = await fetch(`http://www.mocky.io/v2/5e730f5730000069002e61f2?cartUserId=${this.userId}&productId=${productId}`)  //await fetch(`/api/v1/cart/add?cartUserId=${this.userId}&productId=${productId}`);
    let json = await api.json();
    return json;
  }
  @action.bound
  async like(productId) {
    this.stateLike = "pending"
    try {
      const response = await this.fetchLike(productId);
        runInAction(() => {
          if (response.status === "success") {
            this.stateLike = "done"
            //this.likesProducts.push(product);
          } else {
            this.stateLike = "error"
            this.msg = response.message
          }
        })
    } catch (error) {
      runInAction(() => {
        this.stateLike = "error"
        console.log(error)
      })
    }
  }
  /* пользователь ставит дизлайк  */
  async fetchDislike(productId) {
    console.log(`http://www.mocky.io/v2/5e730f5730000069002e61f2?cartUserId=${this.userId}&productId=${productId}`)
    let api = await fetch(`http://www.mocky.io/v2/5e730f5730000069002e61f2?cartUserId=${this.userId}&productId=${productId}`);
    let json = await api.json();
    return json;
  }
  @action
  async dislike(product) {
    this.stateDislike = "pending"
    try {
      const response = await this.fetchDislike(product.id);
      runInAction(() => {
        if (response.status === "success") {
          this.stateDislike = "done"
          const index = this.likesProducts.indexOf(product);
          if (index >= 0) {
            this.likesProducts.splice(index, 1);
          }
        } else {
          this.stateDislike = "error"
          this.msg = response.message
        }
      })
    } catch (error) {
      runInAction(() => {
        this.stateDislike = "error"
        console.log(error)
      })
    }
  }
}

const likeStore = new LikeStore();
export default likeStore; // для классов
export const LikeStoreContext = createContext(new LikeStore()); // для функциональных компонентов
