import React, { useState, useContext } from "react";
import { observer } from "mobx-react-lite";
import { CartStoreContext } from "../../stores/CartStore";

const  TitleBar = observer(() => {
  const cartStore = useContext(CartStoreContext);
  
  return (
    <div className="home-page">
      Total Number of Items in Cart: {cartStore.count}
    </div>
  );
});

export default TitleBar;