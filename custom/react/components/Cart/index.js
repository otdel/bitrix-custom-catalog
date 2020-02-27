import React, { Fragment } from 'react';
import { inject, observer } from 'mobx-react';
import { Provider } from 'mobx-react';
import classNames from 'classnames';

import CartList from '../CartList';
import CartFooter from '../CartFooter';
import CartTest from '../CartTest';

@inject('cartStore')
@observer
export default class extends React.Component {
  render() {
    const store = this.props.cartStore;
    //console.log(this.props.userId);

    return (
      <Provider сartStore={store}>
        <table className="uk-table">
            <caption>Товаров в вашей корзине: {store.count} на сумму {store.totalAmount}</caption>
            <CartList />
            <CartFooter />

        </table>
      </Provider>
    );
  }
}