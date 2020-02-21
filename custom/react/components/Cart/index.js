import React from 'react';
import { inject, observer } from 'mobx-react';
import classNames from 'classnames';
import CartItem from '../CartItem';

@inject('сartStore')
@observer
export default class Cart extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      items: []
    }
  }
  componentDidMount(){
    //var items = this.props.cartStore.fetchCart;
    //console.log(items);
    //this.setState({ items: items });
    //this.props.cartStore.fetchProjects;
    const store = this.props.сartStore;
    //console.log(store.fetchCart)
    store.fetchProjects();
//    console.log(store.githubProjects.data);
  }
  handleClick = e => {
    this.props.сartStore.addToCart(e);    
    
  }
  render() {
    const store = this.props.сartStore;
    //console.log(store.fetchCart)
//    store.fetchProjects();
//    console.log(store.githubProjects);
    return (
      <div>
        <h2>Корзина</h2>
        К оплате: {store.totalAmount}
        <br />
        Status: {store.state}
        <br />
        Status: {store.error}
        <br />
        status items: {store.productsInCart.status}
        <br />
        length: {store.productsInCart.length}
        <br />
        {
          (store.state === "done") && store.productsInCart.map((item, idx) => {
            return <div key={idx}>{item.id}!!!!</div>
            //return (<div key={item.id}>{item.name}</div>)
          })
        }

        {
          store.productsInCart.map((item, idx) => {
            return (<CartItem item={item} key={idx}/>)
          })
        }
      </div>
    );
  }
}