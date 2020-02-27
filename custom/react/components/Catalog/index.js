import React from 'react';
import { inject, observer } from 'mobx-react';
import { Provider } from 'mobx-react';

import TitleBar from '../TitleBar';
import ProductList from '../ProductList';
import Cart from '../Cart';

@inject('cartStore')
@inject('catalogStore')
@observer
export default class extends React.Component {
  render() {
    const store = this.props.cartStore
    console.log(store)
    return (
      <Provider сartStore={store}>
        {this.props.cartStore.count}
        catalog count {this.props.catalogStore.count}
        <TitleBar/>
        <div uk-grid="true">
          <div className="uk-width-1-4@m">
            <Cart />
          </div>
          <div className="uk-width-3-4@m">
            <ProductList />
          </div>
        </div>
      </Provider>
    );
  }
}