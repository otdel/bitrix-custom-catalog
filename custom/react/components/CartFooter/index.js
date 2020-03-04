import React from 'react';
import { inject, observer} from 'mobx-react';
import classNames from 'classnames';

@inject('cartStore')
@observer
export default class extends React.Component {
  handleClickRemoveAll = e => {
    this.props.cartStore.removeAllCart();
    this.refs.btnRemoveAll.setAttribute("disabled", "disabled");
  }
  handleClickCheckout = e => {
    this.props.cartStore.checkout();
    this.refs.btnCheckout.setAttribute("disabled", "disabled");
  }

  render() {
    const store = this.props.сartStore;
    
    return (
      <tfoot>
      <tr>
        <td>
            <button 
              className="uk-button"
              ref="btnRemoveAll"
              onClick = {() => this.handleClickRemoveAll()}
            >
              <i className="uk-icon" uk-icon="trash"></i>
              Очистить корзину
            </button>
          
        </td>
        <td colSpan="3">
          <button className="uk-button uk-button-primary"
            ref="btnCheckout"
            onClick = {() => this.handleClickCheckout()}
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