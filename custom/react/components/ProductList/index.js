import React from 'react';
import { inject } from 'mobx-react';
import classNames from 'classnames';

@inject('CartStore')
export default class ProductList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
          items: [{
            id: 0,
            name: "Вытяжка Серая 2000 Турбо",
            price: 200
          }, {
            id: 1,
            name: "Вытяжка Золотой Граммофон",
            price: 1000
          }, {
            id: 2,
            name: "Пылесос Гроза котов",
            price: 500
          }]
        }
        this.handleClick = this.handleClick.bind(this);
      }
  
    handleClick = e => {
        this.props.cartStore.addToCart(e);    
    } 
    renderProducts() {
        return this.state.items.map((item, idx) => {
            return (
            <tr>
                <td  key = {idx} >
                {item.name} </td><td>{item.price}</td><td>
                <button onClick = {() => this.handleClick(item)} > Add to Cart </button>
            </td></tr>
            )
        })
    } 
    render() {
        return (
          <div className={classNames({'uk-child-width-1-3@m': true, 'uk-grid-small': true, 'uk-grid-match': true})} uk-grid="true">
            <div>
                <div className={classNames({'uk-card': true, 'uk-card-default': true, 'uk-card-body': true})}>
                    <h3 className="uk-card-title">Default</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                </div>
            </div>
            <div>
                <div className={classNames({'uk-card': true, 'uk-card-primary': true, 'uk-card-body': true})}>
                    <h3 className="uk-card-title">Primary</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                </div>
            </div>
            <div>
                <div className={classNames({'uk-card': true, 'uk-card-secondary': true, 'uk-card-body': true})}>
                    <h3 className="uk-card-title">Secondary</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                </div>
            </div>
          </div>
        );
    }
}