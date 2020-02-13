import React from 'react';
import { inject, observer } from 'mobx-react';
//import DevTools from 'mobx-react-devtools';

import TitleBar from '../TitleBar';
import ProductList from '../ProductList';
/*import Cart from './Cart';*/
@inject('CartStore')
@observer
export default class Catalog extends React.Component {
  render() {
    return (
      <div className="catalog-page">
      {this.props.CartStore.count}
      <TitleBar/>
      <ProductList/>
      
      </div>
    );
  }
}