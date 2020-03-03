import React, { Component } from "react";
import ReactDOM from "react-dom";
import { Provider } from 'mobx-react';
require("es6-object-assign").polyfill(); // для IE11

import cartStore from './stores/CartStore';
import catalogStore from './stores/CatalogStore';

const stores = {
  cartStore,
  catalogStore,
};

// For easier debugging
window._____APP_STATE_____ = stores;

if(document.getElementById("react-example")) {
  const Example = require('./components/example').default;
  ReactDOM.render(<Example />, document.querySelector("#react-example"));
}

if(document.getElementById("mobx-example")) {
  const Catalog = require('./components/Catalog').default;
  ReactDOM.render(
    <Provider {...stores}>
      <Catalog />
    </Provider>
    , document.querySelector("#mobx-example")
  );
}

if(document.getElementById("react-cart")) {
  const Cart = require('./components/Cart').default;
  const userId = document.getElementById("react-cart").getAttribute("data-userid");
  ReactDOM.render(
    <Provider {...stores}>
      <Cart userId={userId} />
    </Provider>
    , document.querySelector("#react-cart")
  );
}
