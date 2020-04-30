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
              <caption className="uk-hidden">Товаров в вашей корзине: {store.count} на сумму {store.totalAmount}</caption>
              <CartList />
              <CartFooter />
            </Fragment>
          }
        </table>
        {
          store.stateOrder === "done" && store.count === 0 &&
          <div ref="orderMsg" className="uk-alert-success" uk-alert="true">
            <a className="uk-alert-close" uk-close="true"></a>
            <div ref="orderMsgText">Заказ успешно оформлен и отправлен менеджеру</div>
          </div>
        }
        {
          store.stateOrder === "error" && store.count === 0 && store.msg.includes('самостоятельно') &&
          <div ref="orderMsg" className="uk-alert-danger" uk-alert="true">
            <a className="uk-alert-close" uk-close="true"></a>
            <div ref="orderMsgText">{store.msg}</div>
          </div>
        }
        {
          store.state === "done" && store.count === 0 &&
          <div>Корзина пуста</div>
        }
        {
          store.state === "error" && store.count === 0 &&
          <div>Произошла ошибка: {store.msg}</div>
        }

      </Provider>
    );
  }
}