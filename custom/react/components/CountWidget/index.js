import React from 'react';
import { inject, observer } from 'mobx-react';
<<<<<<< HEAD
import { observable, action, computed, reaction, runInAction, configure } from 'mobx';
=======
>>>>>>> master
import { Provider } from 'mobx-react';
import getNumWord from '../../../js/getNumWord';

@inject('cartStore')
@observer
export default class extends React.Component {
  constructor(props) {
    super(props);
    this.state = {}
  }

  componentDidMount() {
    const store = this.props.cartStore;
    store.setUserId(this.props.userId); // записываем ID пользователя в хранилище!
    store.getCart(); // получаем данные о корзине пользователя и пишем в стор
  }

  render() {
      const store = this.props.cartStore;
      const goods = getNumWord(store.count, ["товар","товара","товаров"]);

      return (
        <div className="uk-padding">
          {
            store.state === "pending" && <div uk-spinner="true"></div>
          }
          {
            store.state === "done" && store.count > 0 && <span>{store.count} {goods}</span>
          }
          {
            store.state === "done" && store.count === 0 && <span>Корзина пуста</span>
          }
        </div>
    );
  }
}
