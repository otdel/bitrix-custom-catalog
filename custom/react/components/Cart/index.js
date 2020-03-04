import React, { Fragment } from 'react';
import { inject, observer } from 'mobx-react';
import { Provider } from 'mobx-react';
import classNames from 'classnames';

import CartList from '../CartList';
import CartFooter from '../CartFooter';

@inject('cartStore')
@observer
export default class extends React.Component {
  constructor(props) {
    super(props);
    this.state = {}
  }

  componentDidMount(){
    const store = this.props.cartStore;
    store.setUserId(this.props.userId); // записываем ID пользователя в хранилище!
    store.getCart(); // получаем данные о корзине пользователя и пишем в стор
  }

  render() {
    const store = this.props.cartStore;

    return (
      <Provider сartStore={store}>
        {
          store.state === "pending" && <div uk-spinner="true"></div>
        }
        <table className="uk-table">
          { store.count > 0 && 
            <Fragment>
              <caption>Товаров в вашей корзине: {store.count} на сумму {store.totalAmount}</caption>
              <CartList />
              <CartFooter />
            </Fragment>
          }
          {
            store.state === "done" && store.count === 0 &&
            <caption>Корзина пуста</caption>
          }
        </table>
      </Provider>
    );
  }
}