import React from 'react';
import { inject, observer} from 'mobx-react';
import classNames from 'classnames';

@inject('cartStore')
@observer
export default class extends React.Component {
  constructor(props) {
    super(props);
    this.state = {}
  }

  componentDidMount() {
    const store = this.props.cartStore;
    const inCart = this.props.inCart;
    if (inCart === "true") {
      this.paramsAddedToCart();
    } else {
      this.defaultParamsAddToCart();
    }

    if (!store.userId) {
      store.setUserId(this.props.userId); // записываем ID пользователя в хранилище
    }
  }

  paramsAddedToCart() {
    this.setState((state) => {
      return {
        inCart: true, 
        iconButton: "close",
        textButton: "В корзине",
        colorButton: classNames({'uk-button': true, 'uk-button-secondary': true})
      }
    });
  }

  defaultParamsAddToCart() {
    this.setState((state) => {
      return {
        inCart: false,
        iconButton: "cart",
        textButton: "В корзину",
        colorButton: classNames({'uk-button': true, 'uk-button-primary': true})
      }
    });
  }

  addSpinner() {
    this.refs.btnAdd.setAttribute("disabled", "disabled");
    this.refs.btnAddIcon.removeAttribute("uk-icon");
    this.refs.btnAddIcon.setAttribute("uk-spinner", "true");
  }

  removeSpinner() {
    this.refs.btnAdd.removeAttribute("disabled");
    this.refs.btnAddIcon.classList.remove("uk-spinner");
    this.refs.btnAddIcon.removeAttribute("uk-spinner");
  }

  handleClick = (e, productId) => {
    e.preventDefault();
    if (this.state.inCart) {
      this.handleRemoveFromCart(productId);
    } else {
      this.handleAddToCart(productId)
    }
  }

  handleAddToCart = (productId) => {
    const store = this.props.cartStore;
    store.addToCart(productId);
    if (store.stateAdding === "pending") {
      this.addSpinner();
    }

    const checkStateAdding = () => {
      if (store.stateAdding !== "pending") {
        this.removeSpinner()
        if (store.stateAdding === "done") {
          this.paramsAddedToCart()
        } else { // "error"
        this.refs.btnAddIcon.setAttribute("uk-icon", "cart")
          console.log(store.msg)
          store.msg = ""
        }
        clearInterval(checkingInterval);
      }
    }
    const checkingInterval = setInterval(checkStateAdding, 100);
  }

  handleRemoveFromCart = (productId) => {
    const store = this.props.cartStore;
    store.removeProduct({id: productId});
    if (store.stateRemove === "pending") {
      this.addSpinner();
    }
    const checkStateRemoving = () => {
      if (store.stateRemove !== "pending") {
        this.removeSpinner()
        if (store.stateRemove === "done") {
          this.defaultParamsAddToCart();
        } else { // "error"
          this.refs.btnAddIcon.setAttribute("uk-icon", "close");
          console.log(store.msg);
          store.msg = "";
        }
        clearInterval(checkingInterval);
      }
    }
    const checkingInterval = setInterval(checkStateRemoving, 100);
  }

  render() {
    const store = this.props.сartStore;
    const productId = this.props.productId;

    return (
      <button 
        className={this.state.colorButton}
        onClick = {e => this.handleClick(e, productId)}
        ref="btnAdd"
      >
        <i 
          className={classNames({'uk-icon': true, 'uk-margin-small-right': true})} 
          uk-icon={this.state.iconButton}
          ref="btnAddIcon"
        >
        </i>
        <span ref="btnAddText">{this.state.textButton}</span>
      </button>
    )
  }
}