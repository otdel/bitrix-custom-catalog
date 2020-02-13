import React, { useState, useContext } from "react";
import { observer } from "mobx-react-lite";
import { CounterStoreContext } from "../../stores/CounterStore";

const Counter = observer(() => {
  const counterStore = useContext(CounterStoreContext);

    return (
      <div>
        <div>{counterStore.count}</div>
        <button className="uk-button" onClick={() => counterStore.count++}>Добавить в корзину</button>
      </div>
    );
  
});

export default Counter;