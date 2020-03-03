import React, { Fragment } from 'react';
import { inject, observer } from 'mobx-react';
import classNames from 'classnames';
import CartItem from '../CartItem';

@inject('сartStore')
@observer
export default class extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      items: []
    }
  }
  componentDidMount(){
    //var items = this.props.cartStore.fetchCart;
    //this.setState({ items: items });
    const store = this.props.сartStore;
    //store.fetchProjects();
  }
  handleClick = e => {
    this.props.сartStore.addToCart(e);
  }
  render() {
    const store = this.props.сartStore;
    return (
      <Fragment>
        <thead>
          <tr>
            <th>Товар</th>
            <th>Изображение</th>
            <th>Цена </th>
            <th>Дествия</th>
          </tr>
        </thead>
        <tbody>
          {
            store.productsInCart.map((item, idx) => {
              return (<CartItem item={item} key={idx}/>)
            })
          }
        </tbody>

      </Fragment>
    );
  }
}


