import React, { Component } from "react";
import ReactDOM from "react-dom";
import { Provider } from 'mobx-react';

import cartStore from './stores/CartStore';

const stores = {
  cartStore
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
  , document.querySelector("#mobx-example"));
}
