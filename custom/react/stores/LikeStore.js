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
  async fetchLike(productId, isCategory) {
    let api = await fetch(`/api/v1/like/action/product/add.php?userId=${this.userId}&productId=${productId}`);
    if (isCategory) {
      api = await fetch(`/api/v1/like/action/category/add.php?userId=${this.userId}&sectionId=${productId}`);
    }
    let json = await api.json();
    return json;
  }

  @action.bound
  async like(productId, isCategory=false) {
    this.stateLike = "pending"
    try {
      const response = await this.fetchLike(productId, isCategory);
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
  async fetchDislike(productId, isCategory) {
    let api = await fetch(`/api/v1/like/action/product/remove.php?userId=${this.userId}&productId=${productId}`);
    if (isCategory) {
      api = await fetch(`/api/v1/like/action/category/remove.php?userId=${this.userId}&sectionId=${productId}`);
    }
    let json = await api.json();
    return json;
  }

  @action
  async dislike(product, isCategory=false) {
    this.stateDislike = "pending"
    try {
      const response = await this.fetchDislike(product.id, isCategory);
      runInAction(() => {
        if (response.status === "success") {
          const index = this.likesProducts.indexOf(product);
          if (index >= 0) {
            this.likesProducts.splice(index, 1);
          }
          this.stateDislike = "done"
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


  /* получаем количество лайков товара */
  async fetchAllLikes(productId) {
    let api = await fetch(`/api/v1/like/product/getAll.php?userId=${this.userId}&productId=${productId}`);
    let json = await api.json();
    return json;
  }
  @action.bound
  async allLikes(productId) {
    this.stateLike = "pending"
    try {
      const response = await this.fetchAllLikes(productId);
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

}

const likeStore = new LikeStore();
export default likeStore; // для классов
export const LikeStoreContext = createContext(new LikeStore()); // для функциональных компонентов
