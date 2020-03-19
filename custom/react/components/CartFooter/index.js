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

  addSpinner() {
    this.refs.btnOrder.setAttribute("disabled", "disabled");
    this.refs.btnOrderIcon.removeAttribute("uk-icon");
    this.refs.btnOrderIcon.setAttribute("uk-spinner", "true");
  }

  removeSpinner() {
    this.refs.btnOrder.removeAttribute("disabled");
    this.refs.orderMsg.classList.remove("uk-hidden");
    this.refs.btnOrderIcon.classList.remove("uk-spinner");
    this.refs.btnOrderIcon.removeAttribute("uk-spinner");
    this.refs.btnOrderIcon.setAttribute("uk-icon", "credit-card");
  }

  handleClickOrder = e => {
    const store = this.props.cartStore;
    this.props.cartStore.createOrder();
    if (store.stateOrder === "pending") {
      this.addSpinner()
    }
    const checkStateOrder = () => {
      if (store.stateOrder !== "pending") {
        this.removeSpinner()
        if (store.stateOrder === "done") {
          this.refs.orderMsgText.textContent = "Заказ успешно создан и отправлен вашему менеджеру";
        } else { // "error"
          this.refs.orderMsgText.textContent = store.msg;
          console.log(store.msg);
          store.msg = "";
        }
        clearInterval(checkingInterval);
      }
    }

    const checkingInterval = setInterval(checkStateOrder, 100);
  }

  render() {
    const store = this.props.сartStore;
    
    return (
      <tfoot>
      <tr>
        <td colSpan="4">
          <div ref="orderMsg" className="uk-alert-danger uk-hidden" uk-alert="true">
            <a className="uk-alert-close" uk-close="true"></a>
              <p ref="orderMsgText"></p>
          </div>
        </td>
      </tr>
      <tr>
        <td>
            <button 
              className="uk-button"
              ref="btnRemoveAll"
              onClick = {() => this.handleClickRemoveAll()}
            >
              <i className="uk-icon uk-margin-small-right" uk-icon="trash"></i>
              Очистить корзину
            </button>
        </td>
        <td colSpan="3">
          <button className="uk-button uk-button-primary"
            ref="btnOrder"
            onClick = {() => this.handleClickOrder()}
          >
            <i className="uk-icon uk-margin-small-right" uk-icon="credit-card" ref="btnOrderIcon" ></i>
              Оформить заказ
            </button>
        </td>
      </tr>
      </tfoot>
    )
  }
}