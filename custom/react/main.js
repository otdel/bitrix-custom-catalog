import React, { Component } from "react";
import ReactDOM from "react-dom";
import { Provider } from 'mobx-react';
require('es6-object-assign').polyfill(); // для IE11

import cartStore from './stores/CartStore';
import likeStore from './stores/LikeStore';
import productStore from './stores/ProductStore';

const stores = {
  cartStore,
  likeStore,
  productStore,
};

// For easier debugging
window._____APP_STATE_____ = stores;

if(document.getElementById("react-example")) {
  const Example = require('./components/example').default;
  ReactDOM.render(<Example />, document.querySelector("#react-example"));
}

if(document.getElementById("react-counter-widget")) {
  const CountWidget = require('./components/CountWidget').default;
  const userId = document.getElementById("shopuserid").getAttribute("data-userid");
  ReactDOM.render(<Provider {...stores}>
    <CountWidget userId={userId} />
  </Provider>, document.querySelector("#react-counter-widget"));
}



if(document.getElementById("react-cart")) {
  const Cart = require('./components/Cart').default;
  const userId = document.getElementById("shopuserid").getAttribute("data-userid");
  ReactDOM.render(
    <Provider {...stores}>
      <Cart userId={userId} />
    </Provider>
    , document.querySelector("#react-cart")
  );
}

// Добавляем кнопки "Добавить в корзину" на страницу
if(document.getElementsByClassName("react-add-to-cart-button").length) {
  const userId = document.getElementById("shopuserid").getAttribute("data-userid");
  const AddToCartButton = require('./components/AddToCartBtn').default;
  document.querySelectorAll('.react-add-to-cart-button').forEach(function(button) {
    const productId = button.getAttribute("data-productid");
    const inCart = button.getAttribute("data-incart");
    ReactDOM.render(
      <Provider {...stores}>
        <AddToCartButton userId={userId} productId={productId} inCart={inCart} />
      </Provider>
      , button
    );
  });
}

// Добавляем лайки на страницу
if(document.getElementsByClassName("react-like-button").length) {
  const userId = document.getElementById("shopuserid").getAttribute("data-userid");
  const LikeButton = require('./components/LikeBtn').default;
  document.querySelectorAll('.react-like-button').forEach(function(button) {
    const productId = button.getAttribute("data-productid");
    const isLiked = button.getAttribute("data-isliked");
    const likes = button.getAttribute("data-likes");
    const isCategory = button.getAttribute("data-iscategory");
    ReactDOM.render(
      <Provider {...stores}>
        <LikeButton userId={userId} productId={productId} isLiked={isLiked} likes={likes} isCategory={isCategory} />
      </Provider>
      , button
    );
  });
}

// Добавляем схлопнутые характеристики товара 
if(document.getElementById("react-product-properties")) {
  const el = document.getElementById("react-product-properties");
  const userId = document.getElementById("shopuserid").getAttribute("data-userid");
  const productId = el.getAttribute("data-productid");
  const iblockId = el.getAttribute("data-iblockid");
  const wareId = el.getAttribute("data-wareid");
  const ProductProperties = require('./components/ProductProperties').default;

  ReactDOM.render(
    <Provider {...stores}>
      <ProductProperties userId={userId} productId={productId} iblockId={iblockId} wareId={wareId} />
    </Provider>
    , document.querySelector("#react-product-properties")
  );
}