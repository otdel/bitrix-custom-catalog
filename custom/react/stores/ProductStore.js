import { observable, action, computed, reaction, runInAction } from 'mobx';
import { createContext } from 'react';
//mobx.configure({ enforceActions: "observed" }) // don't allow state modifications outside actions 

class ProductStore {
  @observable properties = [];
  @observable state = "pending";          // "pending" / "done" / "error"
  @observable msg = "";
  @observable userId = undefined;

  @action.bound
  setUserId(userId) {
    this.userId = userId;
  }

  @computed({ keepAlive: true })
    get curProperties() {
      return this.properties;
    }

  /* получаем характеристики товара */
  async fetchProps(productId, iblockId, wareId) {
    let api = await fetch(`/api/v1/product/mr3/getById.php?userId=${this.userId}&productId=${productId}&iblockId=${iblockId}&wareId=${wareId}`);
    let json = await api.json();
    return json;
  }

  @action
  async getProperties(productId, iblockId, wareId) {
    this.stateLike = "pending"
    try {
      const response = await this.fetchProps(productId, iblockId, wareId);
        runInAction(() => {
          if (response.status === "success") {
            this.properties = response.data;
            this.state = "done"
          } else {
            this.state = "error"
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

const productStore = new ProductStore();
export default productStore; // для классов
export const ProductStoreContext = createContext(new ProductStore()); // для функциональных компонентов
