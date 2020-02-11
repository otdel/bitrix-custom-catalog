import React, { Component } from "react";
import ReactDOM from "react-dom";

if(document.getElementById("react-example")) {
  const Example = require('./components/example').default;
  ReactDOM.render(<Example />, document.querySelector("#react-example"));
}
