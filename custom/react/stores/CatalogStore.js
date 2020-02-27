import { observable, action, computed, reaction, runInAction } from 'mobx';
import { createContext } from 'react';
//mobx.configure({ enforceActions: "observed" })
class CatalogStore {
    @observable productsInCatalog = [];
    @observable state = "pending"; // "pending" / "done" / "error"
    @observable msg = ""; // "pending" / "done" / "error"

    @computed get count() {
        return this.productsInCatalog.length;
    }

    async fetchCatalog() {
        let api = await fetch(`http://www.mocky.io/v2/5e575162300000dc08fd38f8`);
        let json = await api.json();
        return json.data.items;
    }

    @action
    async fetchCatalog() {
        this.productsInCatalog = []
        this.state = "pending"
        try {
            const items = await this.fetchCatalog();
            runInAction(() => {
                this.state = "done"
                this.productsInCatalog = items
            })
        } catch (error) {
            runInAction(() => {
                this.state = "error"
                console.log(error)
            })
        }
    }
} 

const catalogStore = new CatalogStore();
export default catalogStore; // для классов
export const CatalogStoreContext = createContext(new CatalogStore()); // для функциональных компонентов