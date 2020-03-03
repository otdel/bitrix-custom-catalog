import React from 'react';
import { inject, observer} from 'mobx-react';
import classNames from 'classnames';

@inject('cartStore')
@observer
export default class extends React.Component {
  handleCleckRemoveAll = e => {
    this.props.cartStore.removeAllFromCart();
  }
  handleCleckCheckout = e => {
    this.props.cartStore.checkout();
  }

  render() {
    const store = this.props.сartStore;
    
    return (
      <tfoot>
      <tr>
        <td>
            <input type="hidden" name="oipCartActionHandler" value="$component->getComponentId()" />
            <button className="uk-button" name="GLOBAL_CART_ACTION_NAME?"
              value="GLOBAL_CART_ACTION_REMOVE_ALL"
              onClick = {() => this.handleCleckRemoveAll()}
            >
              <i className="uk-icon" uk-icon="trash"></i>
              Очистить корзину
            </button>
          
        </td>
        <td colSpan="3">
          <input type="hidden" name="oipCartActionHandler" value="component->getComponentId()" />
          <button className="uk-button uk-button-primary" name="GLOBAL_CART_ACTION_NAME"
            value="GLOBAL_CART_ACTION_CREATE_ORDER"
            onClick = {() => this.handleCleckCheckout()}
          >
            <i className="uk-icon" uk-icon="credit-card" ></i>
              Оформить заказ
            </button>
          
        </td>
      </tr>
      </tfoot>
    )
  }
}